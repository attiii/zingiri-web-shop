<?php
/*  onecheckout.php
 Copyright 2008,2009,2010 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Web Shop.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Zingiri Web Shop; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php include (dirname(__FILE__)."/includes/checklogin.inc.php"); ?>

<?php
if (loggedin()) {
	if (!empty($_POST['numprod'])) {
		$numprod=intval($_POST['numprod']);
	}
	if (!empty($_POST['paymentid'])) {
		$paymentid=intval($_POST['paymentid']);
	}
	if (!empty($_POST['basketid'])) {
		$basketid=intval($_POST['basketid']);
	}
	if (!empty($_POST['conditions']) && $_POST['conditions']=="on") {
		$conditions=true;
	}
	if (!empty($_POST['notes'])) {
		$notes=$_POST['notes'];
	}
	if (!empty($_GET['prodid'])) {
		$prodid=intval($_GET['prodid']);
		if (!empty($_POST['numprod'][$prodid])) $numprod=$_POST['numprod'][$prodid];
		echo '/'.$prodid.'/'.$numprod.'<br />';
	}
	if (isset($_POST['shipping'])) { list($weightid, $shippingid) = explode(":", $_POST['shipping']); }

	if (isset($_POST['discount_code'])) {
		$discount_code=$_POST['discount_code'];
		if ($discount_code <> "") {
			$discount_query="SELECT * FROM `".$dbtablesprefix."discount` WHERE `code` = '".$discount_code."' AND `orderid` = '0'";
			$discount_sql = mysql_query($discount_query) or die(mysql_error());
			if (mysql_num_rows($discount_sql) == 0) {
				PutWindow($gfx_dir, $txt['general12'], $txt['checkout1'], "warning.gif", "50");
				$error = 1;
			}
			else {
				// let's read the discount
				while ($discount_row = mysql_fetch_row($discount_sql)) {
					$discount_amount = $discount_row[2];
					$discount_percentage = $discount_row[3];
				}
			}
		}
	}
	// current date
	$today = getdate();
	$error = 0; // no errors found
	if ($action=="delete"){
		$query = "DELETE FROM `".$dbtablesprefix."basket` WHERE `CUSTOMERID` = '". $customerid."' AND `STATUS` = '0' AND  `PRODUCTID` = '". $prodid."'";
		$sql = mysql_query($query) or die(mysql_error());
	} elseif ($action=="update"){
		// if we work with stock amounts, then lets check if there is enough in stock
		if ($stock_enabled == 1) {
			$query = "SELECT `STOCK` FROM `".$dbtablesprefix."product` WHERE `ID` = '".$prodid."'";
			$sql = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_row($sql);

			if ($numprod > $row[0] || $row[0] == 0) {
				PutWindow($gfx_dir, $txt['general12'], $txt['checkout15']."<br />".$txt['checkout7']." ".$numprod."<br />".$txt['checkout8']." ".$row[0], "warning.gif", "50");
				$error = 1;
			}
		}
		if ($error == 0) {
			$query = "UPDATE `".$dbtablesprefix."basket` SET `QTY` = ".$numprod." WHERE `CUSTOMERID` = '". $customerid."' AND `STATUS` = '0' AND  `PRODUCTID` = '". $prodid."'";
			$sql = mysql_query($query) or die(mysql_error());
		}
	}

	CheckoutShowProgress();

	//shipping start
	?>
<form id="checkout" method="post" action="<?php zurl('index.php?page=checkout',true);?>">
<table width="100%" class="datatable">
	<caption><?php echo $txt['cart9']; ?></caption>
	<tr>
		<td colspan="4"><?php echo $txt['shipping2'] ?><br />
		<?php if (ZING_PROTOTYPE) {?> <SELECT NAME="shipping" id="shipping">
		<?php } else {?>
			<SELECT NAME="shipping" id="shipping"
				onChange="this.form.action='?page=onecheckout';this.form.submit();"
			>
			<?php }?>
			<?php
			// find out the shipping methods
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
							if ($shippingid==$row[0] && $weightid==$weight_row[0]) $selected='selected="SELECTED"'; else $selected="";
							echo "<OPTION VALUE=\"".$weight_row[0].":".$row[0]."\" ".$selected." >".$row[1]."&nbsp;(".$currency_symbol_pre.myNumberFormat($weight_row[4],$number_format).$currency_symbol_post.")</OPTION>";
						}
					}
				}
			}

			?>
			</SELECT>
			<br />
			<?php echo $txt['shipping10'] ?>
			<br />
			<SELECT NAME="paymentid" id="paymentid">
			<?php
			// find out the payment methods
			$query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$shippingid."' ORDER BY `paymentid`";
			$sql = mysql_query($query) or die(mysql_error());

			while ($row = mysql_fetch_row($sql)) {
				$query_pay="SELECT * FROM `".$dbtablesprefix."payment` WHERE `id`='".$row[1]."'";
				$sql_pay = mysql_query($query_pay) or die(mysql_error());

				while ($row_pay = mysql_fetch_row($sql_pay)) {
					if (!$paymentid) $paymentid=$row_pay[0];
					if ($paymentid==$row_pay[0]) $selected='selected="SELECTED"'; else $selected="";
					echo "<OPTION VALUE=\"".$row_pay[0]."\" ".$selected.">".$row_pay[1];
				}
			}
			?>
			</SELECT></td>
	</tr>
	<tr>
	<?php
	if (WeighCart($customerid) > 0) {
		echo '<td colspan="4">'.$txt['customer21'].'</td></tr><tr>';
		$address=new wsAddress($customerid);
		$addresses=$address->getAddresses();
		$i=0;
		$first=true;
		foreach ($addresses as $adrid => $adr) {
			$i++;
			if ($i > 4) {
				echo '</tr><tr>';
				$i=1;
			}
			echo '<td width="25%">';
			echo '<strong>'.$adr['NAME'].'</strong><br />';
			echo $adr['ADDRESS'].'<br />';
			echo $adr['CITY'].','.$adr['ZIP'].'<br />';
			if ($adr['STATE']) echo $adr['STATE'].'<br />';
			echo $adr['COUNTRY'].'<br />';
			if ($_POST['address'] == $adrid || ($_POST['address']=='' && $first)) $selected = 'CHECKED'; else $selected="";
			echo '<input type="radio" name="address" value="'.$adrid.'" '.$selected.'/>';
			if ($adrid > 0) {
				echo '<a href="index.php?zfaces=form&action=edit&form=address&id='.$adrid.'&redirect='.urlencode('index.php?page=onecheckout').'" class="button">'.$txt['browse7'].'</a>';
				echo ' ';
				echo '<a href="index.php?zfaces=form&action=delete&form=address&id='.$adrid.'&redirect='.urlencode('index.php?page=onecheckout').'" class="button">'.$txt['browse8'].'</a>';
			}
			echo '</td>';
			$first=false;
		}
		echo '<tr><td colspan="4">';
		echo '<a href="index.php?zfaces=form&action=add&form=address&redirect='.urlencode('index.php?page=onecheckout').'" class="button">'.$txt['shippingadmin10'].'</a>';
		echo '</td></tr>';
	}
	?>
	</tr>
</table>
<table>
	<tr>
		<td><?php echo $txt['shipping5']?> <input type="text" id="discount_code" name="discount_code"
			value="<?php echo $discount_code?>"
		> <?php if (!ZING_PROTOTYPE)?> <input type="submit" name="discount"
			value="<?php echo $txt['cart10'];?>"
			onclick="this.form.action='?page=onecheckout';this.form.submit();"
		/> <?php ?></td>
	</tr>
</table>
	<?php //}

	//shipping end
	// read basket
	$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (`CUSTOMERID` = ".$customerid." AND `STATUS` = 0) ORDER BY ID";
	$sql = mysql_query($query) or zfdbexit($query);
	$count = mysql_num_rows($sql);

	if ($count == 0) {
		PutWindow($gfx_dir, $txt['cart1'], $txt['cart2'], "carticon.gif", "50");
	}
	else {
		?> <br />
<table width="100%" class="datatable">
	<tr>
		<th colspan="2"><?php echo $txt['cart3']; ?></th>
		<th><?php echo $txt['cart4']; ?></th>
		<th><?php echo $txt['cart5']; ?></th>
	</tr>

	<?php
	$optel = 0;
	$id=0;
	while ($row = mysql_fetch_row($sql)) {
		$id++;
		$query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
		$sql_details = mysql_query($query) or die(mysql_error());
		while ($row_details = mysql_fetch_row($sql_details)) {
			$optel = $optel +1;
			if ($optel == 3) { $optel = 1; }
			if ($optel == 1) { $kleur = ""; }
			if ($optel == 2) { $kleur = " class=\"altrow\""; }

			// is there a picture?
			if ($search_prodgfx == 1 && $use_prodgfx == 1) {

				if ($pictureid == 1) { $picture = $row_details[0]; }
				else { $picture = $row_details[1]; }

				// determine resize of thumbs
				$width = "";
				$height = "";
				$picturelink = "";
				$thumb = "";
					
				if ($pricelist_thumb_width != 0) { $width = " width=\"".$pricelist_thumb_width."\""; }
				if ($pricelist_thumb_height != 0) { $height = " height=\"".$pricelist_thumb_height."\""; }

				if (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/".$picture.".jpg\"".$width.$height." alt=\"\" />"; }
				if (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/".$picture.".gif\"".$width.$height." alt=\"\" />"; }
				if (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/".$picture.".png\"".$width.$height." alt=\"\" />"; }

				// if the script uses make_thumbs, then search for thumbs
				if ($make_thumbs == 1) {
					if (thumb_exists($product_dir ."/tn_". $picture . ".jpg")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/tn_".$picture.".jpg\" alt=\"\" />"; }
					if (thumb_exists($product_dir ."/tn_". $picture . ".gif")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/tn_".$picture.".gif\" alt=\"\" />"; }
					if (thumb_exists($product_dir ."/tn_". $picture . ".png")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/tn_".$picture.".png\" alt=\"\" />"; }
				}
					
				if ($thumb != "" && $thumbs_in_pricelist == 0) {
					// use a photo icon instead of a thumb
					$picturelink = "<a href=\"".$product_dir."/".$picture.".jpg\"><img src=".$gfx_dir."/photo.gif></a>";
					$thumb = "";
				}
			}

			// make up the description to print according to the pricelist_format and max_description
			$print_description=printDescription($row_details[1],$row_details[3]);
			?>
	<tr <?php echo $kleur; ?>>
		<td colspan="2"><a href="index.php?page=details&prod=<?php echo $row_details[0]; ?>"><?php echo $thumb.$print_description.$picturelink; ?></a>
		<?php
		$productprice = $row[3]; // the price of a product
		$printvalue = $row[7];   // features
		if (!$printvalue == "") { echo "<br />(".$printvalue.")"; }
		?></td>
		<td style="text-align: right"><?php 
		echo $currency_symbol_pre;
		$subtotaal = $productprice * $row[6];
		//$tax=new wsTax($subtotaal);
		//$subtotaal = $tax->in;
		$printprijs = myNumberFormat($subtotaal);
		echo $printprijs;
		echo $currency_symbol_post;
		?></td>
		<td style="text-align: right;"><input type="text" size="4"
			name="numprod[<?php echo $row_details[0];?>]" value="<?php echo $row[6] ?>"
		>&nbsp; <input type="submit" value="<?php echo $txt['cart10'] ?>"
			onclick="form.action='?page=onecheckout&action=update&prodid=<?php echo $row_details[0] ?>';"
			name="sub"
		> <br />
		<input type="submit" value="<?php echo $txt['cart6']; ?>"
			onclick="form.action='?page=onecheckout&action=delete&prodid=<?php echo $row_details[0] ?>';"
			name="sub"
		></td>
	</tr>
	<?php

	$totaal = $totaal + $subtotaal;
		}
	}
	//end of cart contents

	//manage discount
	if ($discount_code <> "") {
		echo '<tr><td colspan="2" style="text-align: right">'.$txt['checkout14'];
		if ($discount_percentage == 1) {
			// percentage
			$discount_percentage = $discount_amount;
			$discount_amount = ($totaal / 100) * $discount_amount;
			echo $discount_percentage.'%</td><td style="text-align: right">-'.$currency_symbol_pre.myNumberFormat($discount_amount,$number_format).$currency_symbol_post.'</td></tr>';
		}
		else {
			//fixed amount
			echo '</td><td style="text-align: right">-'.$currency_symbol_pre.myNumberFormat($discount_amount,$number_format).$currency_symbol_post.'</td></tr>';
		}
		$totaal -= $discount_amount;

	}

	//shipping costs
	if ($shippingid) {
		// first the shipping description
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."shipping` WHERE `id` = %s", quote_smart($shippingid));
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_row($sql)) {
			$shipping_descr = $row[1];
		}
	}

	// read the shipping costs
	if ($weightid) {
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE `ID` = %s", quote_smart($weightid));
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_row($sql)) $sendcosts = $row[4]; else $sendcosts = 0;
	}

	if ($sendcosts != 0) {
		echo '<tr><td>'.$txt['checkout16'].'</td><td>'.$shipping_descr.'</td><td style="text-align: right">'.$currency_symbol_pre.myNumberFormat($sendcosts,$number_format).$currency_symbol_post.'</td></tr>';
		$totaal += $sendcosts;
	}

	//calculate and display taxes
	$tax = new wsTax($totaal);
	$totaal_ex = $tax->exFtd;
	$totaal_in = $tax->inFtd;

	function displayTaxes($tax) {
		global $txt,$currency_symbol_pre,$number_format,$currency_symbol_post;
		$taxheader=$txt['checkout102'];
		if (count($tax->taxes>0)) {
			foreach ($tax->taxes as $label => $data) {
				echo '<tr>';
				if ($taxheader) {
					echo '<td rowspan="'.count($tax->taxes).'">'.$taxheader.'</td>';
				}
				echo '<td>'.$label.' '.$data['RATE'].'%</td><td style="text-align: right">'.$currency_symbol_pre.myNumberFormat($data['TAX'],$number_format).$currency_symbol_post.'</td>';
				if ($taxheader) {
					echo '<td rowspan="'.count($tax->taxes).'"></td>';
				}
				$taxheader="";
				echo '</tr>';
			}
		}
	}
	if (!$db_prices_including_vat) displayTaxes($tax);

	//total
	?>
	<tr>
		<td colspan="2">
		<div style="text-align: right;"><strong><?php echo $txt['cart7']; ?></strong></div>
		</td>
		<td>
		<div style="text-align: right;"><?php echo $currency_symbol_pre.$totaal_in.$currency_symbol_post; ?><br />
		<?php if ($no_vat == 0) { echo "<small>(".$currency_symbol_pre.$totaal_ex.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>"; } ?></div>
		</td>
	</tr>
	<?php
	if ($db_prices_including_vat) displayTaxes($tax);
	?>
</table>
<br />
	<?php //notes
	echo $txt['shipping3'];
	?><br />
<textarea name="notes" rows="5" cols="80"><?php echo $notes;?></textarea><br />
<br />
<br />
<br />
<input type="hidden" name="onecheckout" value="1" /> <input type="checkbox" name="conditions"
<?php if ($conditions) echo 'checked="yes"'?>
/> <a href="<?php zurl('index.php?page=conditions&action=show',true)?>"><?php echo $txt['conditions1'];?></a><br />
<div style="text-align: center;"><input type=submit name=pay value="<?php echo $txt['cart9'] ?>"></div>
</form>

<?php
	}
	if (ZING_PROTOTYPE) {
		?>
<script type="text/javascript" language="javascript">
//<![CDATA[
           $checkout=new wsCheckout();
           $checkout.checkout();
//]]>
</script>
<?php 
	}
}?>