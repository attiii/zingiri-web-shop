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
<?php require (ZING_DIR."/includes/checklogin.inc.php"); ?>

<?php
if (loggedin()) {
	if (!empty($_POST['numprod'])) {
		$numprod=intval($_POST['numprod']);
	}
	if (!empty($_REQUEST['paymentid'])) {
		$paymentid=intval($_REQUEST['paymentid']);
	}
	if (!empty($_REQUEST['basketid'])) {
		$basketid=intval($_REQUEST['basketid']);
	}
	if (!empty($_POST['conditions']) && $_POST['conditions']=="on") {
		$conditions=true;
	}
	if (!empty($_POST['notes'])) {
		$notes=$_POST['notes'];
	}
	if (!empty($_GET['prodid'])) {
		$prodid=intval($_GET['prodid']);
		if (!empty($_POST['numprod'][$basketid])) $numprod=$_POST['numprod'][$basketid];
	}
	if (isset($_REQUEST['shipping'])) {
		list($weightid, $shippingid) = explode(":", $_REQUEST['shipping']);
		$weightid=intval($weightid);
		$shippingid=intval($shippingid);
		$wsShipping=$weightid.':'.$shippingid;
	}

	if (isset($_POST['discount_code'])) {
		$discount_code=$_POST['discount_code'];
		if ($discount_code <> "") {
			$discount=new wsDiscount($discount_code);
			if (!$discount->exists()) {
				PutWindow($gfx_dir, $txt['general12'], $txt['checkout1'], "warning.gif", "50");
				$error = 1;
			}
		}
	}
	// current date
	$today = getdate();
	$error = 0; // no errors found
	if ($action=="delete"){
		$query = "DELETE FROM `".$dbtablesprefix."basket` WHERE `CUSTOMERID` = '". wsCid()."' AND `STATUS` = '0' AND  `ID` = '". $basketid."'";
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
			$query = "UPDATE `".$dbtablesprefix."basket` SET `QTY` = ".$numprod." WHERE `CUSTOMERID` = '". wsCid()."' AND `STATUS` = '0' AND  `ID` = '". $basketid."'";
			$sql = mysql_query($query) or die(mysql_error());
		}
	}

	CheckoutShowProgress();

	//shipping start
	$cart_weight = WeighCart(wsCid());
	//check if combined shipping and weight is applicable
	if ($shippingid && $weightid) {
		$weight_query = "SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE '".$cart_weight."' >= `FROM` AND '".$cart_weight."' <= `TO` AND `SHIPPINGID` = '".$shippingid."'";
		$weight_sql = mysql_query($weight_query) or zfdbexit($weight_query);
		if ($row_weight=mysql_fetch_array($weight_sql)) {
			$weightid=$row_weight['ID'];
		} else {
			$weightid='';
			$shippingid='';
		}
	}
	?>
<form id="checkout" method="post" action="<?php zurl('index.php?page=checkout',true);?>">
<table width="100%" class="datatable">
	<tr>
		<td colspan="4"><?php echo $txt['shipping2'] ?><br />
		<SELECT NAME="shipping" id="shipping"
			onChange="this.form.action='<?php zurl('?page=onecheckout',true)?>';this.form.submit();"
		>
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
					$weight_query = "SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE '".$cart_weight."' >= `FROM` AND '".$cart_weight."' <= `TO` AND `SHIPPINGID` = '".$row[0]."'";
					$weight_sql = mysql_query($weight_query) or zfdbexit($weight_query);
					while ($weight_row = mysql_fetch_array($weight_sql)) {
						if (!$shippingid) $shippingid=$row[0];
						if (!$weightid) $weightid=$weight_row[0];
						if ($shippingid==$row[0] && $weightid==$weight_row[0]) $selected='selected="SELECTED"'; else $selected="";
						echo "<OPTION VALUE=\"".$weight_row[0].":".$row[0]."\" ".$selected." >".$row[1]."&nbsp;(".$currency_symbol_pre.myNumberFormat($weight_row[4],$number_format).$currency_symbol_post.")</OPTION>";
					}
				}
			}
		}

		?>
		</SELECT> <br />
		<?php echo $txt['shipping10'] ?> <br />
		<SELECT NAME="paymentid" id="paymentid"
			onChange="this.form.action='<?php zurl('?page=onecheckout',true)?>';this.form.submit();"
		>
		<?php
		// find out the payment methods
		$query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$shippingid."' ORDER BY `paymentid`";
		$sql = mysql_query($query) or die(mysql_error());

		while ($row = mysql_fetch_row($sql)) {
			$query_pay="SELECT * FROM `".$dbtablesprefix."payment` WHERE `id`='".$row[1]."'";
			$sql_pay = mysql_query($query_pay) or die(mysql_error());

			while ($row_pay = mysql_fetch_array($sql_pay)) {
				if (!$paymentid) $paymentid=$row_pay[0];
				if ($paymentid==$row_pay[0]) $selected='selected="SELECTED"'; else $selected="";
				if ($paymentid==$row_pay[0] && $row_pay['GATEWAY']=='offlinecc') {
					$wsIsOfflineCC=$row_pay[0];
				}
				echo "<OPTION VALUE=\"".$row_pay[0]."\" ".$selected.">".$row_pay[1];
			}
		}
		?>
		</SELECT> <?php
		?></td>
	</tr>
	<?php
	if (IsAdmin() && get_option('zing_webshop_pro') && $wsIsOfflineCC) {
		echo '<tr><td colspan="4">'.$txt['card1'].'</td></tr><tr>';
		$wsCreditCard=new wsCreditCard(wsCid());
		$i=0;
		$first=true;
		if (count($wsCreditCard->all()) > 0) {
			foreach ($wsCreditCard->all() as $cardid => $card) {
				$i++;
				if ($i > 4) {
					echo '</tr><tr>';
					$i=1;
				}
				echo '<td width="25%">';
				echo '<strong>'.$card['CARD_NAME'].'</strong><br />';
				echo $card['CARD_TYPE'].' ';
				echo $card['CARD_NUMBER'].'<br />';
				if ($_POST['wscardid'] == $cardid || ($_POST['wscardid']=='' && $first)) $selected = 'CHECKED'; else $selected="";
				echo '<input type="radio" name="wscardid" value="'.$cardid.'" '.$selected.'/>';
				if ($cardid > 0) {
					echo '<a href="index.php?zfaces=form&action=edit&form=bankcard&id='.$cardid.'&redirect='.urlencode('index.php?page=onecheckout&paymentid='.$paymentid.'&shipping='.$wsShipping).'" class="button">'.$txt['browse7'].'</a>';
					echo ' ';
					echo '<a href="index.php?zfaces=form&action=delete&form=bankcard&id='.$cardid.'&redirect='.urlencode('index.php?page=onecheckout&paymentid='.$paymentid.'&shipping='.$wsShipping).'" class="button">'.$txt['browse8'].'</a>';
				}
				echo '</td>';
				$first=false;
			}
		}
		echo '<tr><td colspan="4">';
		echo '<a href="index.php?zfaces=form&action=add&form=bankcard&redirect='.urlencode('index.php?page=onecheckout&paymentid='.$paymentid.'&shipping='.$wsShipping).'" class="button">'.$txt['shippingadmin10'].'</a>';
		echo '</td></tr>';
	}
	?>
	<?php
	if (WeighCart(wsCid()) > 0) {
		echo '<tr><td colspan="4">'.$txt['customer21'].'</td></tr><tr>';
		$address=new wsAddress(wsCid());
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
	<?php if (ActiveDiscounts()) {?>
<table>
	<tr>
		<td><?php echo $txt['shipping5']?> <input type="text" id="discount_code" name="discount_code"
			value="<?php echo $discount_code?>"
		><input type="submit" name="discount" value="<?php echo $txt['cart10'];?>"
			onclick="this.form.action='<?php zurl('?page=onecheckout',true)?>';this.form.submit();"
		/></td>
	</tr>
</table>
<?php }?> <?php //}

//shipping end
// read basket
$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (`CUSTOMERID` = ".wsCid()." AND `STATUS` = 0) ORDER BY ID";
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
	$tax = new wsTaxSum();

	while ($row = mysql_fetch_array($sql)) {
		$id++;
		$query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
		$sql_details = mysql_query($query) or die(mysql_error());
		while ($row_details = mysql_fetch_array($sql_details)) {
			$optel = $optel +1;
			if ($optel == 3) { $optel = 1; }
			if ($optel == 1) { $kleur = ""; }
			if ($optel == 2) { $kleur = " class=\"altrow\""; }

			// is there a picture?
			if ($search_prodgfx == 1 && $use_prodgfx == 1) {
				if (!empty($row_details['DEFAULTIMAGE'])) { $picture = $row_details['DEFAULTIMAGE']; }
				elseif ($pictureid == 1) { $picture = $row_details[0]; }
				else { $picture = $row_details[1]; }

				list($image_url,$height,$width)=wsDefaultProductImageUrl($picture,$row_details['DEFAULTIMAGE']);
				$thumb = "<img class=\"imgleft\" src=\"".$image_url."\"".$width.$height." alt=\"\" />";
			}

			// make up the description to print according to the pricelist_format and max_description
			$print_description=printDescription($row_details[1],$row_details[3],$row_details['EXCERPT']);
			?>
	<tr <?php echo $kleur; ?>>
		<td colspan="2"><a
			href="<?php zurl("index.php?page=details&prod=".$row_details[0].'&basketid='.$row['ID'],true); ?>"
		><?php echo $thumb.$print_description.$picturelink; ?></a> <?php
		//$productprice = $row[3]; // the price of a product
		$tax->calculate($row[3],$row_details['TAXCATEGORYID']);
		$productprice = $tax->in;

		$printvalue = $row[7];   // features
		if (!$printvalue == "") { echo "<br />(".$printvalue.")"; }
		?></td>
		<td style="text-align: right"><?php 
		echo $currency_symbol_pre;
		$subtotaal = $productprice * $row[6];
		$printprijs = myNumberFormat($subtotaal);
		echo $printprijs;
		echo $currency_symbol_post;
		?></td>
		<td style="text-align: right;"><input type="text" size="4"
			name="numprod[<?php echo $row['ID'];?>]" value="<?php echo $row[6] ?>"
		>&nbsp; <input type="submit" value="<?php echo $txt['cart10'] ?>"
			onclick="form.action='<?php zurl("?page=onecheckout&action=update&prodid=".$row_details[0].'&basketid='.$row['ID'],true)?>';"
			name="sub"
		> <br />
		<input type="submit" value="<?php echo $txt['cart6']; ?>"
			onclick="form.action='<?php zurl("?page=onecheckout&action=delete&prodid=".$row_details[0].'&basketid='.$row['ID'],true)?>';"
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
		$discount->calculate();
		echo '<tr><td colspan="2" style="text-align: right">'.$txt['checkout14'];
		if ($discount->percentage>0) {
			// percentage
			echo ' '.$discount->percentage.'%</td><td style="text-align: right">-'.$currency_symbol_pre.myNumberFormat($discount->discount,$number_format).$currency_symbol_post.'</td></tr>';
		}
		else {
			//fixed amount
			echo '</td><td style="text-align: right">-'.$currency_symbol_pre.myNumberFormat($discount->discount,$number_format).$currency_symbol_post.'</td></tr>';
		}
		$discountValue=$discount->discount;
	} else {
		$discountValue=0;
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
	} else $sendcosts=0;
	
	if ($sendcosts != 0) {
		echo '<tr><td>'.$txt['checkout16'].'</td><td>'.$shipping_descr.'</td><td style="text-align: right">'.$currency_symbol_pre.myNumberFormat($sendcosts,$number_format).$currency_symbol_post.'</td></tr>';
	}
	$shippingTax=new wsTax($sendcosts,wsSetting('SHIPPING_TAX_CATEGORY'));
	
	//calculate and display taxes
	$totaal_ex = $tax->exSum + $shippingTax->ex - $discountValue;
	$totaal_in = $tax->inSum + $shippingTax->in - $discountValue;
	
	if (!$db_prices_including_vat) displayTaxes(array($tax,$shippingTax),$txt['checkout102']);
	
	//total
	?>
	<tr>
		<td colspan="2">
		<div style="text-align: right;"><strong><?php echo $txt['cart7']; ?></strong></div>
		</td>
		<td>
		<div style="text-align: right;"><?php echo $currency_symbol_pre.myNumberFormat($totaal_in).$currency_symbol_post; ?><br />
		<?php if ($no_vat == 0) { echo "<small>(".$currency_symbol_pre.$totaal_ex.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>"; } ?></div>
		</td>
	</tr>
	<?php
	if ($db_prices_including_vat) displayTaxes(array($tax,$shippingTax),$txt['checkout102']);
	?>
</table>
<br />
	<?php //notes
	echo $txt['shipping3'];
	?><br />
<textarea name="notes" rows="5" style="width: 100%"><?php echo $notes;?></textarea><br />
<br />
<br />
<br />
<input type="hidden" name="onecheckout" value="1" /> <input type="checkbox" name="conditions"
<?php if (IsAdmin() || $conditions) echo 'checked="yes"'?>
/> <a href="<?php zurl('index.php?page=conditions&action=show',true)?>"><?php echo $txt['conditions1'];?></a><br />
<div style="text-align: center;"><input type=submit name=pay value="<?php echo $txt['cart9'] ?>"></div>
</form>

<?php
}
}?>