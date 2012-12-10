<?php if ($index_refer <> 1) { exit(); } ?>
<?php
if (!isset($group)) $group='';
if (isset($_GET['displaytype']) && ($_GET['displaytype']=='list' || $_GET['displaytype']=='grid')) {
	if (!isset($_COOKIE['fws_displaytype']) || ($_COOKIE['fws_displaytype'] != $_GET['displaytype'])) {
		setcookie ("fws_displaytype",aphpsSanitize($_GET['displaytype']), 0, '/');
	}
	$wsProductDisplayType=$_GET['displaytype'];
} else $wsProductDisplayType=isset($_COOKIE['fws_displaytype']) ? aphpsSanitize($_COOKIE['fws_displaytype']) : 'list';

if (isset($_GET['itemsperpage'])) {
	if ($_COOKIE['fws_itemsperpage'] != $_GET['itemsperpage']) {
		setcookie ("fws_itemsperpage",intval($_GET['itemsperpage']), 0, '/');
	}
	$products_per_page=$_GET['itemsperpage'];
} elseif (isset($_COOKIE['fws_itemsperpage'])) $products_per_page=aphpsSanitize($_COOKIE['fws_itemsperpage']);
else $products_per_page='10';

if (isset($_GET['includesearch']) && $_GET['includesearch']) $includesearch=aphpsSanitize($_GET['includesearch']);
elseif (isset($_POST['includesearch']) && $_POST['includesearch']) $includesearch=aphpsSanitize($_POST['includesearch']);
else $includesearch='';
if ($includesearch) {
	require(dirname(__FILE__).'/search.php');
}
$searchmethod = "AND"; //default

if (!empty($_POST['searchmethod'])) {
	$searchmethod=aphpsSanitize($_POST['searchmethod']);
}
if (!empty($_GET['searchmethod'])) {
	$searchmethod=aphpsSanitize($_GET['searchmethod']);
}
if (!empty($_POST['searchfor'])) {
	$searchfor=aphpsSanitize($_POST['searchfor']);
} elseif (!empty($_GET['searchfor'])) {
	$searchfor=aphpsSanitize($_GET['searchfor']);
} else $searchfor='';
$orderby=isset($_GET['orderby']) ? aphpsSanitize($_GET['orderby']) : 0;
if ($orderby == 1) $orderby_field = "PRODUCTID";
else $orderby_field = "PRICE";

if (empty($cat) && isset($_GET['cat'])) {
	$cat=aphpsSanitize($_GET['cat']);
}
if (empty($cat) && isset($_GET['kat'])) {
	$cat=aphpsSanitize($_GET['kat']);
}
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
if ($action == "search" || $searchfor) {
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
		$searchquery = " ";
	} // just to cause that the searchresult is empty
	$query = "SELECT * FROM `".$dbtablesprefix."product` $searchquery ORDER BY `$orderby_field` ASC";
	$limit="";
} elseif ($action == "shownew") {
	if ($stock_enabled == 1 && IsAdmin() == false) { // filter out products with stock lower than 1
		$query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `STOCK` > 0 AND `NEW` = '1' ORDER BY `$orderby_field` ASC";
	}
	else { $query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `NEW` = '1' ORDER BY `$orderby_field` ASC"; }
} else {
	
	if ($stock_enabled == 1 && $hide_outofstock == 1 && wsIsAdminPage() == false) $query = sprintf("SELECT * FROM `".$dbtablesprefix."product` where `STOCK` > 0 AND `CATID`=%s ORDER BY `$orderby_field` ASC", quote_smart($cat));
	elseif (!empty($cat)) $query = sprintf("SELECT * FROM `".$dbtablesprefix."product` WHERE CATID=%s ORDER BY `$orderby_field` ASC", quote_smart($cat));
	else $query = "SELECT * FROM `".$dbtablesprefix."product` ORDER BY `$orderby_field` ASC"; 
}

// total products without the limit
$sql = mysql_query($query) or die(mysql_error());
$num_products = mysql_num_rows($sql);

// products optionally with the limit
$sql = mysql_query($query.$limit) or die(mysql_error());
if (mysql_num_rows($sql) == 0) {
	PutWindow($gfx_dir, $txt['general13'], $txt['browse5'].($categorie ? ': ' : '').$categorie, "notify.gif", "50");
}
else {
	if ($searchfor && !$orderby) {
		$rows=wsOrderByRelevance($sql,$query,$searchitem,$searchmethod,$start_record,$orderby_field);
	} else {
		$rows=array();
		while ($row = mysql_fetch_array($sql)) {
			$rows[]=$row;
		}
	}
	if (class_exists('wsMultiCurrency')) {
		$mc=new wsMultiCurrency();
		$mc->currencySelector();
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
	echo '<div style="position:relative;float:right">';
	echo '<a href="'.wsCurrentPageURL().'&displaytype=list" alt="'.$txt['browse100'].'">'.$txt['browse100'].'</a>';
	echo ' <a href="'.wsCurrentPageURL().'&displaytype=grid" alt="'.$txt['browse101'].'">'.$txt['browse101'].'</a>';
	echo '</div>';
	echo '<div style="position:relative;clear:both"></div>';
	
	if ($wsProductDisplayType=='list') {
		echo '<table width="100%" class="datatable">';
		echo '<tr>';
		echo '<th>';
		echo $txt['browse2']." / ".$categorie;
		echo "<br />";
		if ($action == "list") { echo "<a href=\"".zurl('?page=browse&action=list&group='.$group.'&cat='.$cat.'&orderby=1')."\"><small>".$txt['browse4']."</small></a>";  }
		echo '</th>';
		if ($ordering_enabled) {
			echo '<th>';
			echo "<div style=\"text-align:right;\">";
			echo $txt['browse3'];
			// if we use VAT, then display that the prices are including VAT in the list below
			if ($no_vat == 0) { echo " (".$txt['general7']." ".$txt['general5'].")"; }
			echo "<br />";
			if ($action == "list") { echo "<a href=\"".zurl('?page=browse&action=list&group='.$group.'&cat='.$cat.'&orderby=2')."\"><small>".$txt['browse4']."</small></a>";  }
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

		if ($wsProductDisplayType=='list') echo wsShowProductRow($row);
		else echo wsShowProductCell($row,$row_count,$prods_per_row);

		if ($row_count == $prods_per_row) { $row_count = 0; }
		//end row display
	}
	echo '</table>';
	if (!$includesearch) {
		//echo '<div style="text-align: right;"><img src="'.$gfx_dir.'/photo.gif" alt="" /> <em><small>'.$txt['browse6'].'</small></em></div>';
	}
	// page code
	if ($products_per_page > 0 && $num_products > $products_per_page) {

		$page_counter = 0;
		$num_pages = 0;
		$rest_products = $num_products;
		$page_range=3;

		echo "<br /><h4>".$txt['browse11'].": ";

		if ($num_page > $page_range) {
			echo "<a href=\"".zurl('index.php?page=browse&action='.$action.'&group='.$group.'&cat='.$cat.'&orderby='.$orderby.'&searchmethod='.$searchmethod.'&searchfor='.$searchfor.'&num_page=1&includesearch='.$includesearch)."\">[1]</a>";
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
				elseif (($num_pages-$num_page <= $page_range) && ($num_pages-$num_page >= -$page_range)) { echo "<a href=\"".zurl('index.php?page=browse&action='.$action.'&group='.$group.'&cat='.$cat.'&orderby='.$orderby.'&searchmethod='.$searchmethod.'&searchfor='.$searchfor.'&num_page='.$num_pages.'&includesearch='.$includesearch)."\">[$num_pages]</a>"; }
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
			else { echo "<a href=\"".zurl('index.php?page=browse&action='.$action.'&group='.$group.'&cat='.$cat.'&orderby='.$orderby.'&searchmethod='.$searchmethod.'&searchfor='.$searchfor.'&num_page='.$num_pages.'&includesearch='.$includesearch)."\">[$num_pages]</a>"; }
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
if (!wsIsAdminPage()) {
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