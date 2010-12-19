<?php

// $_POST = unserialize('a:3:{s:5:"total";s:7:"1224.56";s:5:"webid";s:11:"WEB20101-08";s:7:"shopurl";s:54:"http://www.pholeron.nl/projects/freewebshop 2.2.9/shop";}');


// Load gateway library & settings
require_once(dirname(__FILE__) . '/include.php');

// Validate $_POST data
if(isset($_POST['webid']) && isset($_POST['total']) && preg_match('/([a-zA-Z0-9]+)[\-]([0-9]+)/', $_POST['webid']) && preg_match('/([0-9]+)(\.[0-9]{0,2})?/', $_POST['total']))
{
	$sWebId = $_POST['webid'];
	$sTotal = number_format($_POST['total'], 2, '.', '');

	// See if order exists
	$sql = "SELECT * FROM `" . DATABASE_PREFIX . "order` WHERE (`WEBID` = '" . addslashes($sWebId) . "') AND (`TOPAY` = '" . addslashes($sTotal) . "') ORDER BY `ID` DESC LIMIT 1;";
	$oRecordSet = mysql_query($sql) or die('SQL: ' . $sql . '<br><br>Error: ' . mysql_error());

	if($oRecordSet && ($oRecord = mysql_fetch_assoc($oRecordSet)))
	{
		// See if transaction record exists
		$sql = "SELECT `order_id`, `order_code`, `transaction_status` FROM `" . DATABASE_PREFIX . "transactions` WHERE (`order_id` = '" . addslashes($sWebId) . "') ORDER BY `id` DESC LIMIT 1;";
		$oRecordSet = mysql_query($sql) or die('SQL: ' . $sql . '<br><br>Error: ' . mysql_error());

		if($oRecordSet && ($oRecord2 = mysql_fetch_assoc($oRecordSet)))
		{
			if(strcasecmp($oRecord2['transaction_status'], 'SUCCESS') === 0)
			{
				GatewayCore::output('<p>Uw betaling is met succes ontvangen.</p>');
			}
			elseif(strcasecmp($oRecord2['transaction_status'], 'PENDING') === 0)
			{
				GatewayCore::output('<p>Uw betaling is met succes ontvangen.</p>');
			}

			// Transaction not finished, restart payment
			header('Location: ' . zurl(GatewayCore::getRootUrl() . '?page=pay_ideal&action=setup&order_id=' . $oRecord2['order_id'] . '&order_code=' . $oRecord2['order_code']));
			exit;
		}
		else
		{
			// Setup transaction record
			$sOrderId = $oRecord['WEBID'];
			$sOrderCode = GatewayCore::randomCode(32);
			$sTransactionId = GatewayCore::randomCode(32);
			$sTransactionCode = GatewayCore::randomCode(32);
			$sTransactionMethod = $aGatewaySettings['GATEWAY_METHOD'];
			$fTransactionAmount = $oRecord['TOPAY'];
			$sTransactionDescription = 'Webshop bestelling #' . $sOrderId;

			// Insert into #_transactions
			$sql = "INSERT INTO `" . DATABASE_PREFIX . "transactions` SET
`id` = NULL, 
`order_id` = '" . addslashes($sOrderId) . "', 
`order_code` = '" . addslashes($sOrderCode) . "', 
`transaction_id` = '" . addslashes($sTransactionId) . "', 
`transaction_code` = '" . addslashes($sTransactionCode) . "', 
`transaction_method` = '" . addslashes($sTransactionMethod) . "', 
`transaction_date` = '" . addslashes(time()) . "', 
`transaction_amount` = '" . addslashes($fTransactionAmount) . "', 
`transaction_description` = '" . addslashes($sTransactionDescription) . "', 
`transaction_status` = NULL, 
`transaction_url` = NULL, 
`transaction_payment_url` = NULL, 
`transaction_success_url` = NULL, 
`transaction_pending_url` = NULL, 
`transaction_failure_url` = NULL, 
`transaction_params` = NULL, 
`transaction_log` = NULL;";

			mysql_query($sql) or die('SQL: ' . $sql . '<br><br>Error: ' . mysql_error());

			// Start payment
			header('Location: ' . zurl(GatewayCore::getRootUrl() . '?page=pay_ideal&action=setup&order_id=' . $sOrderId . '&order_code=' . $sOrderCode));
			exit;
		}
	}
}

?>