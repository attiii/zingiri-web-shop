<?php
class paymentCode {
	var $payment_code;

	function getCode($paymentid,$customer,$total,$webid) {
		global $dbtablesprefix,$shopname,$shopurl,$lang,$sales_mail,$currency,$autosubmit,$index_refer,$zing;

		$this->webid=$webid;
		$this->total=$total;
		$this->total_nodecimals = number_format($total, 2,"","");
		$this->autosubmit=$autosubmit;

		// read the payment method
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."payment` WHERE `id` = %s", quote_smart($paymentid));
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_array($sql)) {
			$payment_descr = $row['DESCRIPTION'];
			$payment_code = $row['CODE'];
			$gateway = $row['GATEWAY'];
			$this->merchantid=$row['MERCHANTID'];
			$this->subid=$row['SUBID'];
			$this->secret=$row['SECRET'];
			$this->testmode=$row['TESTMODE'];

			//common variables
			if (isset($_REQUEST['wslive'])) {
				$this->returnUrl=$_REQUEST['wsliveurl'] . '/index.php?page=checkout&status=1&webid='.urlencode($webid).'&gateway='.$gateway;
				$this->cancelUrl=$_REQUEST['wsliveurl'] . '/index.php?page=checkout&status=9&webid='.urlencode($webid).'&gateway='.$gateway;
			} else {
				$this->returnUrl=zurl('index.php?page=checkout&status=1&webid='.urlencode($webid).'&gateway='.$gateway);
				$this->cancelUrl=zurl('index.php?page=checkout&status=9&webid='.urlencode($webid).'&gateway='.$gateway);
			}
			if (!empty($gateway)) {
				foreach ($zing->paths as $path) {
					$f=dirname($path).'/extensions/gateways/'.$gateway.'/'.$gateway.'.php';
					if (file_exists($f)) {
						require($f);
						break;
					}
				}
			}
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
			$payment_code = str_replace("%currency%", wsPrice::ccy(), $payment_code);
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

class wsGateway {
	function wsGateway() {

	}
}

class wsIpn {
	var $gateway;
	var $merchantid;
	var $secret;
	var $testmode;
	var $webid;
	var $currency;
	var $paid;
	var $newStatus;

	function wsIpn($gateway) {
		ob_start(array($this,'output'));

		$this->db=new db();
		if ($this->db->select("select * from ##payment where gateway=".qs($gateway)) && $this->db->next()) {
			$this->merchantid=$this->db->get('merchantid');
			$this->secret=$this->db->get('secret');
			$this->testmode=$this->db->get('testmode');
			$this->gateway=$gateway;
			return true;
		}
		return false;
	}

	function verifyOrder($webid,$paid,$currency='') {
		$this->webid=$webid;
		$this->paid=$paid;
		$this->currency=$currency;

		$query=sprintf("select topay,paid,status,customerid from ##order where status in (1) and webid=%s",qs($this->webid));
		if ($this->db->select($query) && $this->db->next()) {
			$this->checkDownloadables($this->db->get('customerid'),$this->db->get('id'));
			$totPaid=$this->db->get('PAID') + $this->paid;
			if (($totPaid >= $this->db->get(TOPAY)) && ($this->allProducts==$this->digProducts)) $this->newStatus=5;
			elseif ($totPaid >= $this->db->get('TOPAY')) $this->newStatus=4;
			else $this->newStatus=$this->db->get('STATUS');
			return true;
		}
		return false;
	}

	function updateOrder() {
		$query=sprintf("update ##order set paid=paid+%s, status=%s where webid=%s",qs($this->paid),qs($this->newStatus),qs($this->webid));
		$this->db->update($query);
	}

	function updatePaymentInfo($firstname,$lastname,$buyer_email,$street,$city,$state,$zipcode,$paymentdate,$paymenttype,$txnid,$mc_gross,$mc_fee,$pendingreason,$txntype,$tax,$mc_currency,$reasoncode,$country,$datecreation,$invoice) {
		$this->db->select('desc ##payment_info');
		//echo '<br />=====<br />';
		while ($row=$this->db->next()) {
			//			echo '$data[\''.$row['Field'].'\']=$'.$row['Field'].';';
			//			echo '<br />';
			//echo '$newResponse->post[\''.$row['Field'].'\'],';
		}
		//echo '<br />=====<br />';
		$data=array();
		$data['firstname']=$firstname;
		$data['lastname']=$lastname;
		$data['buyer_email']=$buyer_email;
		$data['street']=$street;
		$data['city']=$city;
		$data['state']=$state;
		$data['zipcode']=$zipcode;
		/*
		$data['memo']=$memo;
		$data['itemname']=$itemname;
		$data['itemnumber']=$itemnumber;
		$data['os0']=$os0;
		$data['on0']=$on0;
		$data['os1']=$os1;
		$data['on1']=$on1;
		$data['quantity']=$quantity;
		*/
		$data['paymentdate']=$paymentdate;
		$data['paymenttype']=$paymenttype;
		$data['txnid']=$txnid;
		$data['mc_gross']=$mc_gross;
		$data['mc_fee']=$mc_fee;
		$data['paymentstatus']=$this->newStatus;
		$data['pendingreason']=$pendingreason;
		$data['txntype']=$txntype;
		$data['tax']=$tax;
		$data['mc_currency']=$mc_currency;
		$data['reasoncode']=$reasoncode;
		$data['custom']=$this->webid;
		$data['country']=$country;
		$data['datecreation']=$datecreation;
		$data['invoice']=$invoice;
		print_r($data);
		$this->db->insertRecord('payment_info','',$data);
	}

	function output($buffer) {
		user_error_handler('0',$buffer,__FILE__);
		return $buffer;
	}

	function checkDownloadables($customerId,$orderId) {
		global $dbtablesprefix;
		//check if order contains only downloadable items
		$this->digProducts=$this->allProducts=0;
		$query_basket=sprintf("select PRODUCTID from ".$dbtablesprefix."basket where CUSTOMERID=%s and ORDERID=%s",$customerId,$orderId);
		if ($sql_basket=mysql_query($query_basket)) {
			while ($row_basket = mysql_fetch_array($sql_basket)) {
				$query_product=sprintf("select LINK from ".$dbtablesprefix."product where ID=%s",$row_basket['PRODUCTID']);
				if ($sql_product=mysql_query($query_product)) {
					if ($row_product = mysql_fetch_array($sql_product)) {
						if (!empty($row_product['LINK'])) $this->digProducts++;
						$this->allProducts++;
					}
				}
			}
		}


	}
}