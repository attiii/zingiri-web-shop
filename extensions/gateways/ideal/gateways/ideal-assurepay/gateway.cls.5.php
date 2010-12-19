<?php

class Gateway extends GatewayCore
{
	// Load iDEAL settings
	public function __construct()
	{
		$this->init();
	}


	// Setup payment
	public function doSetup()
	{
		$sHtml = '';

		// Look for proper GET's en POST's
		if(empty($_GET['order_id']) || empty($_GET['order_code']))
		{
			$sHtml .= wsGatewayMessage('Invalid issuer request.');
		}
		else
		{
			$sOrderId = $_GET['order_id'];
			$sOrderCode = $_GET['order_code'];


			// Lookup transaction
			if($this->getRecordByOrder($sOrderId, $sOrderCode))
			{
				if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
				{
					$sHtml .= wsGatewayMessage('Transaction already completed');
				}
				elseif((strcmp($this->oRecord['transaction_status'], 'OPEN') === 0) && !empty($this->oRecord['transaction_url']))
				{
					header('Location: ' . $this->oRecord['transaction_url']);
					exit;
				}
				else
				{
					$oAssurePay = new AssurePay();
					$oAssurePay->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['MERCHANT_KEY'], $this->aSettings['SHOP_ID']);
					$oAssurePay->setCachePath($this->aSettings['TEMP_PATH']);
						

					$aIssuerList = $oAssurePay->doIssuerRequest();
					$sIssuerList = '';

					if($oAssurePay->hasErrors())
					{
						GatewayCore::output('<code>' . var_export($oAssurePay->getErrors(), true) . '</code>');
					}

					if(empty($this->oRecord['transaction_log']) == false)
					{
						$this->oRecord['transaction_log'] .= "\n\n";
					}

					$this->oRecord['transaction_log'] .= z_('Executing IssuerRequest on ') . date('Y-m-d, H:i:s') . '.';
					$this->save();


					foreach($aIssuerList as $k => $v)
					{
						$sIssuerList .= '<option value="' . $k . '">' . htmlspecialchars($v) . '</option>';
					}

					$sHtml .= '
<form action="' . zurl(GatewayCore::getRootUrl() . '?page=pay_ideal&action=transaction&order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '" method="post" id="checkout">
	<p><b>'.z_('Choose your bank').'</b><br><select name="issuer_id" style="margin: 6px; width: 200px;">' . $sIssuerList . '</select><br><input type="submit" value="'.z_('Continue').'"></p>
</form>';
				}
			}
			else
			{
				$sHtml .= wsGatewayMessage('Invalid issuer request.');
			}
		}

		GatewayCore::output($sHtml);
	}


	// Execute payment
	public function doTransaction()
	{
		$sHtml = '';

		// Look for proper GET's en POST's
		if(empty($_POST['issuer_id']) || empty($_GET['order_id']) || empty($_GET['order_code']))
		{
			$sHtml .= wsGatewayMessage('Invalid transaction request.');
		}
		else
		{
			$sIssuerId = $_POST['issuer_id'];
			$sOrderId = $_GET['order_id'];
			$sOrderCode = $_GET['order_code'];

			// Lookup transaction
			if($this->getRecordByOrder($sOrderId, $sOrderCode))
			{
				if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
				{
					$sHtml .= wsGatewayMessage('Transaction already completed');
				}
				elseif((strcmp($this->oRecord['transaction_status'], 'OPEN') === 0) && !empty($this->oRecord['transaction_url']))
				{
					header('Location: ' . $this->oRecord['transaction_url']);
					exit;
				}
				else
				{
					$oAssurePay = new AssurePay();
					$oAssurePay->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['MERCHANT_KEY'], $this->aSettings['SHOP_ID']);
					$oAssurePay->setCachePath($this->aSettings['TEMP_PATH']);

					list($sTransactionId, $sTransactionUrl) = $oAssurePay->doTransactionRequest($sIssuerId, $this->oRecord['order_id'], $this->oRecord['transaction_amount'], $this->oRecord['transaction_description'], $this->oRecord['transaction_code'], GatewayCore::getRootUrl() . 'return.php', GatewayCore::getRootUrl() . 'report.php');

					if($oAssurePay->hasErrors())
					{
						GatewayCore::output('<code>' . var_export($oAssurePay->getErrors(), true) . '</code>');
					}

					if(empty($this->oRecord['transaction_log']) == false)
					{
						$this->oRecord['transaction_log'] .= "\n\n";
					}

					$this->oRecord['transaction_log'] .= z_('Executing TransactionRequest on ') . date('Y-m-d, H:i:s') . '. '.z_('Received').': ' . $sTransactionId;
					$this->oRecord['transaction_id'] = $sTransactionId;
					$this->oRecord['transaction_url'] = $sTransactionUrl;
					$this->oRecord['transaction_status'] = 'OPEN';
					$this->oRecord['transaction_date'] = time();

					$this->save();

					// die('<a href="' . $oAssurePay->getTransactionUrl() . '">' . $oAssurePay->getTransactionUrl() . '</a>');
					$oAssurePay->doTransaction();
				}
			}
			else
			{
				$sHtml .= wsGatewayMessage('Invalid transaction request.');
			}
		}

		GatewayCore::output($sHtml);
	}


	// Catch return
	public function doReturn()
	{
		$sHtml = '';

		if(empty($_GET['trxid']) || empty($_GET['ec']) || empty($_GET['status']) || empty($_GET['sha1']))
		{
			$sHtml .= wsGatewayMessage('Invalid return request.');
		}
		else
		{
			$sTransactionId = $_GET['trxid'];
			$sTransactionCode = $_GET['ec'];
			$sTransactionStatus = $_GET['status'];
			$sSignature = $_GET['sha1'];

			// Lookup record
			if($this->getRecordByTransaction($sTransactionId, $sTransactionCode))
			{
				if(strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
				{
					if($this->oRecord['transaction_success_url'])
					{
						header('Location: ' . $this->oRecord['transaction_success_url']);
						exit;
					}
					else
					{
						$sHtml .= '<p>'.z_('Your payment was processed successfully').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl(1)) . '\'"></p>';
					}
				}
				else
				{
					$oAssurePay = new AssurePay();
					$oAssurePay->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['MERCHANT_KEY'], $this->aSettings['SHOP_ID']);
					$oAssurePay->setCachePath($this->aSettings['TEMP_PATH']);

					$this->oRecord['transaction_status'] = $oAssurePay->doStatusRequest($sTransactionId, $sTransactionCode, $sTransactionStatus, $sSignature);

					if($oAssurePay->hasErrors())
					{
						GatewayCore::output('<code>' . var_export($oAssurePay->getErrors(), true) . '</code>');
					}

					if(empty($this->oRecord['transaction_log']) == false)
					{
						$this->oRecord['transaction_log'] .= "\n\n";
					}

					$this->oRecord['transaction_log'] .= z_('Executing StatusRequest on ') . date('Y-m-d, H:i:s') . ' '.z_('for').' #' . $this->oRecord['transaction_id'] . '. '.z_('Received').': ' . $this->oRecord['transaction_status'];

					$this->save();



					// Handle status change
					if(function_exists('gateway_update_order_status'))
					{
						gateway_update_order_status($this->oRecord, 'doReturn');
					}



					// Set status message
					if(strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
					{
						$sHtml .= '<p>'.z_('Your payment was processed successfully').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . zurl(GatewayCore::getRootUrl(1)) . '\'"></p>';
					}
					elseif((strcasecmp($this->oRecord['transaction_status'], 'OPEN') === 0) && !empty($this->oRecord['transaction_url']))
					{
						$sHtml .= '<p>'.z_('Your payment is not complete yet').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . htmlspecialchars($this->oRecord['transaction_url']) . '\'"></p>';
					}
					else
					{
						if(strcasecmp($this->oRecord['transaction_status'], 'CANCELLED') === 0)
						{
							$sHtml .= '<p>'.z_('Your payment was cancelled. Please try again.').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . zurl(GatewayCore::getRootUrl() . '?page=pay_ideal&action=setup&order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
						}
						elseif(strcasecmp($this->oRecord['transaction_status'], 'EXPIRED') === 0)
						{
							$sHtml .= '<p>'.z_('Your payment failed. Please try again.').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . zurl(GatewayCore::getRootUrl() . '?page=pay_ideal&action=setup&order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
						}
						else // if(strcasecmp($this->oRecord['transaction_status'], 'FAILURE') === 0)
						{
							$sHtml .= '<p>'.z_('Your payment failed. Please try again.').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . zurl(GatewayCore::getRootUrl() . '?page=pay_ideal&action=setup&order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
						}


						if($this->oRecord['transaction_payment_url'])
						{
							$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_payment_url']) . '">'.z_('Choose another payment method').'</a></p>';
						}
						elseif($this->oRecord['transaction_failure_url'])
						{
							$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_failure_url']) . '">'.z_("I can't pay via iDEAL").'</a></p>';
						}
					}


					if($this->oRecord['transaction_success_url'] && (strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0))
					{
						header('Location: ' . $this->oRecord['transaction_success_url']);
						exit;
					}
				}
			}
			else
			{
				$sHtml .= wsGatewayMessage('Invalid return request.');
			}
		}

		GatewayCore::output($sHtml);
	}


	// Catch report
	public function doReport()
	{
		$sHtml = '';

		if(empty($_GET['trxid']) || empty($_GET['ec']) || empty($_GET['status']) || empty($_GET['sha1']))
		{
			$sHtml .= wsGatewayMessage('Invalid report request.');
		}
		else
		{
			$sTransactionId = $_GET['trxid'];
			$sTransactionCode = $_GET['ec'];
			$sTransactionStatus = $_GET['status'];
			$sSignature = $_GET['sha1'];

			// Lookup record
			if($this->getRecordByTransaction($sTransactionId, $sTransactionCode))
			{
				$oAssurePay = new AssurePay();
				$oAssurePay->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['MERCHANT_KEY'], $this->aSettings['SHOP_ID']);
				$oAssurePay->setCachePath($this->aSettings['TEMP_PATH']);

				$this->oRecord['transaction_status'] = $oAssurePay->doStatusRequest($sTransactionId, $sTransactionCode, $sTransactionStatus, $sSignature);

				if($oAssurePay->hasErrors())
				{
					GatewayCore::output('<code>' . var_export($oAssurePay->getErrors(), true) . '</code>');
				}

				if(empty($this->oRecord['transaction_log']) == false)
				{
					$this->oRecord['transaction_log'] .= "\n\n";
				}

				$this->oRecord['transaction_log'] .= z_('Executing StatusRequest on ') . date('Y-m-d, H:i:s') . ' '.z_('for').' #' . $this->oRecord['transaction_id'] . '. '.z_('Received').': ' . $this->oRecord['transaction_status'];

				$this->save();



				// Handle status change
				if(function_exists('gateway_update_order_status'))
				{
					gateway_update_order_status($this->oRecord, 'doReport');
				}

				$sHtml .= wsGatewayMessage('The transaction has been updated.');
			}
			else
			{
				$sHtml .= wsGatewayMessage('Invalid report request.');
			}
		}

		GatewayCore::output($sHtml);
	}


	// Validate all open transactions
	public function doValidate()
	{
		$sql = "SELECT * FROM `" . DATABASE_PREFIX . "transactions` WHERE (`transaction_status` = 'OPEN') AND (`transaction_method` = '" . addslashes($this->aSettings['GATEWAY_METHOD']) . "') ORDER BY `id` ASC;";
		$oRecordset = mysql_query($sql) or die('QUERY: ' . $sql . '<br><br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

		$sHtml = '<b>'.z_('Verification of outstanding transactions.').'</b><br>';

		if(mysql_num_rows($oRecordset))
		{
			while($oRecord = mysql_fetch_assoc($oRecordset))
			{
				// Execute status request
				$oAssurePay = new AssurePay();
				$oAssurePay->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['MERCHANT_KEY'], $this->aSettings['SHOP_ID']);
				$oAssurePay->setCachePath($this->aSettings['TEMP_PATH']);

				$oRecord['transaction_status'] = $oAssurePay->doStatusRequest($oRecord['transaction_id']);

				if($oAssurePay->hasErrors())
				{
					GatewayCore::output('<code>' . var_export($oAssurePay->getErrors(), true) . '</code>');
				}

				if(empty($oRecord['transaction_log']) == false)
				{
					$oRecord['transaction_log'] .= "\n\n";
				}

				$oRecord['transaction_log'] .= z_('Executing StatusRequest on ') . date('Y-m-d, H:i:s') . ' '.z_('for').' #' . $oRecord['transaction_id'] . '. '.z_('Received').': ' . $oRecord['transaction_status'];

				$this->save($oRecord);


				// Add to body
				$sHtml .= '<br>#' . $oRecord['transaction_id'] . ' : ' . $oRecord['transaction_status'];


				// Handle status change
				if(function_exists('gateway_update_order_status'))
				{
					gateway_update_order_status($oRecord, 'doValidate');
				}
			}

			$sHtml .= '<br><br><br>'.z_('Updated all outstanding transactions');
		}
		else
		{
			$sHtml .= '<br>'.z_('No outstanding transactions could be found.');
		}

		GatewayCore::output('<p>' . $sHtml . '</p><p>&nbsp;</p><p><input type="button" value="'.z_('Close window').'" onclick="javascript: window.close();"></p>');
	}
}

?>