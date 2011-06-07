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
					$oIssuerRequest = new IssuerRequest();
					$oIssuerRequest->setSecurePath($this->aSettings['CERTIFICATE_PATH']);
					$oIssuerRequest->setCachePath($this->aSettings['TEMP_PATH']);
					$oIssuerRequest->setPrivateKey($this->aSettings['PRIVATE_KEY_PASS'], $this->aSettings['PRIVATE_KEY_FILE'], $this->aSettings['PRIVATE_CERTIFICATE_FILE']);
					$oIssuerRequest->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['SUB_ID']);
					$oIssuerRequest->setAquirer($this->aSettings['GATEWAY_NAME'], $this->aSettings['TEST_MODE']);


					$aIssuerList = $oIssuerRequest->doRequest();
					$sIssuerList = '';

					if($oIssuerRequest->hasErrors())
					{
						if($this->aSettings['TEST_MODE'])
						{
							GatewayCore::output('<code>' . var_export($oIssuerRequest->getErrors(), true) . '</code>');
						}
						else
						{
							$this->oRecord['transaction_status'] = 'FAILURE';

							if(empty($this->oRecord['transaction_log']) == false)
							{
								$this->oRecord['transaction_log'] .= "\n\n";
							}

							$this->oRecord['transaction_log'] .= z_('Executing IssuerRequest on ') . date('Y-m-d, H:i:s') . '. Recieved: ERROR' . "\n" . var_export($oIssuerRequest->getErrors(), true);
							$this->save();

							$sHtml = '<p>Door een technische storing kunnen er momenteel helaas geen betalingen via iDEAL worden verwerkt. Onze excuses voor het ongemak.momenteel helaas geen betalingen via iDEAL woren verwerkt. Onze excusses voor het ongemak.</p>';

							if($this->oRecord['transaction_payment_url'])
							{
								$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_payment_url']) . '">'.z_('Choose another payment method').'</a></p>';
							}
							elseif($this->oRecord['transaction_failure_url'])
							{
								$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_failure_url']) . '">Continue</a></p>';
							}

							GatewayCore::output($sHtml);
						}
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
<form action="' . zurl('?page=pay_ideal&action=transaction&order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '" method="post" id="checkout">
	<p><b>'.z_('Choose your bank').'</b><br><select name="issuer_id" style="margin: 6px; width: 200px;">' . $sIssuerList . '</select><br><input type="submit" value="'.z_('Continue').'"></p>.
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
					$oTransactionRequest = new TransactionRequest();
					$oTransactionRequest->setSecurePath($this->aSettings['CERTIFICATE_PATH']);
					$oTransactionRequest->setCachePath($this->aSettings['TEMP_PATH']);
					$oTransactionRequest->setPrivateKey($this->aSettings['PRIVATE_KEY_PASS'], $this->aSettings['PRIVATE_KEY_FILE'], $this->aSettings['PRIVATE_CERTIFICATE_FILE']);
					$oTransactionRequest->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['SUB_ID']);
					$oTransactionRequest->setAquirer($this->aSettings['GATEWAY_NAME'], $this->aSettings['TEST_MODE']);

					$oTransactionRequest->setOrderId($this->oRecord['order_id']);
					$oTransactionRequest->setOrderDescription($this->oRecord['transaction_description']);
					$oTransactionRequest->setOrderAmount($this->oRecord['transaction_amount']);

					$oTransactionRequest->setIssuerId($sIssuerId);
					$oTransactionRequest->setEntranceCode($this->oRecord['transaction_code']);
					$oTransactionRequest->setReturnUrl(zurl(get_option('home').'/?page=pay_ideal&action=return'));


					// Find TransactionID
					$sTransactionId = $oTransactionRequest->doRequest();

					if($oTransactionRequest->hasErrors())
					{
						if($this->aSettings['TEST_MODE'])
						{
							GatewayCore::output('<code>' . var_export($oTransactionRequest->getErrors(), true) . '</code>');
						}
						else
						{
							$this->oRecord['transaction_status'] = 'FAILURE';

							if(empty($this->oRecord['transaction_log']) == false)
							{
								$this->oRecord['transaction_log'] .= "\n\n";
							}

							$this->oRecord['transaction_log'] .= z_('Executing TransactionRequest on ') . date('Y-m-d, H:i:s') . '. Recieved: ERROR' . "\n" . var_export($oTransactionRequest->getErrors(), true);
							$this->save();

							$sHtml = '<p>Door een technische storing kunnen er momenteel helaas geen betalingen via iDEAL worden verwerkt. Onze excuses voor het ongemak.</p>';

							if($this->oRecord['transaction_payment_url'])
							{
								$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_payment_url']) . '">'.z_('Choose another payment method').'</a></p>';
							}
							elseif($this->oRecord['transaction_failure_url'])
							{
								$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_failure_url']) . '">Continue</a></p>';
							}

							$sHtml .= '<!--

' . var_export($oTransactionRequest->getErrors(), true) . '

-->';

							GatewayCore::output($sHtml);
						}
					}

					$sTransactionUrl = $oTransactionRequest->getTransactionUrl();

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

					// die('<a href="' . $oTransactionRequest->getTransactionUrl() . '">' . $oTransactionRequest->getTransactionUrl() . '</a>');
					$oTransactionRequest->doTransaction();
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

		if(empty($_GET['trxid']) || empty($_GET['ec']))
		{
			$sHtml .= wsGatewayMessage('Invalid return request.');
		}
		else
		{
			$sTransactionId = $_GET['trxid'];
			$sTransactionCode = $_GET['ec'];

			// Lookup record
			if($this->getRecordByTransaction($sTransactionId, $sTransactionCode))
			{
				// Transaction already finished
				if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
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
					// Check status
					$oStatusRequest = new StatusRequest();
					$oStatusRequest->setSecurePath($this->aSettings['CERTIFICATE_PATH']);
					$oStatusRequest->setCachePath($this->aSettings['TEMP_PATH']);
					$oStatusRequest->setPrivateKey($this->aSettings['PRIVATE_KEY_PASS'], $this->aSettings['PRIVATE_KEY_FILE'], $this->aSettings['PRIVATE_CERTIFICATE_FILE']);
					$oStatusRequest->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['SUB_ID']);
					$oStatusRequest->setAquirer($this->aSettings['GATEWAY_NAME'], $this->aSettings['TEST_MODE']);

					$oStatusRequest->setTransactionId($sTransactionId);

					$this->oRecord['transaction_status'] = $oStatusRequest->doRequest();

					if($oStatusRequest->hasErrors())
					{
						if($this->aSettings['TEST_MODE'])
						{
							GatewayCore::output('<code>' . var_export($oStatusRequest->getErrors(), true) . '</code>');
						}
						else
						{
							$this->oRecord['transaction_status'] = 'FAILURE';

							if(empty($this->oRecord['transaction_log']) == false)
							{
								$this->oRecord['transaction_log'] .= "\n\n";
							}

							$this->oRecord['transaction_log'] .= z_('Executing StatusRequest on ') . date('Y-m-d, H:i:s') . '. Recieved: ERROR' . "\n" . var_export($oStatusRequest->getErrors(), true);
							$this->save();

							$sHtml = '<p>Door een technische storing kunnen er momenteel helaas geen betalingen via iDEAL worden verwerkt. Onze excuses voor het ongemak.momenteel helaas geen betalingen via iDEAL woren verwerkt. Onze excusses voor het ongemak.</p>';

							if($this->oRecord['transaction_payment_url'])
							{
								$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_payment_url']) . '">'.z_('Choose another payment method').'</a></p>';
							}
							elseif($this->oRecord['transaction_failure_url'])
							{
								$sHtml .= '<p><a href="' . htmlentities($this->oRecord['transaction_failure_url']) . '">Continue</a></p>';
							}

							$sHtml .= '<!--

' . var_export($oStatusRequest->getErrors(), true) . '

-->';

							GatewayCore::output($sHtml);
						}
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
						$sHtml .= '<p>'.z_('Your payment was processed successfully').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . htmlspecialchars(GatewayCore::getRootUrl(1)) . '\'"></p>';
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
		GatewayCore::output('Invalid report request.');
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
				$oStatusRequest = new StatusRequest();
				$oStatusRequest->setSecurePath($this->aSettings['CERTIFICATE_PATH']);
				$oStatusRequest->setCachePath($this->aSettings['TEMP_PATH']);
				$oStatusRequest->setPrivateKey($this->aSettings['PRIVATE_KEY_PASS'], $this->aSettings['PRIVATE_KEY_FILE'], $this->aSettings['PRIVATE_CERTIFICATE_FILE']);
				$oStatusRequest->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['SUB_ID']);
				$oStatusRequest->setAquirer($this->aSettings['GATEWAY_NAME'], $this->aSettings['TEST_MODE']);

				$oStatusRequest->setTransactionId($oRecord['transaction_id']);

				$oRecord['transaction_status'] = $oStatusRequest->doRequest();

				if(empty($oRecord['transaction_log']) == false)
				{
					$oRecord['transaction_log'] .= "\n\n";
				}

				if($oStatusRequest->hasErrors())
				{
					$oRecord['transaction_status'] = 'FAILURE';
					$oRecord['transaction_log'] .= z_('Executing StatusRequest on ') . date('Y-m-d, H:i:s') . '. Recieved: ERROR' . "\n" . var_export($oStatusRequest->getErrors(), true);
				}
				else
				{
					$oRecord['transaction_log'] .= z_('Executing StatusRequest on ') . date('Y-m-d, H:i:s') . ' '.z_('for').' #' . $oRecord['transaction_id'] . '. '.z_('Received').': ' . $oRecord['transaction_status'];
				}

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