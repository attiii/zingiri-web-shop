<?php if ($index_refer <> 1) { exit(); } ?>
<?php include (dirname(__FILE__)."/includes/checklogin.inc.php"); ?>
<?php
global $shippingid,$weightid,$paymentid,$notes,$discount_code;
CheckoutInit();
?>
<?php
if (LoggedIn() == True) {
	$error = 0;

	//return from payment gateway
	if (isset($_GET['status'])) {
		$paymentstatus=intval($_GET['status']);
		if ($paymentstatus == 1) {
			$status=9; //completed

			//get order id
			$webid=$_GET['webid'];
			$db=new db();
			$query = sprintf("SELECT `ID` FROM `##order` WHERE `WEBID` = %s", quote_smart($webid));
			if ($db->select($query)) {
				$db->next();
				$orderid=$db->get('id');
			}

			//check if digital order
			$query = sprintf("SELECT `##basket`.`ID` FROM `##basket`,`##product` WHERE `##product`.`ID`=`##basket`.`PRODUCTID` AND `##product`.`LINK` IS NOT NULL AND `##basket`.`ORDERID` = %s", quote_smart($orderid));
			if ($db->select($query)) $dig='<br />'.$txt['readorder100'].': <a href="'.zurl('index.php/?page=products').'">'.$txt['menu15'].'</a>';
			else $dig="";

			//update basket status
			$query = sprintf("UPDATE `".$dbtablesprefix."basket` SET `STATUS` = 1 WHERE `CUSTOMERID` = %s AND `STATUS` = 0", quote_smart(wsCid()));
			$sql = mysql_query($query) or die(mysql_error());

			PutWindow($gfx_dir, $txt['general13'], $txt['checkout100'].$dig, "notify.gif", "50");
		} else {
			$status=8; //error or cancelled
			PutWindow($gfx_dir, $txt['general12'], $txt['checkout101'], "warning.gif", "50");
		}
	} else {
		$status=1; //first run
	}

	//first pass
	if ($status==1) {

		// if the cart is empty, then you shouldn't be here
		if (CountCart(wsCid()) == 0) {
			PutWindow($gfx_dir, $txt['general12'], $txt['checkout2'], "warning.gif", "50");
			$error = 1;
		}

		$totalWeight=WeighCart(wsCid());

		// lets find out some customer details
		$query = sprintf("SELECT * FROM ".$dbtablesprefix."customer WHERE ID = %s", quote_smart(wsCid()));
		$sql = mysql_query($query) or die(mysql_error());

		// we can not find you, so please leave
		if (mysql_num_rows($sql) == 0) {
			PutWindow($gfx_dir, $txt['general12'], $txt['checkout2'], "warning.gif", "50");
			$error = 1;
		}

		// if you gave a discount code, let's check if it's valid
		if ($discount_code <> "") {
			$discount=new wsDiscount($discount_code);
			if (!$discount->exists()) {
				PutWindow($gfx_dir, $txt['general12'], $txt['checkout1'], "warning.gif", "50");
				$error = 1;
			}
		}
		//check conditions accepted
		if (isset($_POST['onecheckout']) && $_POST['onecheckout']==true && $_POST['conditions']!="on") {
			PutWindow($gfx_dir, $txt['general12'], $txt['checkout103'], "warning.gif", "50");
			$error = 1;
		}

		//check credit card
		if (get_option('wspro_offline-cc') && class_exists('wsCreditCard')) {
			$wsCreditCard=new wsCreditCard(wsCid());
			$wsCardId=$wsCreditCard->getCardId();
		} else $wsCardId=0;

		if ($error == 0) {
			// set global variables if not set yet
			foreach ($zingPrompts->vars as $var) { global $$var; }

			// read the details
			if ($row = mysql_fetch_array($sql)) {
				$address=new wsAddress(wsCid());
				$adrid=isset($_POST['address']) ? $_POST['address'] : '';
				$adr=$address->getAddress($adrid);
				$initials=$adr['INITIALS'];
				$middlename=$adr['MIDDLENAME'];
				$lastname = $adr['LASTNAME'];
				$address=$adr['ADDRESS'];
				$zipcode=$adr['ZIP'];
				$city=$adr['CITY'];
				$state=$adr['STATE'];
				$to = $row['EMAIL'];
				$country=$adr['COUNTRY'];
				$phone = $row['PHONE'];
				$customer_row = $row;
			}

			// process the order. NOTE: the price is calculated and added later on in this process!!! so $total is still empty at this point
			// let's see if an aborted order already exists in which case we'll reuse it
			$lastid=0;
			$query = sprintf("SELECT `ORDERID` FROM `".$dbtablesprefix."basket` WHERE `STATUS`=0 AND `CUSTOMERID`=%s AND `ORDERID` <> 0", quote_smart(wsCid()));
			$sql = mysql_query($query) or die(mysql_error());
			if ($row = mysql_fetch_array($sql)) {
				$query_order = sprintf("SELECT `DATE` FROM `".$dbtablesprefix."order` WHERE `ID`=%s", $row['ORDERID']);
				$sql_order = mysql_query($query_order) or die(mysql_error());
				if ($row_order = mysql_fetch_array($sql_order)) {
					$lastid=$row['ORDERID'];
					$orderDate=$row_order['DATE'];
				}
			}
			if (!$lastid) {
				$orderDate=Date($date_format);
				$query = sprintf("INSERT INTO `".$dbtablesprefix."order` (`ADDRESSID`,`DATE`,`STATUS`,`SHIPPING`,`PAYMENT`,`CUSTOMERID`,`TOPAY`,`WEBID`,`NOTES`,`WEIGHT`) VALUES (%s,'".$orderDate."','1',%s,%s,%s,'1','n/a',%s,%s)", quote_smart($adrid), quote_smart($shippingid), quote_smart($paymentid), quote_smart(wsCid()), quote_smart($notes), quote_smart($weightid));
				$sql = mysql_query($query) or die(mysql_error());
				// get the last id
				$lastid = mysql_insert_id();
			}
				
			//link credit card to order
			if (get_option('wspro_offline-cc') && class_exists('wsCreditCard') && $wsCardId) {
				$wsCreditCard->updateOrder($lastid,$wsCardId);
			}
				
			// make webID
			$date_array = GetDate();
			$this_year = $date_array['year'];
			$webid = $order_prefix . $this_year. $lastid . $order_suffix;
			$query = "UPDATE `".$dbtablesprefix."order` SET `WEBID` = '".$webid."' WHERE `ID` = ".$lastid;
			$sql = mysql_query($query) or die(mysql_error());

			$zingPrompts->load(true);

			//template
			$tpl=new wsTemplate('order',$lang);
			$tpl->replace('ORDERDATE',$orderDate);
			$tpl->replace('INITIALS',$initials);
			$tpl->replace('LASTNAME',$lastname);
			$tpl->replace('MIDDLENAME','');
			$tpl->replace('SHOPNAME',$shopname);
			$tpl->replace('SHOPURL',$shopurl);
			$tpl->replace('WEBID',$webid);
			$tpl->replace('CUSTOMERID',wsCid());
			$tpl->replace('EMAIL',$to);
				
			$paymentmessage = "";
			// now go through all all products from basket with status 'basket'

			$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE ( CUSTOMERID = ".wsCid()." AND STATUS = 0 )";
			$sql = mysql_query($query) or die(mysql_error());
			$total = 0;
			$tax = new wsTaxSum();

			// let's format the product list a little

			while ($row = mysql_fetch_array($sql)) {
				$query_details = "SELECT * FROM ".$dbtablesprefix."product WHERE ID = '" . $row[2] . "'";
				$sql_details = mysql_query($query_details) or die(mysql_error());

				while ($row_details = mysql_fetch_array($sql_details)) {
					$product_price = $row[3]; // read from the cart

					$tax->calculate($product_price*$row['QTY'],$row_details['TAXCATEGORYID']);
					$total_add = $tax->in;
					
					// make up the description to print according to the pricelist_format and max_description
					$print_description=$row_details['PRODUCTID'];
					if (!empty($row[7])) {
						$wsFeatures=new wsFeatures($row[7]);
						$wsFeatures->setDefinition($row_details['FEATURES'],$row_details['FEATURES_SET']);
						$print_description .= "<br />".$wsFeatures->toString();
						$wsFeatures->prepare();
					} // product features
					$tpl->repeatRow(array('DESCRIPTION','QTY','PRICE','LINETOTAL'));
					$tpl->replace('DESCRIPTION',$print_description);
					if ($pictureid == 1) { $picture = $row_details[0]; }
					else { $picture = $row_details[1]; }
					list($image_url,$height,$width)=wsDefaultProductImageUrl($picture,$row_details['DEFAULTIMAGE']);
					$thumb = "<img class=\"imgleft\" src=\"".$image_url."\"".$width.$height." alt=\"\" />";
					$tpl->replace('IMAGE',$thumb);
					$tpl->replace('QTY',$row[6]);
					$tpl->replace('PRICE',wsPrice::currencySymbolPre().wsPrice::format($product_price).wsPrice::currencySymbolPost());
					$tpl->replace('LINETOTAL',wsPrice::currencySymbolPre().wsPrice::format($total_add).wsPrice::currencySymbolPost());

					$total = $total + $total_add;

					// update stock amount if needed
					if ($stock_enabled == 1) {
						if ($row[6] > $row_details[5] || $row_details[5] == 0) {
							// the product stock is too low, so we have to cancel this order
							$zingPrompts->load(true);
							PutWindow($gfx_dir, $txt['general12'], $txt['checkout15']." ".$print_description."<br />".$txt['checkout7']." ".$row[6]."<br />".$txt['checkout8']." ".$row_details[5], "warning.gif", "50");
							$del_query = sprintf("DELETE FROM `".$dbtablesprefix."order` WHERE (`ID` = %s)", quote_smart($lastid));
							$del_sql = mysql_query($del_query) or die(mysql_error());
							$error = 1;
						}
						else {
							$new_stock = $row_details[5] - $row[6];
							$update_query = "UPDATE `".$dbtablesprefix."product` SET `STOCK` = ".$new_stock." WHERE `ID` = '".$row_details[0]."'";
							$update_sql = mysql_query($update_query) or die(mysql_error());
						}
					}
				}
			}

			// there might be a discount code
			if ($discount_code <> "") {
				$discount->calculate();
				$tpl->replace('DISCOUNTCODE',$discount_code);
				if ($discount->percentage>0) {
					// percentage
					$tpl->replace('DISCOUNTRATE',$discount->percentage.'%');
				}
				else {
					$tpl->replace('DISCOUNTRATE',wsPrice::currencySymbolPre().wsPrice::format($discount->discount).wsPrice::currencySymbolPost());
				}
				$tpl->replace('DISCOUNTAMOUNT',wsPrice::currencySymbolPre().wsPrice::format($discount->discount).wsPrice::currencySymbolPost());
				$total -= $discount->discount;
				$discount->consume();
			}
			$tpl->removeRow(array('DISCOUNTCODE','DISCOUNTRATE','DISCOUNTAMOUNT'));

			// if the customer added additional notes/questions, we will display them too
			$tpl->replace('NOTES',nl2br($notes));

			// first the shipping description
			$shipping=new wsShipping();
			$shipping_descr=$shipping->getShippingDescription($shippingid);			

			// read the shipping costs
			$sendcosts=wsPrice::price($shipping->getCosts($weightid));

			$shippingTax=new wsTax($sendcosts,wsSetting('SHIPPING_TAX_CATEGORY'));
				
			$zingPrompts->load(true); // update sendcost in language file
			$tpl->replace('SHIPPINGMETHOD',$shipping_descr);
			$tpl->replace('SHIPPINGCOSTS',wsPrice::currencySymbolPre().wsPrice::format($sendcosts).wsPrice::currencySymbolPost());

			$total = $total + $sendcosts;
			$totalprint = myNumberFormat($total);
			$print_sendcosts = myNumberFormat($sendcosts);
			$total_nodecimals = number_format($total, 2,"","");
			$zingPrompts->load(true);
			$taxheader=$txt['checkout102'];
			$noTaxes=true;
			if (count($tax->taxByCategory)>0) {
				foreach ($tax->taxByCategory as $taxCategory => $taxes) {
					if (count($taxes)>0) {
						foreach ($taxes as $label => $data) {
							$tpl->repeatRow(array('TAXLABEL','TAXRATE','TAXTOTAL'));
							$tpl->replace('TAXRATE',$data['RATE']);
							$tpl->replace('TAXTOTAL',wsPrice::currencySymbolPre().wsPrice::format($data['TAX']).wsPrice::currencySymbolPost());
							$tpl->replace('TAXLABEL',$label.' '.$data['CATEGORY']);
							$taxheader="";
							$noTaxes=false;
						}
					}
				}
			}

			if (count($shippingTax->taxByCategory)>0 && $shippingTax->tax != 0) {
				foreach ($shippingTax->taxByCategory as $taxCategory => $taxes) {
					if (count($taxes)>0) {
						foreach ($taxes as $label => $data) {
							$tpl->repeatRow(array('TAXLABEL','TAXRATE','TAXTOTAL'));
							$tpl->replace('TAXRATE',$data['RATE']);
							$tpl->replace('TAXTOTAL',wsPrice::currencySymbolPre().wsPrice::format($data['TAX']).wsPrice::currencySymbolPost());
							$tpl->replace('TAXLABEL',$label.' '.$data['CATEGORY']);
							$taxheader="";
							$noTaxes=false;
						}
					}
				}
			}
			if ($noTaxes) {
				$tpl->removeRow(array('TAXLABEL','TAXRATE','TAXTOTAL'));
			}
				
			// now lets calculate the invoice total now we know the final addition, the shipping costs
			$tpl->replace('TOTAL',wsPrice::currencySymbolPre().wsPrice::format($total).wsPrice::currencySymbolPost());
			// shippingmethod 2 is pick up at store. if you don't support this option, there is no need to remove this
//			if ($shippingid != "2" && $totalWeight > 0) { // only show shipping address if something to ship and not pickup from store
				$tpl->replace('PHONE',$customer_row['PHONE']);
				$tpl->replace('COMPANY',$customer_row['COMPANY']);
				$tpl->replace('ADDRESS',$address);
				$tpl->replace('ZIPCODE',$zipcode);
				$tpl->replace('CITY',$city);
				$tpl->replace('STATE',$state);
				$tpl->replace('COUNTRY',$country);
				/*
			} else {
				$tpl->replace('PHONE','');
				$tpl->replace('COMPANY','');
				$tpl->replace('ADDRESS','');
				$tpl->replace('ZIPCODE','');
				$tpl->replace('CITY','');
				$tpl->replace('STATE','');
				$tpl->replace('COUNTRY','');
			}
			*/

			// now the payment
			$payment=new paymentCode();
			$payment_code=$payment->getCode($paymentid,$customer_row,$total,$webid);

			// the two standard build in payment methods
			if ($paymentid == "1") {
				//bank payment
				$paymentmessage = $txt['checkout20']; // bank info
				$paymentmessage .= $txt['checkout6'].$txt['checkout6']; // new line
				$paymentmessage .= $txt['checkout26'];  // pay within xx days
			} elseif ($paymentid == "2") {
				// if the payment method is 'pay at the store', you don't need to pay within 14 days
				$paymentmessage = $txt['checkout21']; // cash payment
			} else {
				//other methods
				//$paymentmessage .= $txt['checkout6'].$txt['checkout6']; // new line
				if ($payment_code!='') {
					$paymentmessage = $payment_code;
				}
				$paymentmessage .= $txt['checkout26'];  // pay within xx days
			}
			$tpl->replace('PAYMENTCODE',$paymentmessage);

			$message=$tpl->getContent();

			$message=str_replace($shopurl.'/index.php?page=orders',zurl('index.php?page=orders'),$message);;

			if (isset($_REQUEST['wslive']) && $_REQUEST['wslive']=='dp') $message=str_replace($shopurl.'/index.php?',$_REQUEST['wsliveurl'].'/index.php?q=webshop&',$message);
				
			//update order & basket
			if ($autosubmit==1 && $payment_code!="") {
				$basket_status=0;
				$order_status=0;
			} elseif ($autosubmit==2 && $payment_code!="") {
				$basket_status=1;
				$order_status=1;
			} else {
				$basket_status=1;
				$order_status=1;
			}
			// order update
			$query = "UPDATE `".$dbtablesprefix."order` SET `CURRENCY`=".qs(wsPrice::ccy()).", `STATUS`=".qs($order_status).", `TOPAY` = '".$total."',`DISCOUNTCODE`=".qs($discount_code)." WHERE `ID` = ".$lastid;
			$sql = mysql_query($query) or die(mysql_error());

			//basket update
			$query = sprintf("UPDATE `".$dbtablesprefix."basket` SET `ORDERID` = '".$lastid."',`STATUS`=%s WHERE (`CUSTOMERID` = %s AND `STATUS` = '0')", qs($basket_status), quote_smart(wsCid()));
			$sql = mysql_query($query) or die(mysql_error());

			// make pdf
			$pdf = "";
			$fullpdf = "";
			if ($create_pdf == 1) {
				$m = '<html><head><meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" /></head>';
				if ($charset=='UTF-8') {
					$m.='<body style="font-family:courier;">';
					ini_set("memory_limit","512M");
					//$m.= utf8_decode($message);
					$m.=$message;
				} else {
					$m.='<body>';
					$m.=$message;
				}
				$m.='</body></html>';
				require_once(ZING_LOC."comlib/addons/dompdf/dompdf_config.inc.php");
				$dompdf = new DOMPDF();
				$dompdf->load_html($m);
				$dompdf->render();
				$output = $dompdf->output();
				$random = CreateRandomCode(5);
				$pdf = $webid."_".$random.".pdf";
				$fullpdf = $orders_dir."/".$pdf;
				file_put_contents($fullpdf, $output);
				$query = "UPDATE `".$dbtablesprefix."order` SET `PDF` = '".$pdf."' WHERE `ID` = ".$lastid;
				$sql = mysql_query($query) or die(mysql_error());
			}

			// save the order in order folder for administration
			$security = "<?php if ($"."index_refer <> 1) { exit(); } ?>";
			$handle = fopen ($orders_dir."/".strval($webid).".php", "w+");
			if (!fwrite($handle, $security.$message))
			{
				$retVal = false;
			}
			else {
				fclose($handle);
			}

			// email confirmation in case no autosubmit
			if (!$autosubmit  || ($autosubmit && $payment_code=='')) {
				$subject = $txt['checkout10'];
				if (mymail($sales_mail, $to, $subject, $message, $charset)) {
					PutWindow($gfx_dir, $txt['general13'], $txt['checkout11'], "notify.gif", "50");
				}
				else { PutWindow($gfx_dir, $txt['general12'], $txt['checkout12'], "warning.gif", "50"); }
			}
			if (wsSetting('order_email_admin')) {
				$adminSubject = $txt['db_status1'].":".$webid;
				$adminMessage=$txt['checkout105'].' '.$to.'<br /><br />';
				$adminMessage.=$message;
				mymail($sales_mail, $sales_mail, $adminSubject, $adminMessage, $charset); // no error checking here, because there is no use to report this to the customer
			}

			// now lets show the customer some details
			CheckoutShowProgress();

			// now print the confirmation on the screen
			if (!$autosubmit || ($autosubmit && $payment_code=='')) {
				echo '
		     <table width="100%" class="datatable">
		       <tr><td>'.$message.'
		       </td></tr>
		     </table>
		     <h4><a href="'.zurl('index.php?page=printorder&orderid='.$lastid).'" target="_blank">'.$txt['readorder1'].'</a>';
				if ($create_pdf == 1) { echo "<br /><a href=\"".$orders_url."/".$pdf."\" target=\"_blank\">".$txt['checkout27']."</a>"; }
				echo '</h4>';
			} else {
				PutWindow($gfx_dir, $txt['general13'], $txt['checkout104'], "loader.gif", "50");
				echo '<div>'.$payment_code.'</div>';
				?>
<script type="text/javascript" language="javascript">
//<![CDATA[
					jQuery(document).ready(function() {
        		   		wsSubmit();
					});
			           //]]>
					</script>
				<?php
			}
		}
		//end
	}
}
?>
