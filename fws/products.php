<?php if ($index_refer <> 1) { exit(); } ?>

<?php
if (!empty($_POST['basketid'])) {
	$basketid=intval($_POST['basketid']);
}

// current date
$error = 0; // no errors found

if (IsAdmin() == true) {
	if (!empty($_GET['id']))
	{ $customerid = intval($_GET['id']); }
}

// read basket
$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (`CUSTOMERID` = ".$customerid." AND `STATUS` != 0) ORDER BY ID DESC";
$sql = mysql_query($query) or zfdbexit($query);
$count = mysql_num_rows($sql);

if ($count == 0) {
	PutWindow($gfx_dir, $txt['browse9'], $txt['browse5'], "products.gif", "50");
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

	while ($row = mysql_fetch_array($sql)) {
		$query = "SELECT * FROM `".$dbtablesprefix."order` where `ID`='" . $row['ORDERID'] . "'";
		$sql_details = mysql_query($query) or die(mysql_error());
		$row_order = mysql_fetch_array($sql_details);
				
		$query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
		$sql_details = mysql_query($query) or die(mysql_error());
		if ($row_details = mysql_fetch_array($sql_details)) {
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
			href="index.php?page=details&prod=<?php echo $row_details[0]; ?>"><?php echo $thumb.$print_description.$picturelink; ?></a>
			<?php
			$productprice = $row[3]; // the price of a product
			if ($row[7]) {
				$wsFeatures=new wsFeatures($row[7]);
				$wsFeatures->setDefinition($row_details['FEATURES']);
				echo "<br />(".$wsFeatures->toString($row[7]).")";
			}
			?></td>
		<td><?php 
		echo $currency_symbol_pre;
		$subtotaal = $productprice * $row[6];
		if ($no_vat == 0 && $db_prices_including_vat == 0) {
			$tax=new wsTax($subtotaal); 
			$subtotaal = $tax->in; 
		}
		$printprijs = myNumberFormat($subtotaal);
		echo $printprijs;
		echo $currency_symbol_post;
		?></td>
		<td style="text-align:center;">
		<?php echo $row[6];
			if ($row_details['LINK'] && ($row_order['STATUS']==5 || $row_order['STATUS']==6 || IsAdmin())) {
				echo '<br /><br />';
				?>
				<form method="POST" action="<?php echo ZING_URL;?>fws/download.php">
                <input type="hidden" name="basketid" value="<?php echo $row[0] ?>">
                <input type="hidden" name="abspath" value="<?php echo ABSPATH;?>">
                <input type="submit" value="<?php echo $txt['products1'] ?>" name="sub">
                </form>
				<?php 
			}	?>	</td>
	</tr>
	<?php

		}
	}
	?>
</table>
<br />
<br />
		<?php
}
?>