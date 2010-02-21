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
$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (`CUSTOMERID` = ".$customerid." AND `ORDERID` != 0) ORDER BY ID DESC";
$sql = mysql_query($query) or zfdbexit($query);
$count = mysql_num_rows($sql);

if ($count == 0) {
	PutWindow($gfx_dir, $txt['browse9'], $txt['browse5'], "products.gif", "50");
}
else {
	?>

<table width="100%" class="datatable">
	<caption><?php echo $txt['menu15'] ?></caption>
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
			if ($pricelist_format == 0) { $print_description = $row_details[1]; }
			if ($pricelist_format == 1) { $print_description = $row_details[3]; }
			if ($pricelist_format == 2) { $print_description = $row_details[1]." - ".$row_details[3]; }
			if ($max_description != 0) {
				$description = stringsplit($print_description, $max_description); // so lets only show the first xx characters
				if (strlen($print_description) != strlen($description[0])) { $description[0] = $description[0] . ".."; }
				$print_description = $description[0];
			}
			$print_description = strip_tags($print_description); //remove html because of danger of broken tags
			?>
	<tr <?php echo $kleur; ?>>
		<td><a
			href="index.php?page=details&prod=<?php echo $row_details[0]; ?>"><?php echo $thumb.$print_description.$picturelink; ?></a>
			<?php
			$productprice = $row[3]; // the price of a product
			$printvalue = $row[7];   // features
			if (!$printvalue == "") { echo "<br />(".$printvalue.")"; }
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