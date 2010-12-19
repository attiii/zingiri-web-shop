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
				$sHtml .= '<p>Invalid setup request.</p>';
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
					else
					{
						$oIdealLite = new IdealLite();
						$oIdealLite->setHashKey($this->aSettings['HASH_KEY']);
						$oIdealLite->setMerchant($this->aSettings['MERCHANT_ID'], $this->aSettings['SUB_ID']);
						$oIdealLite->setAquirer($this->aSettings['GATEWAY_NAME'], $this->aSettings['TEST_MODE']);
						
						// Set return URLs
						$oIdealLite->setUrlCancel(GatewayCore::getRootUrl() . '?page=pay_ideal&action=return&trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code'] . '&status=CANCELLED');
						$oIdealLite->setUrlError(GatewayCore::getRootUrl() . '?page=pay_ideal&action=return&trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code'] . '&status=FAILURE');
						$oIdealLite->setUrlSuccess(GatewayCore::getRootUrl() . '?page=pay_ideal&action=return&trxid=' . $this->oRecord['transaction_id'] . '&ec=' . $this->oRecord['transaction_code'] . '&status=SUCCESS');

						// Set order details
						$oIdealLite->setOrderId($this->oRecord['order_id']); // Unieke order referentie (tot 16 karakters)
						$oIdealLite->setOrderDescription($this->oRecord['transaction_description']); // Order omschrijving (tot 32 karakters)
						$oIdealLite->setAmount($this->oRecord['transaction_amount']); // Bedrag (in EURO's)

						// Customize submit button
						$oIdealLite->setButton('Verder >>');

						$sHtml = '<p><b>Direct online afrekenen via uw eigen bank.</b></p>' . $oIdealLite->createForm() . '</div>';

						// Add auto-submit button
						if($this->aSettings['TEST_MODE'] == false)
						{
							$sHtml .= '<script type="text/javascript"> function doAutoSubmit() { document.forms[0].submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid setup request.</p>';
				}
			}

			GatewayCore::output($sHtml);
		}


		// Catch return
		public function doReturn()
		{
			$sHtml = '';

			if(empty($_GET['trxid']) || empty($_GET['ec']) || empty($_GET['status']))
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
						$this->oRecord['transaction_status'] = ((empty($_GET['status']) || (in_array($_GET['status'], array('SUCCESS', 'CANCELLED', 'FAILURE')) == false)) ? 'FAILURE' : $_GET['status']);

						if(empty($this->oRecord['transaction_log']) == false)
						{
							$this->oRecord['transaction_log'] .= "\n\n";
						}

						$this->oRecord['transaction_log'] .= 'Recieved status ' . $this->oRecord['transaction_status'] . ' '.z_('for').' #' . $sTransactionId . ' on ' . date('Y-m-d, H:i:s') . '.';


						if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
						{
							$sHtml .= '<p>'.z_('Your payment was processed successfully').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . htmlspecialchars($this->oRecord['transaction_success_url']) . '\'"></p>';
						}
						elseif((strcmp($this->oRecord['transaction_status'], 'OPEN') === 0) && !empty($this->oRecord['transaction_url']))
						{
							$sHtml .= '<p>'.z_('Your payment is not complete yet').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . htmlspecialchars($this->oRecord['transaction_url']) . '\'"></p>';
						}
						else
						{
							if(strcmp($this->oRecord['transaction_status'], 'CANCELLED') === 0)
							{
								$sHtml .= '<p>'.z_('Your payment was cancelled. Please try again.').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . zurl(GatewayCore::getRootUrl() . '?page=pay_ideal&action=setup&order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
							}
							elseif(strcmp($this->oRecord['transaction_status'], 'EXPIRED') === 0)
							{
								$sHtml .= '<p>'.z_('Your payment failed. Please try again.').'<br><input style="margin: 6px;" type="button" value="'.z_('Continue').'" onclick="javascript: document.location.href = \'' . zurl(GatewayCore::getRootUrl() . '?page=pay_ideal&action=setup&order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
							}
							else // if(strcmp($this->oRecord['transaction_status'], 'FAILURE') === 0)
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


						// Update transaction
						$sql = "UPDATE `" . DATABASE_PREFIX . "transactions` SET `transaction_date` = '" . time() . "', `transaction_status` = '" . addslashes($this->oRecord['transaction_status']) . "', `transaction_log` = '" . addslashes($this->oRecord['transaction_log']) . "' WHERE (`id` = '" . addslashes($this->oRecord['id']) . "') LIMIT 1;";
						mysql_query($sql) or die('QUERY: ' . $sql . ', ERROR: ' . mysql_error());



						if($this->oRecord['transaction_success_url'] && strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
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
	}

?>