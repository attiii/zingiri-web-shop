<?php
class paymentCode {
	var $payment_code;

	function getCode($paymentid,$customer,$total,$webid) {
		global $dbtablesprefix,$shopname,$shopurl,$lang,$sales_mail,$currency,$autosubmit,$index_refer;

		$this->webid=$webid;
		$this->total=$total;
		$this->total_nodecimals = number_format($total, 2,"","");

		// read the payment method
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."payment` WHERE `id` = %s", quote_smart($paymentid));
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_array($sql)) {
			$payment_descr = $row['DESCRIPTION'];
			$payment_code = $row['CODE'];
			$gateway = $row['GATEWAY'];
			$this->merchantid=$row['MERCHANTID'];
			$this->secret=$row['SECRET'];
			
			//common variables
			if (isset($_REQUEST['wslive'])) {
				$this->returnUrl=$_REQUEST['wsliveurl'] . '/index.php?page=checkout&status=1&webid='.urlencode($webid).'&gateway='.$gateway;
				$this->cancelUrl=$_REQUEST['wsliveurl'] . '/index.php?page=checkout&status=9&webid='.urlencode($webid).'&gateway='.$gateway;
			} else {
				$this->returnUrl=$shopurl . '/index.php?page=checkout&status=1&webid='.urlencode($webid).'&gateway='.$gateway;
				$this->cancelUrl=$shopurl . '/index.php?page=checkout&status=9&webid='.urlencode($webid).'&gateway='.$gateway;
			}
			if (!empty($gateway) && file_exists(ZING_LOC.'extensions/gateways/'.$gateway.'/'.$gateway.'.php')) @include(ZING_LOC.'extensions/gateways/'.$gateway.'/'.$gateway.'.php');
			elseif (!empty($gateway) && file_exists(ZING_WS_PRO_DIR.'../extensions/gateways/'.$gateway.'/'.$gateway.'.php')) @include(ZING_WS_PRO_DIR.'../extensions/gateways/'.$gateway.'/'.$gateway.'.php');
			if (class_exists($gateway.'Gateway')) {
				$gc=$gateway.'Gateway';
				$g=new $gc($this,$customer);
				//calculate hash if needed
				$hash=$g->calcHash();
				$payment_code = str_replace("%hash%", $hash, $payment_code);
				$payment_code = str_replace("%idealhash%", $hash, $payment_code); //for backward compatibility
				// custom replacements
				$payment_code = $g->replace($payment_code);
			}
			
			// common replacements
			if ($autosubmit) $payment_code=str_replace('target="_new"','',$payment_code);
			$payment_code = str_replace('<form','<form id="autosubmit"',$payment_code);
			$payment_code = str_replace('<FORM','<FORM id="autosubmit"',$payment_code);
			$payment_code = str_replace("%total_nodecimals%", $this->total_nodecimals, $payment_code);
			$payment_code = str_replace("%merchantid%", $this->merchantid, $payment_code);
			$payment_code = str_replace("%shopname%", $shopname, $payment_code);
			$payment_code = str_replace("%total%", $total, $payment_code);
			$payment_code = str_replace("%webid%", $webid, $payment_code);
			$payment_code = str_replace("%shopurl%", $shopurl, $payment_code);
			$payment_code = str_replace("%currency%", $currency, $payment_code);
			$payment_code = str_replace("%lang%", $lang, $payment_code);
			$payment_code = str_replace("%customer%", $customer['ID'], $payment_code);
			$payment_code = str_replace("%name%", $customer['INITIALS'].' '.$customer['LASTNAME'], $payment_code);
			if (isset($customer['COMPANY'])) $payment_code = str_replace("%company%", $customer['COMPANY'], $payment_code);
			$payment_code = str_replace("%firstname%", $customer['INITIALS'], $payment_code);
			$payment_code = str_replace("%lastname%", $customer['LASTNAME'], $payment_code);
			$payment_code = str_replace("%address%", $customer['ADDRESS'], $payment_code);
			$payment_code = str_replace("%city%", $customer['CITY'], $payment_code);
			$payment_code = str_replace("%state%", $customer['STATE'], $payment_code);
			$payment_code = str_replace("%zip%", $customer['ZIP'], $payment_code);
			$payment_code = str_replace("%country%", $customer['COUNTRY'], $payment_code);
			$payment_code = str_replace("%email%", $customer['EMAIL'], $payment_code);
			$payment_code = str_replace("%phone%", $customer['PHONE'], $payment_code);
			$payment_code = str_replace("%return%", $this->returnUrl, $payment_code);
			$payment_code = str_replace("%cancel%", $this->cancelUrl, $payment_code);
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