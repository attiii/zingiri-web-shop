<?php


// Set default timezone (required in PHP 5+)
if(function_exists('date_default_timezone_set'))
{
	date_default_timezone_set('Europe/Amsterdam');
}

// Load user configuration
//require_once(dirname(__FILE__) . '/config.php');
//$aGatewaySettings = gateway_getSettings();

if (isset($_REQUEST['webid'])) $sWebId = $_REQUEST['webid'];
elseif (isset($_REQUEST['order_id'])) $sWebId = $_REQUEST['order_id'];
elseif (isset($_REQUEST['trxid'])) {
	$sTransactionId = $_GET['trxid'];
	$sTransactionCode = $_GET['ec'];
	$db=new db();
	if ($db->select("select order_id from ##transactions where transaction_id=".qs($sTransactionId)." AND transaction_code=".qs($sTransactionCode)) && $db->next()) {
		$sWebId = $db->get('order_id');
	}
}
else die('Unable to identify this order');

$db=new db();
if ($db->select("select PAYMENT from ##order where WEBID=".qs($sWebId)) && $db->next()) {
	$wsPaymentId=$db->get('PAYMENT');
	if ($db->select("select * from ##payment where ID=".qs($wsPaymentId))) {
		$wsPayment=$db->next();
		$wsGateway=explode('-',$db->get('gateway'));
	}
}
if (!$wsPayment) die('Error in determining payment mode');
require_once(dirname(__FILE__) . '/config/'.$wsGateway[1].'.php');
global $aGatewaySettings;
$aGatewaySettings = $aSettings;

// Load gateway class
if(file_exists($aGatewaySettings['GATEWAY_FILE']) == false)
{
	die('ERROR: Cannot load gateway file "' . $aGatewaySettings['GATEWAY_FILE'] . '".<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);
}
else
{
	require_once($aGatewaySettings['GATEWAY_FILE']);
}

// Define database settings
define('DATABASE_PREFIX', $dbtablesprefix, true);

// Order update
function gateway_update_order_status($oRecord, $sView)
{
	global $dbtablesprefix;

	if(strcasecmp($oRecord['transaction_status'], 'SUCCESS') === 0) {
		$mc_gross=$oRecord['transaction_amount'];
		$webid=$oRecord['order_id'];
		//update order payment status
		$query="select * from " . $dbtablesprefix . "order where WEBID=" . quote_smart($webid);
		$sql=mysql_query($query) or die(user_error_handler("1","Default - paypal_payment_info, Can not find order:<br>" . mysql_error() . "<br>". mysql_errno(),"ipn.php", 0));
		if ($row = mysql_fetch_array($sql)) {
			//check if order contains only downloadable items
			$digProducts=$allProducts=0;
			$query_basket=sprintf("select PRODUCTID from ".$dbtablesprefix."basket where CUSTOMERID=%s and ORDERID=%s",$row['CUSTOMERID'],$row['ID']);
			user_error_handler(1,$query_basket);
			$sql_basket=mysql_query($query_basket) or die(user_error_handler("1","Error reading basket:<br>" . mysql_error() . "<br>" . mysql_errno(),"ipn.php", 0));
			while ($row_basket = mysql_fetch_array($sql_basket)) {
				$query_product=sprintf("select LINK from ".$dbtablesprefix."product where ID=%s",$row_basket['PRODUCTID']);
				user_error_handler(1,$query_product);
				$sql_product=mysql_query($query_product) or die(user_error_handler("1","Error reading product:<br>" . mysql_error() . "<br>" . mysql_errno(),"ipn.php", 0));
				if ($row_product = mysql_fetch_array($sql_product)) {
					if (!empty($row_product['LINK'])) $digProducts++;
					$allProducts++;
				}
			}
			user_error_handler(1,'Products:'.$digProducts.'/'.$allProducts);
			$paid=$row['PAID'] + $mc_gross;
			if (($paid >= $row['TOPAY']) && ($allProducts==$digProducts)) $status=5;
			elseif ($paid >= $row['TOPAY']) $status=4;
			else $status=$row['STATUS'];
			$query="update " . $dbtablesprefix . "order SET STATUS=".$status.", PAID=".$paid ." WHERE WEBID =" . quote_smart(trim($webid));
			user_error_handler(1,$query);
			user_error_handler("0", "custom=" . $custom . "\n","ipn.php",0);
			user_error_handler("0", "mc_gross=" . $mc_gross . "\n","ipn.php",0);
			$result=mysql_query($query) or die(user_error_handler("1",
							"Default - paypal_payment_info, Customer update failed:<br>" . mysql_error() . "<br>"
							. mysql_errno(),
							"ipn.php", 0));
		}

		//update basket status
		$query=sprintf("update ".$dbtablesprefix."basket set STATUS=1 where CUSTOMERID=%s and ORDERID=%s",$row['CUSTOMERID'],$row['ID']);
		$sql=mysql_query($query) or die(user_error_handler("1","Error updating basket:<br>" . mysql_error() . "<br>" . mysql_errno(),"ipn.php", 0));
	}

}

//added for Zingiri
function wsGatewayMessage($msg) {
	return '<p>'.z_($msg).'</p>';
}

function gateway_getSettings() {
	global $aGatewaySettings;
	// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
	$aGatewaySettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

	// Path to TEMP folder (This folder should not be accessable for webusers)
	$aGatewaySettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';
	
	return $aGatewaySettings;
}
?>