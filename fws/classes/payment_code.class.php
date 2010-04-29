<?php
class paymentCode {
	var $payment_code;

	function getCode($paymentid,$customer,$total,$webid) {
		global $dbtablesprefix,$shopurl,$lang,$sales_mail,$currency,$autosubmit;

		$total_nodecimals = number_format($total, 2,"","");

		// read the payment method
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."payment` WHERE `id` = %s", quote_smart($paymentid));
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_array($sql)) {
			//IDEAL hash
			$validity="2016-01-01T12:00:00:0000Z";
			$shastring = $row['SECRET'] . $row['MERCHANTID'] . "0" . $total_nodecimals. $webid . "ideal" . $validity . "12345" . $webid . "1". $total_nodecimals;
			$clean_shaString = HTML_entity_decode($shastring);
			$not_allowed = array("\t", "\n", "\r", " "); 
			$clean_shaString = str_replace($not_allowed, "",$clean_shaString);
			$idealhash = sha1($clean_shaString);
				
			$payment_descr = $row[1];
			$payment_code = $row[2];
			// there could be some variables in the code, like %total%, %webid% and %shopurl% so lets update them with the correct values
			if ($autosubmit) $payment_code=str_replace('target="_new"','',$payment_code);
			$payment_code = str_replace('<form','<form id="autosubmit"',$payment_code);
			$payment_code = str_replace("%total_nodecimals%", $total_nodecimals, $payment_code);
			$payment_code = str_replace("%validity%", $validity, $payment_code);
			$payment_code = str_replace("%idealhash%", $idealhash, $payment_code);
			$payment_code = str_replace("%merchantid%", $row['MERCHANTID'], $payment_code);
			$payment_code = str_replace("%total%", $total, $payment_code);
			$payment_code = str_replace("%webid%", $webid, $payment_code);
			$payment_code = str_replace("%shopurl%", $shopurl, $payment_code);
			$payment_code = str_replace("%currency%", $currency, $payment_code);
			$payment_code = str_replace("%lang%", $lang, $payment_code);
			$payment_code = str_replace("%customer%", $customer['ID'], $payment_code);
			$payment_code = str_replace("%firstname%", $customer['INITIALS'], $payment_code);
			$payment_code = str_replace("%lastname%", $customer['LASTNAME'], $payment_code);
			$payment_code = str_replace("%address%", $customer['ADDRESS'], $payment_code);
			$payment_code = str_replace("%city%", $customer['CITY'], $payment_code);
			$payment_code = str_replace("%state%", $customer['STATE'], $payment_code);
			$payment_code = str_replace("%zip%", $customer['ZIP'], $payment_code);
			$payment_code = str_replace("%country%", $customer['COUNTRY'], $payment_code);
			$payment_code = str_replace("%email%", $customer['EMAIL'], $payment_code);
			$payment_code = str_replace("%phone%", $customer['PHONE'], $payment_code);
			$payment_code = str_replace("%ipn%", ZING_URL.'fws/ipn.php', $payment_code);
			$payment_code = str_replace("%paypal_email%", $sales_mail, $payment_code);
			$payment_code = str_replace("%return%", $shopurl . '/index.php?page=checkout&status=1&webid='.urlencode($webid), $payment_code);
			$payment_code = str_replace("%cancel%", $shopurl . '/index.php?page=checkout&status=9&webid='.urlencode($webid), $payment_code);
			$payment_code = trim($payment_code);
			$this->payment_code=$payment_code;
			return $payment_code;
		} else {
			return false;
		}
	}
	
	function codeExists($paymentid) {
		global $dbtablesprefix;

		if ($paymentid=="") $paymentid=$this->defaultPaymentId();
		
		// read the payment method
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."payment` WHERE `id` = %s", quote_smart($paymentid));
		$sql = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($sql) > 0) return true;
		else return false;
	}
	
	function defaultPaymentId() {
		global $dbtablesprefix,$send_default_country,$customerid;
		
		$query="SELECT * FROM `".$dbtablesprefix."shipping` ORDER BY `id`";
		$sql = mysql_query($query) or zfdbexit($query);
		while ($row = mysql_fetch_row($sql)) {
			// there must be at least 1 payment option available, so lets check that
			$pay_query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`=".$row[0];
			$pay_sql = mysql_query($pay_query) or zfdbexit($pay_query);
			if (mysql_num_rows($pay_sql) <> 0) {
				if ($row[2] == 0 || ($row[2] == 1 && IsCustomerFromDefaultSendCountry($send_default_country) == 1)) {
					// now check the weight and the costs
					if (!$shippingid) $shippingid=$row[0];
					$cart_weight = WeighCart($customerid);
					$weight_query = "SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE '".$cart_weight."' >= `FROM` AND '".$cart_weight."' <= `TO` AND `SHIPPINGID` = '".$row[0]."'";
					$weight_sql = mysql_query($weight_query) or zfdbexit($weight_query);
					while ($weight_row = mysql_fetch_row($weight_sql)) {
						if (!$weightid) $weightid=$weight_row[0];
					}
				}
			}
		}
		
		$query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$shippingid."' ORDER BY `paymentid`";
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_row($sql)) {
			$paymentid=$row[1];
		}
		return $paymentid;
	}
}