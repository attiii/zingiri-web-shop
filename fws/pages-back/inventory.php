<?php if ($index_refer <> 1) { exit(); } ?>
<?php
if (IsAdmin() == false) {
	PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
} else {

	if (!isset($group)) $group='';
	$wsProductDisplayType=isset($_COOKIE['fws_displaytype']) ? aphpsSanitize($_COOKIE['fws_displaytype']) : 'list';

	if (isset($_GET['itemsperpage'])) {
		if ($_COOKIE['fws_itemsperpage'] != $_GET['itemsperpage']) {
			setcookie ("fws_itemsperpage",intval($_GET['itemsperpage']), 0, '/');
		}
		$products_per_page=$_GET['itemsperpage'];
	} elseif (isset($_COOKIE['fws_itemsperpage'])) $products_per_page=aphpsSanitize($_COOKIE['fws_itemsperpage']);

	if ($_GET['includesearch']) $includesearch=$_GET['includesearch'];
	elseif ($_POST['includesearch']) $includesearch=$_POST['includesearch'];
	require(dirname(__FILE__).'/inventory.search.php');
	$searchmethod = "AND"; //default

	if (!empty($_POST['searchmethod'])) {
		$searchmethod=$_POST['searchmethod'];
	}
	if (!empty($_GET['searchmethod'])) {
		$searchmethod=$_GET['searchmethod'];
	}
	if (!empty($_POST['searchfor'])) {
		$searchfor=$_POST['searchfor'];
	} elseif (!empty($_GET['searchfor'])) {
		$searchfor=$_GET['searchfor'];
	} else $searchfor='';
	if (!empty($_GET['orderby'])) {
		$orderby = $_GET['orderby'];
	}
	if ($orderby == 1) {
		$orderby_field = "PRODUCTID";
	}
	else { $orderby_field = "PRICE"; }

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
	if (IsAdmin()) {
		echo "<br /><a href=\"".zurl("?page=product&zfaces=form&form=product&action=add&zfp=62")."\"><img src=\"".ZING_APPS_PLAYER_URL."images/add.png\" height=\"16px\" />"."</a>";
		echo "<a href=\"".zurl("?page=product&zfaces=form&form=product&action=add&zfp=62")."\">".$txt['productadmin16']."</a>";
		echo '<br />';
	}

	if ($action == "list") {
		$query = "SELECT * FROM `".$dbtablesprefix."product` ";
		if ($stock_enabled == 1 && $hide_outofstock == 1 && wsIsAdminPage() == false) { // filter out products with stock lower than 1
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
		if ($searchfor || $searchCategory || $searchGroup) {
			if ($stock_enabled == 1) { $searchquery = "WHERE `STOCK` > 0"; }
			else $searchquery = "WHERE 1=1";
			if ($searchfor) {
				$searchForQuery='';
				$searchitem = explode (" ", $searchfor);
				$counter = 0;
				while (!$searchitem[$counter] == NULL){
					if (wsExtension('ml')) $searchForQuery.=wsMultiLingualQuery('search');
					else $searchForQuery .= "((DESCRIPTION LIKE '%" . $searchitem[$counter] . "%') OR (PRODUCTID LIKE '%" . $searchitem[$counter] . "%'))";
					$counter += 1;
					if (!$searchitem[$counter] == NULL) { $searchForQuery .= " ".$searchmethod." "; }
				}
				$searchquery.=' AND ('.$searchForQuery.')';
			}
			if ($searchCategory) $searchquery.=' AND (`CATID`='.$searchCategory.')'; 
			elseif ($searchGroup) $searchquery.=" AND (`CATID` IN (SELECT `ID` FROM `".$dbtablesprefix."category` WHERE `GROUPID`=".$searchGroup."))"; 
			//$searchquery .= ")";
		}
		else {
			//$searchquery = "WHERE (DESCRIPTION = 'never_find_me')";
			$searchquery = " ";
		} // just to cause that the searchresult is empty
		$query = "SELECT * FROM `".$dbtablesprefix."product` $searchquery ORDER BY `$orderby_field` ASC";
		$limit="";
	}

	//manage stack
	require_once(ZING_APPS_PLAYER_DIR.'classes/stack.class.php');
	$stack=new zfStack('list','inventory');

	// total products without the limit
	$sql = mysql_query($query) or die(mysql_error());
	$num_products = mysql_num_rows($sql);

	// products optionally with the limit
	$sql = mysql_query($query.$limit) or die(mysql_error());
	if (mysql_num_rows($sql) == 0) {
		PutWindow($gfx_dir, $txt['general13'], $txt['browse5'].($categorie ? ': ' : '').$categorie, "notify.gif", "50");
	}
	else {
		if ($searchfor) {
			$rows=wsOrderByRelevance($sql,$query,$searchitem,$searchmethod,$start_record,$orderby_field);
		} else {
			$rows=array();
			while ($row = mysql_fetch_array($sql)) {
				$rows[]=$row;
			}
		}
		//table header
		echo '<div style="position:relative;float:left">';
		if (strpos($txt['editsettings91'],'<a')) echo substr($txt['editsettings91'],0,strpos($txt['editsettings91'],'<a'));
		else echo $txt['editsettings91'];
		echo '<select name="itemsperpage" onChange="document.location.href=\''.wsCurrentPageURL().'&itemsperpage=\'+this.options[this.selectedIndex].value'.';">';
		$wsItemsPerPageValues=array(10,15,25,50);
		foreach ($wsItemsPerPageValues as $i) {
			echo '<option value="'.$i.'" '.($products_per_page==$i ? 'selected="selected"' : '').'>'.$i.'</option>';
		}
		echo '</select>';
		echo '</div>';
		echo '<div style="position:relative;clear:both"></div>';

		if ($wsProductDisplayType=='list') {
			echo '<table width="100%" class="datatable">';
			echo '<tr>';
			echo '<th>';
			echo $txt['browse2']." / ".$categorie;
			echo "<br />";
			if ($action == "list") { echo "<a href=\"".zurl('?page=inventory&action=list&group='.$group.'&cat='.$cat.'&orderby=1')."\"><small>".$txt['browse4']."</small></a>";  }
			echo '</th>';
			if ($ordering_enabled) {
				echo '<th>';
				echo "<div style=\"text-align:right;\">";
				echo $txt['browse3'];
				// if we use VAT, then display that the prices are including VAT in the list below
				if ($no_vat == 0) { echo " (".$txt['general7']." ".$txt['general5'].")"; }
				echo "<br />";
				if ($action == "list") { echo "<a href=\"".zurl('?page=inventory&action=list&group='.$group.'&cat='.$cat.'&orderby=2')."\"><small>".$txt['browse4']."</small></a>";  }
				echo "</div>";
				echo '</th>';
			}
			echo '</tr>';
		} else {
			echo '<table width="100%" class="borderless" style="width:100%">';
		}
		if ($prods_per_row = wsSetting('productsperrow')); else $prods_per_row=3;
		$row_count = 0;
		foreach ($rows as $row) {

			//start row
			$row_count++;

			//if ($wsProductDisplayType=='list') echo wsShowProductRow($row);
			//else echo wsShowProductCell($row,$row_count,$prods_per_row);
			echo wsShowInventoryRow($row);

			if ($row_count == $prods_per_row) { $row_count = 0; }
			//end row display
		}
		echo '</table>';
		// page code
		if ($products_per_page > 0 && $num_products > $products_per_page) {

			$page_counter = 0;
			$num_pages = 0;
			$rest_products = $num_products;
			$page_range=3;

			echo "<br /><h4>".$txt['browse11'].": ";

			if ($num_page > $page_range) {
				echo "<a href=\"".zurl('index.php?page=inventory&action='.$action.'&group='.$group.'&cat='.$cat.'&orderby='.$orderby.'&searchmethod='.$searchmethod.'&searchfor='.$searchfor.'&num_page=1&includesearch='.$includesearch)."\">[1]</a>";
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
					elseif (($num_pages-$num_page <= $page_range) && ($num_pages-$num_page >= -$page_range)) { echo "<a href=\"".zurl('index.php?page=inventory&action='.$action.'&group='.$group.'&cat='.$cat.'&orderby='.$orderby.'&searchmethod='.$searchmethod.'&searchfor='.$searchfor.'&num_page='.$num_pages.'&includesearch='.$includesearch)."\">[$num_pages]</a>"; }
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
				else { echo "<a href=\"".zurl('index.php?page=inventory&action='.$action.'&group='.$group.'&cat='.$cat.'&orderby='.$orderby.'&searchmethod='.$searchmethod.'&searchfor='.$searchfor.'&num_page='.$num_pages.'&includesearch='.$includesearch)."\">[$num_pages]</a>"; }
			}

			echo "</h4>";
		}
		if ($stock_enabled == 0 && !wsIsAdminPage()) {
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
if (!wsIsAdminPage() && !ZING_LIVE) {
	?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	jQuery(document).ready(function() {
	    wsFrontPage=false;
		wsCart.order();
	});
//]]>
</script>
	<?php }?>
	<?php
	function wsShowInventoryRow($row) {
		global $use_prodgfx,$prods_per_row,$currency_symbol_pre,$currency_symbol_post,$txt,$product_url,$product_dir,$pictureid;
		global $db_prices_including_vat,$search_prodgfx,$use_prodgfx,$stock_enabled,$gfx_dir,$hide_outofstock,$show_stock;
		global $ordering_enabled,$order_from_pricelist,$includesearch,$currency_symbol,$no_vat,$optel;

		$output='';

		$optel++;
		if ($optel == 3) { $optel = 1; }
		if ($optel == 1) { $kleur = ""; }
		if ($optel == 2) { $kleur = " class=\"altrow\""; }

		// the price gets calculated here
		$printprijs = $row[4]; // from the database
		//if ($db_prices_including_vat == 0 && $no_vat == 0) { $printprijs = $row[4] * $vat; }
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

			list($image_url,$height,$width)=wsDefaultProductImageUrl($picture,$row['DEFAULTIMAGE']);
			$thumb = "<img class=\"imgleft\" src=\"".$image_url."\"".$width.$height." alt=\"\" />";
		}
		// see if you are an admin. if so, add a [EDIT] link to the line
		$admin_edit = "";
		if (IsAdmin() == true) {
			$admin_edit = "<br /><br />";
			if ($stock_enabled == 1) { $admin_edit .= $txt['productadmin12'].": ".$row[5]."<br />"; }
			if (wsIsAdminPage()) {
				$admin_edit .= "<a href=\"".zurl("?page=product&zfaces=form&form=product&action=edit&zfp=62&id=".$row[0])."\">".$txt['browse7']."</a>";
				$admin_edit .= " | <a href=\"".zurl("?page=product&zfaces=form&form=product&action=delete&zfp=62&id=".$row[0])."\" >".$txt['browse8']."</a>";
				$admin_edit .= " | ".$txt['productadmin14'].' <input id="wsfp'.$row[0].'" type="checkbox" class="wsfrontpage" onclick="wsFrontPage('.$row[0].',this.checked);"';
				if ($row['FRONTPAGE']) $admin_edit.=" checked";
				$admin_edit.='>';
			} elseif (ZING_CMS=='wp' && IsAdmin()) {
				$admin_edit .= "<a href=\"".zurl("?page=product&zfaces=form&form=product&action=edit&id=".$row[0]."&redirect=".wsCurrentPageURL(true))."\">".$txt['browse7']."</a>";
				$admin_edit .= " | <a href=\"".zurl("?page=product&zfaces=form&form=product&action=delete&id=".$row[0]."&redirect=".wsCurrentPageURL(true))."\" >".$txt['browse8']."</a>";
			}
		}
		// make up the description to print according to the pricelist_format and max_description
		$print_description=printDescription($row[1],$row[3],$row['EXCERPT']);

		$output.= "<tr".$kleur.">";

		// see what the stock is
		if ($stock_enabled == 0) {
			if ($row[5] == 1) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_stock1']."\" /> "; } // in stock
			if ($row[5] == 0) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_stock2']."\" /> "; } // out of stock
			if ($row[5] == 2) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_stock3']."\" /> "; } // in backorder
		}
		else {
			$stockpic = "";
			if ($hide_outofstock == 0 && $row[5] == 0 && !wsIsAdminPage()) { $row[4] = 0; }
			if (wsIsAdminPage() == FALSE && $show_stock == 1) {
				$stocktext = "<br /><small>".$txt['browse13'].": ".$row[5]."</small>";
			}
		}

		$output.= "<td>".$stockpic;
		//if (!wsIsAdminPage() && !ZING_LIVE) $output.= "<a class=\"plain\" href=\"".zurl("index.php?page=details&prod=".$row[0]."&cat=".$row[2]."&group=".$group)."\">".$thumb.$print_description."</a> ";
		//else 
		$output.= $thumb.$print_description;
		$output.= $picturelink." ".$new." ".$stocktext.$admin_edit."</td>";
		if ($ordering_enabled) {
			$output.= "<td><div style=\"text-align:right;\">";
			if ($order_from_pricelist) {
				$output.= '<div style="text-align: right"><input type="hidden" id="prodid" name="prodid" value="'.$row[0].'">';
				$output.= '<input type="hidden" name="prodprice" value="'.$row[4].'">';
				if (!$row[4] == 0) {
					$tax=new wsTax($row[4],$row['TAXCATEGORYID']);
					if ($no_vat == 1) {
						$output.= "<big><strong>". $currency_symbol_pre.'<span class="wspricein" id="wsprice'.$row[0].'">'.$tax->inFtd.'</span>'.$currency_symbol_post."</strong></big>";
					}
					else {
						$output.= "<big><strong>".$currency_symbol_pre.'<span class="wspricein" id="wsprice'.$row[0].'">'.$tax->inFtd.'</span>'.$currency_symbol_post."</strong></big>";
						$output.= "<br /><small>(".$currency_symbol_pre.'<span class="wspriceex" id="wsprice'.$row[0].'">'.$tax->exFtd.'</span>'.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>";
					}

					// product features
					$allfeatures = $row[8];
					$wsFeatures=new wsFeatures($allfeatures,$row['FEATURESHEADER'],$row['FEATURES_SET'],$row[0]);

					$output.= '<input type="hidden" name="featuresets" value="'.$wsFeatures->sets.'" />';
					if (count($wsFeatures->prefil)>0) {
						for ($i=0;$i<$wsFeatures->sets;$i++) {
							$output.= '<input type="hidden" name="basketid[]" value="'.$wsFeatures->prefil[$i]['id'].'" />';
						}
					}
					$output.= '<div style="clear:both"></div>';
					$output.= '<div class="wsfeatures">';
					$output.= '<table class="'.$wsFeatures->tableClass.'">';
					$output.=$wsFeatures->displayFeatures(false);

					$output.= '</table>';
					$output.= '</div>';
					$output.= '<div style="clear:both"></div>';
				}
				if ($row[4] == 0) {
					if ($row[5] == 0 && $hide_outofstock == 0 && $stock_enabled != 2) { $output.= '<strong><big>'.$txt['browse12'].'</big></strong>'; }
				}
			}
			else { $output.= "<big><strong>".$currency_symbol."&nbsp;".$printprijs."</strong></big>"; }
			$output.= "</div></td>";
		}
		$output.= "</tr>";

		return $output;
	}
	?>