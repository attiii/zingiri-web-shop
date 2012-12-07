<?php if ($index_refer <> 1) { exit(); } ?>

<?php

if (isset($_POST['featuresets'])) {
	$featureSets=intval($_POST['featuresets']);
} else $featureSets=1;

if (!empty($_REQUEST['prodid'])) $prodid=intval($_REQUEST['prodid']);

if ($action=='add') {
	if (!empty($_REQUEST['basketid'])) $basketid=$_REQUEST['basketid'];
	if (isset($_REQUEST['numprod'])) $numprod=$_REQUEST['numprod'];
	else $numprod[]=1;
} else {
	if (!empty($_REQUEST['basketid'])) $basketid=intval($_REQUEST['basketid']);
	if (isset($_REQUEST['numprod'])) $numprod=intval($_REQUEST['numprod']);
}

// current date
$today = getdate();
$error = 0; // no errors found

if ($action=="add") {
	
	// if we work with stock amounts, then lets check if there is enough in stock
	if ($stock_enabled == 1) {
		// if you have 2 of product x in basket and stock is 2, you get an error if you try to add 1 more
		for ($i=0;$i<$featureSets;$i++) {
			if (isset($basketid[$i])) {
				$query = "SELECT `QTY` FROM `".$dbtablesprefix."basket` WHERE `CUSTOMERID` = ".qs(wsCid())." AND `ID` = ".qs($basketid[$i])." AND `STATUS` = 0";
				$sql = mysql_query($query) or die(mysql_error());
				if (mysql_num_rows($sql) != 0) {
					$row = mysql_fetch_row($sql);
					$num_in_basket = $row[0];
				}
			} else { $num_in_basket = 0; }
	
			$query = sprintf("SELECT `STOCK` FROM `".$dbtablesprefix."product` WHERE `ID` = %s", qs($prodid));
			$sql = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_row($sql);
			//ebo
			$numordered = $numprod[$i] + $num_in_basket;
			if ($numordered > $row[0] || $row[0] == 0) { // you're ordering more then whats in stock , or stock is 0
				$warning = $txt['checkout15']."<br /><br />".$txt['checkout7']." ".$numordered."<br />".$txt['checkout8']." ".$row[0];
				PutWindow($gfx_dir, $txt['general12'], $warning, "warning.gif", "50");
				$error = 1;
			}
		}
	}
	if (!$error) {
		// product features
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."product` WHERE `ID` = %s", quote_smart($prodid));
		$sql = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_array($sql);

		$prodprice = 0;
		$allfeatures = $row['FEATURES'];
		$productfeatures = "";
		$uniqueSet=isset($_POST['featuresuniqueset']) ? intval($_POST['featuresuniqueset']) : CreateRandomCode(10).time();

		for ($i=0;$i<$featureSets;$i++) {
			//features
			$wsFeatures=new wsFeatures($allfeatures,$row['FEATURESHEADER'],$row['FEATURES_SET']);

			//verify features
			//$wsFeatures->prepare($i);
			
			//calculate price
			$wsFeatures->calcPrice($i,wsPrice::price($row['PRICE']),isset($row['PRICE_FORMULA_TYPE']) ? $row['PRICE_FORMULA_TYPE'] : '',isset($row['PRICE_FORMULA_RULE']) ? $row['PRICE_FORMULA_RULE'] : '');
			
			$productfeatures=$wsFeatures->featureString;
			$prodprice+=$wsFeatures->price;
			$qty=isset($numprod[$i]) ? $numprod[$i] : 1;

			if (isset($basketid[$i])) {
				if ($numprod==0) $query = "DELETE FROM `".$dbtablesprefix."basket` WHERE `ID` = ".qs($basketid[$i])." AND `CUSTOMERID` = ".qs(wsCid());
				else $query = "UPDATE `".$dbtablesprefix."basket` SET `QTY` = ".qs($qty).",`FEATURES`=".qs($productfeatures).",`PRICE`=".qs($prodprice)." WHERE `ID` = ".qs($basketid[$i])." AND `CUSTOMERID` = ".qs(wsCid())." AND STATUS = 0";
			} else {
				$query="SELECT `ID` FROM `".$dbtablesprefix."basket` WHERE `CUSTOMERID`=".qs(wsCid())." AND `STATUS`=0 AND `PRODUCTID`=".qs($prodid)." AND `FEATURES`=".qs($productfeatures);
				$sql = mysql_query($query) or zfdbexit($query);
				if (mysql_num_rows($sql)>0) {
					$row = mysql_fetch_array($sql);
					$query = "UPDATE `".$dbtablesprefix."basket` SET `QTY` = ".qs($qty)." WHERE `ID` = ".qs($row['ID']);
				} else {
					$query = "INSERT INTO `".$dbtablesprefix."basket` ( `CURRENCY` , `SET` ,`CUSTOMERID` , `PRODUCTID` , `PRICE` , `ORDERID` , `LINEADDDATE` , `QTY` , `FEATURES`) VALUES (".qs(wsPrice::ccy()).", ".qs($uniqueSet).", '".wsCid()."', '".$prodid."', '".$prodprice."', '0', '".Date("d-m-Y @ G:i")."', '".$qty."', '".$productfeatures."')";
				}
			}
			$sql = mysql_query($query) or die(mysql_error());
		}
	}
}
elseif ($action=="update"){
	if ($numprod == 0) {
		$query = "DELETE FROM `".$dbtablesprefix."basket` WHERE `ID` = '". $basketid."' AND `STATUS` = '0' AND `CUSTOMERID` = " . wsCid();
		$sql = mysql_query($query) or die(mysql_error());
	}
	else {
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
			$query = "UPDATE `".$dbtablesprefix."basket` SET `QTY` = ".$numprod." WHERE `ID` = ".$basketid;
			$sql = mysql_query($query) or die(mysql_error());
		}
	}
}
elseif ($action=="empty"){
	$query = "DELETE FROM ".$dbtablesprefix."basket WHERE `CUSTOMERID` = " . wsCid(). " AND `STATUS`=0";
	$sql = mysql_query($query) or die(mysql_error());
}

// read basket
$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (`CUSTOMERID` = ".wsCid()." AND `STATUS` = 0) ORDER BY ID";
$sql = mysql_query($query) or zfdbexit($query);
$count = mysql_num_rows($sql);

if ($count == 0) {
	PutWindow($gfx_dir, '', $txt['cart2'], "carticon.gif", "50");
}
else {
	?>

<table width="100%" class="datatable">
	<tr>
		<th><?php echo $txt['cart3']; ?></th>
		<th><?php echo $txt['cart4']; ?></th>
		<th><?php echo $txt['cart5']; ?></th>
	</tr>

	<?php
	$optel = 0;
	$tax=new wsTaxSum();
	$totaal=0;
	
	while ($row = mysql_fetch_array($sql)) {
		$query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
		$sql_details = mysql_query($query) or die(mysql_error());
		while ($row_details = mysql_fetch_array($sql_details)) {
			$optel = $optel +1;
			if ($optel == 3) { $optel = 1; }
			if ($optel == 1) { $kleur = ""; }
			if ($optel == 2) { $kleur = " class=\"altrow\""; }

			// is there a picture?
			if ($search_prodgfx == 1 && $use_prodgfx == 1) {

				if ($pictureid == 1) { $picture = $row_details[0]; }
				else { $picture = $row_details[1]; }

				list($image_url,$height,$width)=wsDefaultProductImageUrl($picture,$row_details['DEFAULTIMAGE']);
				$thumb = "<img class=\"imgleft\" src=\"".$image_url."\"".$width.$height." alt=\"\" />";
				
			}

			// make up the description to print according to the pricelist_format and max_description
			$print_description=printDescription($row_details[1],$row_details[3],$row_details['EXCERPT']);
			?>
	<tr <?php echo $kleur; ?>>
		<td><a
			href="index.php?page=details&prod=<?php echo $row_details[0]; ?>&basketid=<?php echo $row['ID']; ?>"
		><?php echo $thumb.$print_description; ?></a> <?php
		$productprice = $row[3]; // the price of a product
		if (!empty($row[7])) {
			$wsFeatures=new wsFeatures($row[7],$row_details['FEATURESHEADER'],$row_details['FEATURES_SET']);
			$printvalue = $wsFeatures->toString();   // features
			if (!$printvalue == "") { echo "<br />(".$printvalue.")"; }
		}
		?></td>
		<td><?php 
		echo wsPrice::currencySymbolPre();
		$subtotaal = $productprice * $row[6];
		$tax->calculate($subtotaal,$row_details['TAXCATEGORYID']);
		$subtotaal = $tax->in;
		$printprijs = wsPrice::format($subtotaal);
		echo $printprijs;
		echo wsPrice::currencySymbolPost();
		?></td>
		<td><?php if (!$row_details['LINK']) {?>
		<form method="POST" action="?page=cart&action=update"><input type="hidden" name="prodid"
			value="<?php echo $row_details[0] ?>"
		/> <input type="hidden" name="basketid" value="<?php echo $row[0] ?>" />
		<div style="text-align: right;"><input type="text" size="4" name="numprod"
			value="<?php echo $row[6] ?>"
		/>&nbsp; <input type="submit" value="<?php echo $txt['cart10'] ?>" name="sub" /></div>
		</form>
		<?php }?>
		<form method="POST" action="?page=cart&action=update"><input type="hidden" name="prodid"
			value="<?php echo $row_details[0] ?>"
		> <input type="hidden" name="basketid" value="<?php echo $row[0] ?>"> <input type="hidden"
			name="numprod" value="0"
		>
		<div style="text-align: right;"><input type="submit" value="<?php echo $txt['cart6']; ?>"
			name="sub"
		></div>
		</form>
		</td>
	</tr>
	<?php

	$totaal = $totaal + $subtotaal;
		}
	}

	?>
	<tr>
		<td colspan="3">
		<div style="text-align: right;"><strong><?php echo $txt['cart7']; ?></strong> <?php echo wsPrice::currencySymbolPre().wsPrice::format($totaal).wsPrice::currencySymbolPost(); ?><br />
		<?php if ($no_vat == 0) { 
			echo "<small>(".wsPrice::currencySymbolPre().wsPrice::format($tax->exSum).wsPrice::currencySymbolPost()." ".$txt['general6']." ".$txt['general5'].")</small>"; 
		}
		?></div>
		</td>
	</tr>
</table>
<br />
<br />
<div style="text-align: center;">

<table class="borderless" width="50%">
	<tr>
		<td nowrap>
		<form method="post" action="?page=cart&action=empty"><input type="submit"
			value="<?php echo $txt['cart8']; ?>"
		></form>
		</td>
		<td nowrap><?php
		// if the conditions page is disabled, then we might as well skip it ;)
		if ($ordering_enabled == 1) { 
			echo "<form method=\"post\" action=\"".zurl("index.php?page=checkout1")."\">"; 
		 	echo "<input type=\"submit\" value=\"".$txt['cart9']."\">"; 
		 	echo '</form>'; 
		}
		?>
		</td>
		<?php
		if ($action=="add") {
			echo "<td nowrap>";
			// lets find out the category of the last added product
			$query = "SELECT `CATID` FROM `".$dbtablesprefix."product` WHERE `ID` = '".$prodid."'";
			$sql = mysql_query($query) or die(mysql_error());

			while ($row = mysql_fetch_row($sql)) {
				$jump2cat = $row[0];
			}
			echo "<form method=\"post\" action=\"".zurl("index.php?page=browse&action=list&cat=".$jump2cat)."\">";
			echo "<input type=\"submit\" value=\"".$txt['cart12']."\">";
			echo "</form>";
			echo "</td>";

		}
		?>
	</tr>
</table>
</div>
<?php
}
?>