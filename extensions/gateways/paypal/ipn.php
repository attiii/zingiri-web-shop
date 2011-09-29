<?php
/*  zingiri_webshop.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Web Shop.

 Zingiri Web Shop is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Web Shop is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php

if (!defined('ZING_CMS')) {
	include(dirname(__FILE__)."/../../../fws/includes/settings.inc.php");     // database settings
	include(dirname(__FILE__)."/../../../fws/includes/connectdb.inc.php");    // connect to db
	include(dirname(__FILE__)."/../../../fws/includes/subs.inc.php");         // general functions
	include(dirname(__FILE__)."/../../../fws/includes/readsettings.inc.php"); // read shop settigns
}

user_error_handler("0", "Begin script\n","ipn.php",0);
/////////////////////////////////////////////////
/////////////Begin Script below./////////////////
/////////////////////////////////////////////////

// read the post from PayPal system and add 'cmd'
$req='cmd=_notify-validate';

foreach ($_POST as $key => $value)
{
	$value=urlencode(stripslashes($value));
	$req.="&$key=$value";
}
user_error_handler("0", "post back to PayPal\n","ipn.php",0);

// post back to PayPal system to validate
$header="POST /cgi-bin/webscr HTTP/1.0\r\n";
$header.="Content-Type: application/x-www-form-urlencoded\r\n";
$header.="Content-Length: " . strlen($req) . "\r\n\r\n";

user_error_handler("0", "before open socket\n","ipn.php",0);
$fp=fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);

user_error_handler("0", "retrieving values\n","ipn.php",0);

// assign posted variables to local variables
$item_name=$_POST['item_name'];
$business=$_POST['business'];
$item_number=$_POST['item_number'];
$payment_status=$_POST['payment_status'];
$mc_gross=$_POST['mc_gross'];
$payment_currency=$_POST['mc_currency'];
$txn_id=$_POST['txn_id'];
$receiver_email=$_POST['receiver_email'];
$receiver_id=$_POST['receiver_id'];
$quantity=$_POST['quantity'];
$num_cart_items=$_POST['num_cart_items'];
$payment_date=$_POST['payment_date'];
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$payment_type=$_POST['payment_type'];
$payment_status=$_POST['payment_status'];
$payment_gross=$_POST['payment_gross'];
$payment_fee=$_POST['payment_fee'];
$settle_amount=$_POST['settle_amount'];
$memo=$_POST['memo'];
$payer_email=$_POST['payer_email'];
$txn_type=$_POST['txn_type'];
$payer_status=$_POST['payer_status'];
$address_street=$_POST['address_street'];
$address_city=$_POST['address_city'];
$address_state=$_POST['address_state'];
$address_zip=$_POST['address_zip'];
$address_country=$_POST['address_country'];
$address_status=$_POST['address_status'];
$item_number=$_POST['item_number'];
$tax=$_POST['tax'];
$option_name1=$_POST['option_name1'];
$option_selection1=$_POST['option_selection1'];
$option_name2=$_POST['option_name2'];
$option_selection2=$_POST['option_selection2'];
$for_auction=$_POST['for_auction'];
$invoice=$_POST['invoice'];
$custom=$_POST['custom'];
$notify_version=$_POST['notify_version'];
$verify_sign=$_POST['verify_sign'];
$payer_business_name=$_POST['payer_business_name'];
$payer_id=$_POST['payer_id'];
$mc_currency=$_POST['mc_currency'];
$mc_fee=$_POST['mc_fee'];
$exchange_rate=$_POST['exchange_rate'];
$settle_currency=$_POST['settle_currency'];
$parent_txn_id=$_POST['parent_txn_id'];
$pending_reason=$_POST['pending_reason'];
$reason_code=$_POST['reason_code'];
user_error_handler("0", "invoice=" . $invoice . "\n","ipn.php",0);
user_error_handler("0", "custom=" . $custom . "\n","ipn.php",0);

// subscription specific vars

$subscr_id=$_POST['subscr_id'];
$subscr_date=$_POST['subscr_date'];
$subscr_effective=$_POST['subscr_effective'];
$period1=$_POST['period1'];
$period2=$_POST['period2'];
$period3=$_POST['period3'];
$amount1=$_POST['amount1'];
$amount2=$_POST['amount2'];
$amount3=$_POST['amount3'];
$mc_amount1=$_POST['mc_amount1'];
$mc_amount2=$_POST['mc_amount2'];
$mc_amount3=$_POST['mcamount3'];
$recurring=$_POST['recurring'];
$reattempt=$_POST['reattempt'];
$retry_at=$_POST['retry_at'];
$recur_times=$_POST['recur_times'];
$username=$_POST['username'];
$password=$_POST['password'];

//auction specific vars

$for_auction=$_POST['for_auction'];
$auction_closing_date=$_POST['auction_closing_date'];
$auction_multi_item=$_POST['auction_multi_item'];
$auction_buyer_id=$_POST['auction_buyer_id'];

//DB connect creds and email
$notify_email=$sales_mail; //email address to which debug emails are sent to

user_error_handler("0", "TXN ID=" . $txn_id . "\n","ipn.php",0);
user_error_handler("0", "TXN TYPE=" . $txn_type . "\n","ipn.php",0);


if (!$fp)
{
	// HTTP ERROR
	user_error_handler("1", "http error:".$errno . "-" . $errstr . "\n","ipn.php",0);
}
else
{

	user_error_handler("0", "OK\n","ipn.php",0);
	fputs($fp, $header . $req);

	while (!feof($fp))
	{

		$res=fgets($fp, 1024);

		if (strcmp($res, "VERIFIED") == 0)
		{

			user_error_handler("0", "step B\n","ipn.php",0);

			$fecha=date("m") . "/" . date("d") . "/" . date("Y");
			$fecha=date("Y") . date("m") . date("d");

			//check if transaction ID has been processed before
			$checkquery="select txnid from " . $dbtablesprefix . "payment_info where txnid='" . $txn_id . "'";
			$sihay=mysql_query($checkquery) or die(user_error_handler("1", "Duplicate txn id check query failed:<br>" . mysql_error() . "<br>"
			. mysql_errno(),"ipn.php",0));
			$nm=mysql_num_rows($sihay);

			if ($nm == 0)
			{

				//execute query
				user_error_handler("0", "step C\n","ipn.php",0);

				if ($txn_type == "cart")
				{
					user_error_handler("0", "step C1\n","ipn.php",0);
					$strQuery=
						"insert into " . $dbtablesprefix
					. "payment_info(paymentstatus,buyer_email,firstname,lastname,street,city,state,zipcode,country,mc_gross,mc_fee,memo,paymenttype,paymentdate,txnid,pendingreason,reasoncode,tax,datecreation) values ('"
					. $payment_status . "','" . $payer_email . "','" . $first_name . "','" . $last_name . "','"
					. $address_street . "','" . $address_city . "','" . $address_state . "','" . $address_zip
					. "','" . $address_country . "','" . $mc_gross . "','" . $mc_fee . "','" . $memo . "','"
					. $payment_type . "','" . $payment_date . "','" . $txn_id . "','" . $pending_reason . "','"
					. $reason_code . "','" . $tax . "','" . $fecha . "')";

					$result=
					mysql_query($strQuery) or die(user_error_handler("1", "Cart - payment_info, Query failed:<br>" . mysql_error()
					. "<br>" . mysql_errno(),"ipn.php",0));

					for ($i=1; $i <= $num_cart_items; $i++)
					{
						$itemname="item_name" . $i;
						$itemnumber="item_number" . $i;
						$on0="option_name1_" . $i;
						$os0="option_selection1_" . $i;
						$on1="option_name2_" . $i;
						$os1="option_selection2_" . $i;
						$quantity="quantity" . $i;

						$struery=
							"insert into " . $dbtablesprefix
						. "paypal_cart_info(txnid,itemnumber,itemname,os0,on0,os1,on1,quantity,invoice,custom) values ('"
						. $txn_id . "','" . $_POST[$itemnumber] . "','" . $_POST[$itemname] . "','" . $_POST[$on0]
						. "','" . $_POST[$os0] . "','" . $_POST[$on1] . "','" . $_POST[$os1] . "','"
						. $_POST[$quantity] . "','" . $invoice . "','" . substr($custom,1,20) . "')";
						$result=
						mysql_query($struery) or die(user_error_handler("1", "Cart - paypal_cart_info, Query failed:<br>" . mysql_error()
						. "<br>" . mysql_errno(),"ipn.php",0));
					}
				}
				else
				{
					user_error_handler("0", "step C2\n","ipn.php",0);
					$strQuery=
						"insert into " . $dbtablesprefix
					. "payment_info(paymentstatus,buyer_email,firstname,lastname,street,city,state,zipcode,country,mc_gross,mc_fee,itemnumber,itemname,os0,on0,os1,on1,quantity,memo,paymenttype,paymentdate,txnid,pendingreason,reasoncode,tax,datecreation,custom,invoice) values ('"
					. $payment_status . "','" . $payer_email . "','" . $first_name . "','" . $last_name . "','"
					. $address_street . "','" . $address_city . "','" . $address_state . "','" . $address_zip
					. "','" . $address_country . "','" . $mc_gross . "','" . $mc_fee . "','" . $item_number . "','"
					. $item_name . "','" . $option_name1 . "','" . $option_selection1 . "','"
					. $option_name2 . "','" . $option_selection2 . "','" . $quantity . "','" . $memo . "','"
					. $payment_type . "','" . $payment_date . "','" . $txn_id . "','" . $pending_reason . "','"
					. $reason_code . "','" . $tax . "','" . $fecha . "','" . $custom . "','" . $invoice . "')";
					$result=
					mysql_query($strQuery) or die(user_error_handler("1",
							"Default - payment_info, Query failed:<br>" . mysql_error() . "<br>" . mysql_errno(),
							"ipn.php", 0));
				}

				user_error_handler("0", "step C-OK\n","ipn.php",0);


				//update order payment status
				$query="select * from " . $dbtablesprefix . "order where WEBID=" . quote_smart(trim($item_name));
				$sql=mysql_query($query) or die(user_error_handler("1",
						"Default - payment_info, Can not find order:<br>" . mysql_error() . "<br>"
						. mysql_errno(),
						"ipn.php", 0));
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
							$query="update " . $dbtablesprefix . "order SET STATUS=".$status.", PAID=".$paid ." WHERE WEBID =" . quote_smart(trim($item_name));
							user_error_handler("0", "custom=" . $custom . "\n","ipn.php",0);
							user_error_handler("0", "mc_gross=" . $mc_gross . "\n","ipn.php",0);
							$result=mysql_query($query) or die(user_error_handler("1",
							"Default - payment_info, Customer update failed:<br>" . mysql_error() . "<br>"
							. mysql_errno(),
							"ipn.php", 0));
						}

						//update basket status
						$query=sprintf("update ".$dbtablesprefix."basket set STATUS=1 where CUSTOMERID=%s and ORDERID=%s",$row['CUSTOMERID'],$row['ID']);
						$sql=mysql_query($query) or die(user_error_handler("1","Error updating basket:<br>" . mysql_error() . "<br>" . mysql_errno(),"ipn.php", 0));

						// send an email in any case
						//				echo "Verified";
						//				mail($notify_email, "VERIFIED IPN", "$res\n $req\n $strQuery\n $struery\n  $strQuery2");
						user_error_handler("0", "VERIFIED IPN\n","ipn.php",0);
			}
			else {
				// send an email
				//			mail($notify_email, "VERIFIED DUPLICATED TRANSACTION", "$res\n $req \n $strQuery\n $struery\n  $strQuery2");
				user_error_handler("1", "VERIFIED DUPLICATED TRANSACTION\n","ipn.php",0);
			}


			//subscription handling branch
			if ($txn_type == "subscr_signup" || $txn_type == "subscr_payment")
			{

				// insert subscriber payment info into payment_info table
				$strQuery=
					"insert into " . $dbtablesprefix
				. "payment_info(paymentstatus,buyer_email,firstname,lastname,street,city,state,zipcode,country,mc_gross,mc_fee,memo,paymenttype,paymentdate,txnid,pendingreason,reasoncode,tax,datecreation) values ('"
				. $payment_status . "','" . $payer_email . "','" . $first_name . "','" . $last_name . "','"
				. $address_street . "','" . $address_city . "','" . $address_state . "','" . $address_zip . "','"
				. $address_country . "','" . $mc_gross . "','" . $mc_fee . "','" . $memo . "','" . $payment_type
				. "','" . $payment_date . "','" . $txn_id . "','" . $pending_reason . "','" . $reason_code . "','"
				. $tax . "','" . $fecha . "')";
				$result=mysql_query($strQuery) or die(user_error_handler("1", "Subscription - payment_info, Query failed:<br>"
				. mysql_error() . "<br>" . mysql_errno(),"ipn.php",0));


				// insert subscriber info into paypal_subscription_info table
				$strQuery2=
					"insert into " . $dbtablesprefix
				. "paypal_subscription_info(subscr_id , sub_event, subscr_date ,subscr_effective,period1,period2, period3, amount1 ,amount2 ,amount3,  mc_amount1,  mc_amount2,  mc_amount3, recurring, reattempt,retry_at, recur_times, username ,password, payment_txn_id, subscriber_emailaddress, datecreation) values ('"
				. $subscr_id . "', '" . $txn_type . "','" . $subscr_date . "','" . $subscr_effective . "','"
				. $period1 . "','" . $period2 . "','" . $period3 . "','" . $amount1 . "','" . $amount2 . "','"
				. $amount3 . "','" . $mc_amount1 . "','" . $mc_amount2 . "','" . $mc_amount3 . "','" . $recurring
				. "','" . $reattempt . "','" . $retry_at . "','" . $recur_times . "','" . $username . "','"
				. $password . "', '" . $txn_id . "','" . $payer_email . "','" . $fecha . "')";
				$result=mysql_query($strQuery2) or die(user_error_handler("0", "Subscription - paypal_subscription_info, Query failed:<br>"
				. mysql_error() . "<br>" . mysql_errno(),"ipn.php",0));

				//				mail($notify_email, "VERIFIED IPN", "$res\n $req\n $strQuery\n $struery\n  $strQuery2");
				user_error_handler("0", "VERIFIED IPN\n","ipn.php",0);
			}


		}

		// if the IPN POST was 'INVALID'...do this

		else if (strcmp($res, "INVALID") == 0)
		{
			// log for manual investigation
			//		mail($notify_email, "INVALID IPN", "$res\n $req");
			user_error_handler("1", "INVALID IPN\n","ipn.php",0);
		}

	}
	fclose($fp);

}

?>

