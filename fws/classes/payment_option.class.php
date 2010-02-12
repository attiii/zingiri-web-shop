<?php
class wsPaymentOption {
	var $code;
	var $description;

	function wsPaymentOption($paymentid) {
		require(dirname(__FILE__).'/../globals.php');

		$query = sprintf("SELECT * FROM `".$dbtablesprefix."payment` WHERE `id` = %s", quote_smart($paymentid));
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_row($sql)) {
			$this->description = $row[1];
			$this->code = $row[2];
		}
	}
	function getCode($customer_row) {
		require(dirname(__FILE__).'/../globals.php');
		
		// there could be some variables in the code, like %total%, %webid% and %shopurl% so lets update them with the correct values
		$payment_code = $this->code;
		$payment_code = str_replace("%total_nodecimals%", $total_nodecimals, $payment_code);
		$payment_code = str_replace("%total%", $total, $payment_code);
		$payment_code = str_replace("%webid%", $webid, $payment_code);
		$payment_code = str_replace("%shopurl%", $shopurl, $payment_code);
		$payment_code = str_replace("%currency%", $currency, $payment_code);
		$payment_code = str_replace("%lang%", $lang, $payment_code);
		$payment_code = str_replace("%customer%", $customer_row['ID'], $payment_code);
		$payment_code = str_replace("%firstname%", $customer_row['INITIALS'], $payment_code);
		$payment_code = str_replace("%lastname%", $customer_row['LASTNAME'], $payment_code);
		$payment_code = str_replace("%address%", $customer_row['ADDRESS'], $payment_code);
		$payment_code = str_replace("%city%", $customer_row['CITY'], $payment_code);
		$payment_code = str_replace("%state%", $customer_row['STATE'], $payment_code);
		$payment_code = str_replace("%zip%", $customer_row['ZIP'], $payment_code);
		$payment_code = str_replace("%country%", $customer_row['COUNTRY'], $payment_code);
		$payment_code = str_replace("%email%", $customer_row['EMAIL'], $payment_code);
		$payment_code = str_replace("%phone%", $customer_row['PHONE'], $payment_code);
		$payment_code = str_replace("%ipn%", ZING_URL.'fws/ipn.php', $payment_code);
		$payment_code = str_replace("%paypal_email%", $sales_mail, $payment_code);
		$payment_code = str_replace("%return%", $shopurl . '/index.php?page=checkout&status=1', $payment_code);
		$payment_code = str_replace("%cancel%", $shopurl . '/index.php?page=checkout&status=9', $payment_code);
		return $payment_code;

	}

}
?>