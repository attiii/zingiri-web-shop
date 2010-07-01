<?php
/*  checkout.php
 Copyright 2006, 2007, 2008 Elmar Wenners
 Support site: http://www.chaozz.nl

 This file is part of FreeWebshop.org.

 FreeWebshop.org is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FreeWebshop.org is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
?>
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
			if ($db->select($query)) $dig='<br />'.$txt['readorder100'].': <a href="'.get_option('home').'/?page=products">'.$txt['menu15'].'</a>';
			else $dig="";

			//update basket status
			$query = sprintf("UPDATE `".$dbtablesprefix."basket` SET `STATUS` = 1 WHERE `CUSTOMERID` = %s AND `STATUS` = 0", quote_smart($customerid));
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
		if (CountCart($customerid) == 0) {
			PutWindow($gfx_dir, $txt['general12'], $txt['checkout2'], "warning.gif", "50");
			$error = 1;
		}
		
		$totalWeight=WeighCart($customerid);

		// lets find out some customer details
		$query = sprintf("SELECT * FROM ".$dbtablesprefix."customer WHERE ID = %s", quote_smart($customerid));
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

		if ($error == 0) {
			// set global variables if not set yet
			foreach ($zingPrompts->vars as $var) { global $$var; }

			// read the details
			if ($row = mysql_fetch_array($sql)) {
				$address=new wsAddress($customerid);
				$adrid=$_POST['address'];
				$adr=$address->getAddress($adrid);
				$initials=$adr['INITIALS'];
				$middlename=$adr['MIDDLENAME'];
				$lastname = $adr['LASTNAME'];
				$address=$adr['ADDRESS'];
				$zipcode=$adr['ZIP'];
				$city=$adr['CITY'];
				$state=$adr['STATE'];
				$to = $row[12];
				$country=$adr['COUNTRY'];
				$phone = $row[11];
				$customer_row = $row;
			}

			// process the order. NOTE: the price is calculated and added later on in this process!!! so $total is still empty at this point
			// let's see if an aborted order already exists in which case we'll reuse it
			$query = sprintf("SELECT `ORDERID` FROM `".$dbtablesprefix."basket` WHERE `STATUS`=0 AND `CUSTOMERID`=%s AND `ORDERID` <> 0", quote_smart($customerid));
			$sql = mysql_query($query) or die(mysql_error());
			if ($row = mysql_fetch_array($sql)) {
				$query_order = sprintf("SELECT `DATE` FROM `".$dbtablesprefix."order` WHERE `ID`=%s", $row['ORDERID']);
				$sql_order = mysql_query($query_order) or die(mysql_error());
				if ($row_order = mysql_fetch_array($sql_order)) {
					$lastid=$row['ORDERID'];
					$orderDate=$row_order['DATE'];
				}
			} else {
				$orderDate=Date($date_format);
				$query = sprintf("INSERT INTO `".$dbtablesprefix."order` (`ADDRESSID`,`DATE`,`STATUS`,`SHIPPING`,`PAYMENT`,`CUSTOMERID`,`TOPAY`,`WEBID`,`NOTES`,`WEIGHT`) VALUES (%s,'".$orderDate."','1',%s,%s,%s,'1','n/a',%s,%s)", quote_smart($adrid), quote_smart($shippingid), quote_smart($paymentid), quote_smart($customerid), quote_smart($notes), quote_smart($weightid));
				$sql = mysql_query($query) or die(mysql_error());
				// get the last id
				$lastid = mysql_insert_id();
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
			$tpl->replace('CUSTOMERID',$customerid);
				
			$message = $txt['checkout3'];
			$paymentmessage = "";
			// now go through all all products from basket with status 'basket'

			$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE ( CUSTOMERID = ".$customerid." AND STATUS = 0 )";
			$sql = mysql_query($query) or die(mysql_error());
			$total = 0;

			// let's format the product list a little
			$message .= "<table width=\"100%\" class=\"borderless\">";
				
			while ($row = mysql_fetch_row($sql)) {
				$query_details = "SELECT * FROM ".$dbtablesprefix."product WHERE ID = '" . $row[2] . "'";
				$sql_details = mysql_query($query_details) or die(mysql_error());

				while ($row_details = mysql_fetch_array($sql_details)) {
					$product_price = $row[3]; // read from the cart
					// additional costs for features?
					if (!empty($row[7])) {
						// features might involve extra costs, but we don't want to show them
						$features = explode(", ", $row[7]);
						$counter1 = 0;
						$printvalue = "";
						while (!$features[$counter1] == NULL){
							$feature = explode("+",$features[$counter1]);
							$printvalue .= $feature[0];    // don't show the extra costs here, just show the feature
							$counter1 += 1;
							if (!empty($features[$counter1])) { $printvalue .= ", "; }
							$product_price += $feature[1]; // if there are extra costs, let's add them
						}
					}

					//					if ($no_vat == 0 && $db_prices_including_vat == 0) {
					$tax = new wsTax($product_price);
					$product_price = $tax->in;
					//					}
						
					// make up the description to print according to the pricelist_format and max_description
					$print_description=printDescription($row_details[1],$row_details[3]);
					if (!empty($row[7])) { $print_description .= "<br />".$printvalue; } // product features
					$total_add = $product_price * $row[6];
					$message .= "<tr><td>".$row[6].$txt['checkout4']."</td><td>".$print_description."<br />".$currency_symbol_pre.myNumberFormat($product_price,$number_format).$currency_symbol_post.$txt['checkout5']."</td><td style=\"text-align: right\">".$currency_symbol_pre.myNumberFormat($total_add,$number_format).$currency_symbol_post."</tr>";
					$tpl->repeatRow(array('DESCRIPTION','QTY','PRICE','LINETOTAL'));
					$tpl->replace('DESCRIPTION',$print_description);
					$tpl->replace('QTY',$row[6]);
					$tpl->replace('PRICE',$currency_symbol_pre.myNumberFormat($product_price,$number_format).$currency_symbol_post);
					$tpl->replace('LINETOTAL',$currency_symbol_pre.myNumberFormat($total_add,$number_format).$currency_symbol_post);

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
				$message.= '<tr><td>'.$txt['checkout14'].'</td><td>'.$txt['checkout18'].' '.$discount_code.'<br />';
				$tpl->replace('DISCOUNTCODE',$discount_code);
				if ($discount->percentage>0) {
					// percentage
					$tpl->replace('DISCOUNTRATE',$discount->percentage.'%');
					$message.= $txt['checkout14'].' '.$discount->percentage.'%</td><td style="text-align: right"><strong>-'.$currency_symbol_pre.myNumberFormat($discount->discount,$number_format).$currency_symbol_post.'</strong></td></tr>';
				}
				else {
					$tpl->replace('DISCOUNTRATE','');
					$message.= $txt['checkout14'].' '.$currency_symbol_pre.myNumberFormat($discount->discount,$number_format).$currency_symbol_post.'</td><td style="text-align: right"><strong>-'.$currency_symbol_pre.myNumberFormat($discount->discount,$number_format).$currency_symbol_post.'</strong></td></tr>';
				}
				$tpl->replace('DISCOUNTAMOUNT',$currency_symbol_pre.myNumberFormat($discount->discount,$number_format).$currency_symbol_post);
				$total -= $discount->discount;
				$discount->consume();
			}
			$tpl->removeRow(array('DISCOUNTCODE','DISCOUNTRATE','DISCOUNTAMOUNT'));
				
			// if the customer added additional notes/questions, we will display them too
			if (!empty($_POST['notes'])) {
				$message = $message . $txt['checkout6'].$txt['checkout6']; // white line
				$message = $message . $txt['shipping3']."<br />".nl2br($notes);
			}
			$tpl->replace('NOTES',nl2br($notes));

			// first the shipping description
			$query = sprintf("SELECT * FROM `".$dbtablesprefix."shipping` WHERE `id` = %s", quote_smart($shippingid));
			$sql = mysql_query($query) or die(mysql_error());

			while ($row = mysql_fetch_row($sql)) {
				$shipping_descr = $row[1];
			}

			// read the shipping costs
			$query = sprintf("SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE `ID` = %s", quote_smart($weightid));
			$sql = mysql_query($query) or die(mysql_error());

			while ($row = mysql_fetch_row($sql)) {
				$sendcosts = $row[4];
			}
			$zingPrompts->load(true); // update sendcost in language file
			$message .= '<tr><td>'.$txt['checkout16'].'</td><td>'.$shipping_descr.'</td><td style="text-align: right">'.$currency_symbol_pre.myNumberFormat($sendcosts,$number_format).$currency_symbol_post.'</td></tr>';
			$tpl->replace('SHIPPINGMETHOD',$shipping_descr);
			$tpl->replace('SHIPPINGCOSTS',$currency_symbol_pre.myNumberFormat($sendcosts,$number_format).$currency_symbol_post);

			$total = $total + $sendcosts;
			$totalprint = myNumberFormat($total);
			$print_sendcosts = myNumberFormat($sendcosts);
			$total_nodecimals = number_format($total, 2,"","");
			$zingPrompts->load(true);
			$tax = new wsTax($total);
			$taxheader=$txt['checkout102'];
			if (count($tax->taxes)>0) {
				foreach ($tax->taxes as $label => $data) {
					$tpl->repeatRow(array('TAXLABEL','TAXRATE','TAXTOTAL'));
					$tpl->replace('TAXRATE',$data['RATE']);
					$tpl->replace('TAXTOTAL',$currency_symbol_pre.myNumberFormat($data['TAX'],$number_format).$currency_symbol_post);
					$tpl->replace('TAXLABEL',$label);
					$message .= '<tr><td>'.$taxheader.'</td><td>'.$label.' '.$data['RATE'].'%</td><td style="text-align: right">'.$currency_symbol_pre.myNumberFormat($data['TAX'],$number_format).$currency_symbol_post.'</td></tr>';
					$taxheader="";
				}
			}

			// now lets calculate the invoice total now we know the final addition, the shipping costs
			$message .= '<tr><td>'.$txt['checkout24'].'</td><td>'.$txt['checkout25'].'</td><td style="text-align: right"><big><strong>'.$currency_symbol_pre.myNumberFormat($total,$number_format).$currency_symbol_post.'</strong></big></td></tr>';
			$tpl->replace('TOTAL',$currency_symbol_pre.myNumberFormat($total,$number_format).$currency_symbol_post);
			$message .= "</table><br /><br />";

			// shippingmethod 2 is pick up at store. if you don't support this option, there is no need to remove this
			if ($shippingid != "2" && $totalWeight > 0) { // only show shipping address if something to ship and not pickup from store
				$message .= $txt['checkout17']; // shipping address
				$tpl->replace('PHONE',$customer_row['PHONE']);
				$tpl->replace('COMPANY',$customer_row['COMPANY']);
				$tpl->replace('ADDRESS',$address);
				$tpl->replace('ZIPCODE',$zipcode);
				$tpl->replace('CITY',$city);
				$tpl->replace('STATE',$state);
				$tpl->replace('COUNTRY',$country);
			} else {
				$message .= $txt['checkout18']; // appointment line
				$tpl->replace('PHONE','');
				$tpl->replace('COMPANY','');
				$tpl->replace('ADDRESS','');
				$tpl->replace('ZIPCODE','');
				$tpl->replace('CITY','');
				$tpl->replace('STATE','');
				$tpl->replace('COUNTRY','');
			} 
			$message = $message . $txt['checkout6'].$txt['checkout6']; // white line

			// now the payment
			$payment=new paymentCode();
			$payment_code=$payment->getCode($paymentid,$customer_row,$total,$webid);

			$message .= $txt['checkout19'].$payment_descr; // Payment method:
			$message .= $txt['checkout6']; // line break

			// the two standard build in payment methods
			if ($paymentid == "1") {
				//bank payment
				$paymentmessage = $txt['checkout20']; // bank info
				$paymentmessage .= $txt['checkout6'].$txt['checkout6']; // new line
				$paymentmessage .= $txt['checkout26'];  // pay within xx days
				$message.=$paymentmessage;
			} elseif ($paymentid == "2") {
				// if the payment method is 'pay at the store', you don't need to pay within 14 days
				$paymentmessage = $txt['checkout21']; // cash payment
				$message.=$paymentmessage;
			} else {
				//other methods
				//$paymentmessage .= $txt['checkout6'].$txt['checkout6']; // new line
				if ($payment_code!='') {
					$paymentmessage = $payment_code;
					$message .= $paymentmessage;
				} else {
					$message .= $paymentmessage;
				}
				$paymentmessage .= $txt['checkout26'];  // pay within xx days
				//if (!$autosubmit)
			}
			$tpl->replace('PAYMENTCODE',$paymentmessage);
				
			$message .= $txt['checkout6']; // white line
			$message .= $txt['checkout9']; // direct link to customer order for online status checking
				
			$message=$tpl->getContent();
				
			//update order & basket
			if ($autosubmit && $payment_code!="") {
				$basket_status=0;
				$order_status=0;
			} else {
				$basket_status=1;
				$order_status=1;
			}
			// order update
			$query = "UPDATE `".$dbtablesprefix."order` SET `STATUS`=".qs($order_status).", `TOPAY` = '".$total."',`DISCOUNTCODE`=".qs($discount_code)." WHERE `ID` = ".$lastid;
			$sql = mysql_query($query) or die(mysql_error());

			//basket update
			$query = sprintf("UPDATE `".$dbtablesprefix."basket` SET `ORDERID` = '".$lastid."',`STATUS`=%s WHERE (`CUSTOMERID` = %s AND `STATUS` = '0')", qs($basket_status), quote_smart($customerid));
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
				require_once(dirname(__FILE__)."/addons/dompdf-0.6.1/dompdf_config.inc.php");
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

			// email subject
			$subject  = $txt['checkout10'].":".$webid;

			// email confirmation in case no autosubmit
			if (!$autosubmit  || ($autosubmit && $payment_code=='')) {
				$subject = $txt['checkout10'];
				if (mymail($sales_mail, $to, $subject, $message, $charset)) {
					PutWindow($gfx_dir, $txt['general13'], $txt['checkout11'], "notify.gif", "50");
				}
				else { PutWindow($gfx_dir, $txt['general12'], $txt['checkout12'], "warning.gif", "50"); }
			}
			mymail($sales_mail, $sales_mail, $txt['db_status1'].":".$webid, $message, $charset); // no error checking here, because there is no use to report this to the customer

			// now lets show the customer some details
			CheckoutShowProgress();
				
			// now print the confirmation on the screen
			if (!$autosubmit || ($autosubmit && $payment_code=='')) {
				echo '
		     <table width="100%" class="datatable">
		       <caption>'.$txt['checkout13'].'</caption>
		       <tr><td>'.$message.'
		       </td></tr>
		     </table>
		     <h4><a href="'.ZING_URL.'fws/printorder.php?orderid='.$lastid.'" target="_blank">'.$txt['readorder1'].'</a>';
				if ($create_pdf == 1) { echo "<br /><a href=\"".$orders_url."/".$pdf."\" target=\"_blank\">".$txt['checkout27']."</a></h4>"; }
			} else {
				PutWindow($gfx_dir, $txt['general13'], $txt['checkout104'], "loader.gif", "50");
				echo '<div>'.$payment_code.'</div>';
				if (ZING_PROTOTYPE) {
					?>
<script type="text/javascript" language="javascript">
//<![CDATA[
					document.observe("dom:loaded", function() {
           				wsSubmit();
					});
           //]]>
</script>
					<?php
				} elseif (ZING_JQUERY) {?>
<script type="text/javascript" language="javascript">
//<![CDATA[
					jQuery(document).ready(function() {
        		   		wsSubmit();
					});
			           //]]>
					</script>
				<?php }
			}

		}
		//end
	}
}
?>
