<?php
/*  browse.php
 Copyright 2006, 2007, 2008 Elmar Wenners
 Support site: http://www.chaozz.nl

 This file is part of UltraShop.

 UltraShop is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 UltraShop is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with UltraShop; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
if ($_GET['includesearch']) $includesearch=$_GET['includesearch'];
elseif ($_POST['includesearch']) $includesearch=$_POST['includesearch'];
if ($includesearch) {
	require(dirname(__FILE__).'/search.php');
}
$searchmethod = " AND "; //default

if (!empty($_POST['searchmethod'])) {
	$searchmethod=$_POST['searchmethod'];
}
if (!empty($_GET['searchmethod'])) {
	$searchmethod=$_GET['searchmethod'];
}
if (!empty($_POST['searchfor'])) {
	$searchfor=$_POST['searchfor'];
}
if (!empty($_GET['searchfor'])) {
	$searchfor=$_GET['searchfor'];
}
if (!empty($_GET['orderby'])) {
	$orderby = $_GET['orderby'];
}
if ($orderby == 1) {
	$orderby_field = "PRODUCTID";
}
else { $orderby_field = "PRICE"; }
?>

<?php
if (!empty($cat)){
	// find the product category
	$query = sprintf("SELECT * FROM `".$dbtablesprefix."category` where `ID`=%s", quote_smart($cat));
	$sql = mysql_query($query) or die(mysql_error());

	while ($row = mysql_fetch_row($sql)) {
		$categorie = $row[1];
	}
}
else {
	$categorie = $txt['browse1'] . " / " . $searchfor;
}

// products per page
if ($products_per_page > 0) {
	if (!empty($_GET['num_page'])) {
		$num_page = $_GET['num_page'];
	}
	else { $num_page = 1; }
	$start_record = ($num_page -1) * $products_per_page;
	$limit    = " LIMIT $start_record, $products_per_page";
}
else { $limit = ""; }
?>

<table width="100%" class="datatable">
	<tr>
		<th><?php 
		echo $txt['browse2']." / ".$categorie;
		echo "<br />";
		if ($action == "list") { echo "<a href=\"index.php?page=browse&action=list&group=$group&cat=$cat&orderby=1\"><small>".$txt['browse4']."</small></a>";  }
		?></th>
		<?php if ($ordering_enabled) {?>
		<th><?php 
		echo "<div style=\"text-align:right;\">";
		echo $txt['browse3'];
		// if we use VAT, then display that the prices are including VAT in the list below
		if ($no_vat == 0) { echo " (".$txt['general7']." ".$txt['general5'].")"; }
		echo "<br />";
		if ($action == "list") { echo "<a href=\"index.php?page=browse&action=list&group=$group&cat=$cat&orderby=2\"><small>".$txt['browse4']."</small></a>";  }
		echo "</div>";
		?></th>
		<?php }?>
	</tr>
	<?php

	if ($action == "list") {
		$query = "SELECT * FROM `".$dbtablesprefix."product` ";
		if ($stock_enabled == 1 && $hide_outofstock == 1 && IsAdmin() == false) { // filter out products with stock lower than 1
			$query = sprintf("SELECT * FROM `".$dbtablesprefix."product` where `STOCK` > 0 AND `CATID`=%s ORDER BY `$orderby_field` ASC", quote_smart($cat));
		}
		elseif (!empty($cat)) {
			$query = sprintf("SELECT * FROM `".$dbtablesprefix."product` WHERE CATID=%s ORDER BY `$orderby_field` ASC", quote_smart($cat));
		}
	}
	elseif ($action == "shownew") {
		if ($stock_enabled == 1 && IsAdmin() == false) { // filter out products with stock lower than 1
			$query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `STOCK` > 0 AND `NEW` = '1' ORDER BY `$orderby_field` ASC";
		}
		else { $query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `NEW` = '1' ORDER BY `$orderby_field` ASC"; }
	}
	else {
		//search on the given terms
		if ($searchfor != "") {
			$searchitem = explode (" ", $searchfor);
			if ($stock_enabled == 1) { $searchquery = "WHERE `STOCK` > 0 AND ("; }
			else $searchquery = "WHERE (";

			$counter = 0;
			while (!$searchitem[$counter] == NULL){
				$searchquery .= "((DESCRIPTION LIKE '%" . $searchitem[$counter] . "%') OR (PRODUCTID LIKE '%" . $searchitem[$counter] . "%'))";
				$counter += 1;
				if (!$searchitem[$counter] == NULL) { $searchquery .= " ".$searchmethod." "; }
			}
			$searchquery .= ")";
		}
		else {
			//$searchquery = "WHERE (DESCRIPTION = 'never_find_me')";
			$searchquery = " ";
		} // just to cause that the searchresult is empty
		$query = "SELECT * FROM `".$dbtablesprefix."product` $searchquery ORDER BY `$orderby_field` ASC";
		$limit="";
	}

	// total products without the limit
	$sql = mysql_query($query) or die(mysql_error());
	$num_products = mysql_num_rows($sql);

	// products optionally with the limit
	$sql = mysql_query($query.$limit) or die(mysql_error());
	if (mysql_num_rows($sql) == 0) {
		echo "<tr><td>".$txt['browse5']."</td><td>&nbsp;</td></tr></table>";
	}
	else {
			
		$optel = 0;

		if ($searchfor) {
			$rows=wsOrderByRelevance($sql,$query,$searchitem,$searchmethod,$start_record,$orderby_field);
		} else {
			$rows=array();
			while ($row = mysql_fetch_array($sql)) {
				$rows[]=$row;
			}
		}
		foreach ($rows as $row) {
			$optel++;
			if ($optel == 3) { $optel = 1; }
			if ($optel == 1) { $kleur = ""; }
			if ($optel == 2) { $kleur = " class=\"altrow\""; }

			// the price gets calculated here
			$printprijs = $row[4]; // from the database
			if ($db_prices_including_vat == 0 && $no_vat == 0) { $printprijs = $row[4] * $vat; }
			$printprijs = myNumberFormat($printprijs); // format to our settings

			// reset values
			$picturelink = "";
			$new = "";
			$thumb = "";
			$stocktext = "";

			// new product?
			if ($row[7] == 1) { $new = "<font color=\"red\"><strong>" . $txt['general3']. "</strong></font>"; }

			// is there a picture?
			if ($search_prodgfx == 1 && $use_prodgfx == 1) {
					
				if ($pictureid == 1) { $picture = $row[0]; }
				else { $picture = $row[1]; }
					
				// determine resize of thumbs
				$width = "";
				$height = "";
				if ($pricelist_thumb_width != 0) { $width = " width=\"".$pricelist_thumb_width."\""; }
				if ($pricelist_thumb_height != 0) { $height = " height=\"".$pricelist_thumb_height."\""; }
				if (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/".$picture.".jpg\"".$width.$height." alt=\"\" />"; }
				if (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/".$picture.".gif\"".$width.$height." alt=\"\" />"; }
				if (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/".$picture.".png\"".$width.$height." alt=\"\" />"; }
					
				// if the script uses make_thumbs, then search for thumbs
				if ($make_thumbs == 1) {
					if (!empty($row['DEFAULTIMAGE']) && thumb_exists($product_dir ."/". $row['DEFAULTIMAGE'])) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/".$row['DEFAULTIMAGE']."\" alt=\"\" />"; }
					elseif (thumb_exists($product_dir ."/tn_". $picture . ".jpg")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/tn_".$picture.".jpg\" alt=\"\" />"; }
					elseif (thumb_exists($product_dir ."/tn_". $picture . ".gif")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/tn_".$picture.".gif\" alt=\"\" />"; }
					elseif (thumb_exists($product_dir ."/tn_". $picture . ".png")) { $thumb = "<img class=\"imgleft\" src=\"".$product_url."/tn_".$picture.".png\" alt=\"\" />"; }
				}
					
				if ($thumb != "" && $thumbs_in_pricelist == 0) {
					// use a photo icon instead of a thumb
					$picturelink = "<a href=\"".$product_dir."/".$picture.".jpg\"><img src=".$gfx_dir."/photo.gif></a>";
					$thumb = "";
				}
			}

			// see if you are an admin. if so, add a [EDIT] link to the line
			$admin_edit = "";
			if (IsAdmin() == true) {
				$admin_edit = "<br /><br />";
				if ($stock_enabled == 1) { $admin_edit .= $txt['productadmin12'].": ".$row[5]."<br />"; }
				$admin_edit .= "<a href=\"?page=productadmin&action=edit_product&pcat=".$cat."&prodid=".$row[0]."\">".$txt['browse7']."</a>";
				$admin_edit .= " | <a href=\"?page=productadmin&action=delete_product&pcat=".$cat."&prodid=".$row[0]."\" onclick=\"return confirm('".$txt['generic1']."?');\">".$txt['browse8']."</a>";
				if (is_admin()) {
					$admin_edit .= " | ".$txt['productadmin14'].' <input id="wsfp'.$row[0].'" type="checkbox" class="wsfrontpage" onclick="wsFrontPage('.$row[0].',this.checked);"';
					if ($row['FRONTPAGE']) $admin_edit.=" checked";
					$admin_edit.='>';
				}
			}
			// make up the description to print according to the pricelist_format and max_description
			$print_description=printDescription($row[1],$row[3]);

			echo "<tr".$kleur.">";

			// see what the stock is
			if ($stock_enabled == 0) {
				if ($row[5] == 1) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_stock1']."\" /> "; } // in stock
				if ($row[5] == 0) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_stock2']."\" /> "; } // out of stock
				if ($row[5] == 2) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_stock3']."\" /> "; } // in backorder
			}
			else {
				$stockpic = "";
				if ($hide_outofstock == 0 && $row[5] == 0) { $row[4] = 0; }
				if (IsAdmin() == FALSE && $show_stock == 1) {
					$stocktext = "<br /><small>".$txt['browse13'].": ".$row[5]."</small>";
				}
			}

			echo "<td>".$stockpic;
			if (!is_admin()) echo "<a class=\"plain\" href=\"index.php?page=details&prod=".$row[0]."&cat=".$row[2]."&group=".$group."\">".$thumb.$print_description."</a> ";
			else echo $thumb.$print_description;
			echo $picturelink." ".$new." ".$stocktext.$admin_edit."</td>";
			if ($ordering_enabled) {
				echo "<td><div style=\"text-align:right;\">";
				if ($order_from_pricelist) {
					?>
	<form id="order<?php echo $row[0];?>" method="POST" action="?page=cart&action=add">
	<div style="text-align: right"><input type="hidden" id="prodid" name="prodid"
		value="<?php echo $row[0] ?>"
	> <input type="hidden" name="prodprice" value="<?php echo $row[4] ?>"> <?php
	if (!$row[4] == 0) {
		$tax=new wsTax($row[4]);
		if ($no_vat == 1) {
			echo "<big><strong>". $currency_symbol_pre.$tax->inFtd.$currency_symbol_post."</strong></big>";
		}
		else {
			echo "<big><strong>".$currency_symbol_pre.$tax->inFtd.$currency_symbol_post."</strong></big>";
			echo "<br /><small>(".$currency_symbol_pre.$tax->exFtd.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>";
		}

		// product features
		$allfeatures = $row[8];
		if (!empty($allfeatures)) {
			$features = explode("|", $allfeatures);
			$counter1 = 0;
			echo "<br /><br />";
			while (!$features[$counter1] == NULL){
				if (strpos($features[$counter1],":")===FALSE){echo "<br />".$features[$counter1].":  <input type=\"text\" name=\"".$features[$counter1]."\"> ";$counter1 += 1;}
				else {
					$feature = explode(":", $features[$counter1]);
					$counter1 += 1;
					echo "<br />".$feature[0].": ";
					echo "<select name=\"".$feature[0]."\">";
					$value = explode(",", $feature[1]);
					$counter2 = 0;
					while (!$value[$counter2] == NULL){

						// optionally you can specify the additional costs: color:red+1.50,green+2.00,blue+3.00 so lets deal with that
						$extracosts = explode("+",$value[$counter2]);
						if (!$extracosts[1] == NULL) {
							// there are extra costs
							$printvalue = $extracosts[0]." (+".$currency_symbol_pre.myNumberFormat($extracosts[1],$number_format).$currency_symbol_post.")";
						}
						else {
							$printvalue = $value[$counter2];
						}

						// print the pulldown menu
						$printvalue = str_replace("+".$currency_symbol_pre."-", "-".$currency_symbol_pre, $printvalue);
						echo "<option value=\"".$value[$counter2]."\""; if ($counter2 == 0) { echo " SELECTED"; } echo ">".$printvalue;
						$counter2 += 1;
					}
					echo "</select>";
				}
			}
		}

		?> <br />
	<br />
	<?php }
	if (!$includesearch) {
		if (!$row['LINK']) {
			echo $txt['details6'] ?>:<br />
	<input type="text" size="4" name="numprod" value="1" maxlength="4">&nbsp; <?php }?> <input
		type="<?php if (ZING_PROTOTYPE || ZING_JQUERY) echo 'button'; else echo 'submit';?>"
		class="addtocart" id="addtocart" value="<?php echo $txt['details7'] ?>" name="sub"
	> <?php
	}
	if ($row[4] == 0) {
		if ($row[5] == 0 && $hide_outofstock == 0 && $stock_enabled != 2) { echo '<strong><big>'.$txt['browse12'].'</big></strong>'; }
	}
	?>
	
	</form>
	<?php
				}
				else { echo "<big><strong>".$currency_symbol."&nbsp;".$printprijs."</strong></big>"; }
				echo "</div></td>";
			}
			echo "</tr>";
		} ?>
</table>
		<?php if (!$includesearch) {?>
<div style="text-align: right;"><img src="<?php echo $gfx_dir ?>/photo.gif" alt="" /> <em><small><?php echo $txt['browse6'] ?></small></em></div>
		<?php
		}
		// page code
		if ($products_per_page > 0 && $num_products > $products_per_page) {

	  $page_counter = 0;
	  $num_pages = 0;
	  $rest_products = $num_products;
	  $page_range=3;

	  echo "<br /><h4>".$txt['browse11'].": ";

	  if ($num_page > $page_range) {
	  	echo "<a href=\"".zurl('index.php?page=browse&action=$action&group=$group&cat=$cat&orderby=$orderby&searchmethod=$searchmethod&searchfor=$searchfor&num_page=1&includesearch=$includesearch')."\">[1]</a>";
	  }
	  if ($num_page > $page_range + 1) echo ' ...';

	  for($i = 0; $i < $num_products; $i++) {
		  $page_counter++;
		  if ($page_counter == $products_per_page) {
			  $num_pages++;
			  $page_counter = 0;
			  $rest_products = $rest_products - $products_per_page;
			  if ($num_pages == $num_page) {
				  echo "<b>[$num_pages]</b>";
			  }
			  elseif (($num_pages-$num_page <= $page_range) && ($num_pages-$num_page >= -$page_range)) { echo "<a href=\"".zurl('index.php?page=browse&action=$action&group=$group&cat=$cat&orderby=$orderby&searchmethod=$searchmethod&searchfor=$searchfor&num_page=$num_pages&includesearch=$includesearch')."\">[$num_pages]</a>"; }
			  echo " ";
		  }
	  }
	  if ($num_pages - $num_page > $page_range) echo '... ';
	  // the rest (if any)
	  if ($rest_products > 0) {
		  $num_pages++;
		  if ($num_pages == $num_page) {
			  echo "<b>[$num_pages]</b>";
		  }
		  else { echo "<a href=\"".zurl('index.php?page=browse&action=$action&group=$group&cat=$cat&orderby=$orderby&searchmethod=$searchmethod&searchfor=$searchfor&num_page=$num_pages&includesearch=$includesearch')."\">[$num_pages]</a>"; }
	  }

	  echo "</h4>";
		}
		?>
		<?php
		if ($stock_enabled == 0 && !is_admin()) {
			?>
<br />
<br />
<table width="50%" class="datatable">
	<caption><?php echo $txt['db_stock10'] ?></caption>
	<tr>
		<td><?php echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_stock1']."\" />"; ?></td>
		<td><?php echo $txt['db_stock11']; ?></td>
	</tr>
	<tr>
		<td><?php echo "<img src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_stock2']."\" />"; ?></td>
		<td><?php echo $txt['db_stock12']; ?></td>
	</tr>
	<tr>
		<td><?php echo "<img src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_stock3']."\" />"; ?></td>
		<td><?php echo $txt['db_stock13']; ?></td>
	</tr>
</table>
			<?php
		}
	}
	function wsOrderByRelevance($sql,$query,$searchitems,$searchmethod,$start_record,$orderby_field) {
		global $products_per_page;

		$i=0;
		$rows=array();
		$allrows=array();
		while ($row = mysql_fetch_array($sql)) {
			$search_quotient = 0;
			foreach ($searchitems as $term) {
				if ($searchmethod!="AND") $search_quotient=0;
				$term=strtolower($term);
				$sdes=substr_count(strtolower($row['PRODUCTID']),$term);
				$ldes=substr_count(strtolower($row['DESCRIPTION']),$term);
				if ($sdes == 0 && $ldes == 1) $search_quotient += 1 ;
				elseif ($sdes == 0 && $ldes > 1) $search_quotient += 2 ;
				elseif ($sdes >= 1 && $ldes == 0) $search_quotient += 3 ;
				elseif ($sdes >= 1 && $ldes == 1) $search_quotient += 4 ;
				elseif ($sdes >= 1 && $ldes >= 1) $search_quotient += 5 ;
			}
			$key=sprintf("%04d",$search_quotient).'_';
			if ($orderby_field=="PRICE") $key.=sprintf("%09d",$row[$orderby_field]*1000);
			else $key.=sprintf("%s",$row[$orderby_field]);
			$key.='_'.sprintf("%09d",$i);
			//echo '<br />'.$key;
			$allrows[$search_quotient.'.'.$i]=$row;
			$i++;
		}
		krsort($allrows);
		$i=0;
		foreach ($allrows as $id => $row) {
			if ($i >= $start_record && $i < ($start_record+$products_per_page)) $rows[$id]=$row;
			$i++;
		}
		return $rows;
	}
	if (ZING_PROTOTYPE && !is_admin()) {
		?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	document.observe("dom:loaded", function() {
	    wsFrontPage=false;
		cart=new wsCart();
		cart.order();
	});
//]]>
</script>
		<?php } elseif (ZING_JQUERY && !is_admin()) {?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	jQuery(document).ready(function() {
	    wsFrontPage=false;
		cart=new wsCart();
		cart.order();
	});
//]]>
</script>
		<?php }?>