<?php
/*  readorder.php
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
if (!empty($_GET['orderid'])) {
	$orderid=intval($_GET['orderid']);
}
?>
<?php

// lets check if the order you are trying to read is REALLY your own order
$query = sprintf("SELECT * FROM `".$dbtablesprefix."order` WHERE ID = %s", quote_smart($orderid));
$sql = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_array($sql)) {
	$webid = $row[7];
	$ownerid = $row[5];
}
if ($ownerid != $customerid && IsAdmin() == false) {
	PutWindow($gfx_dir, $txt['general12'] , $txt['general2'], "warning.gif", "50");
}
else {
	//download links if any
	if ($row['STATUS'] == '5' || $row['STATUS'] == '6' || IsAdmin()) {
		$linkhtml="";
		$query="SELECT * FROM `".$dbtablesprefix."basket` WHERE `ORDERID`=".quote_smart($orderid);
		$sql_basket = mysql_query($query) or die(mysql_error());
		while ($row_basket = mysql_fetch_array($sql_basket)) {
			$query_prod="SELECT * FROM `".$dbtablesprefix."product` WHERE `LINK` IS NOT NULL AND `ID`=".quote_smart($row_basket['PRODUCTID']);
			$sql_prod = mysql_query($query_prod) or die(mysql_error());
			if ($row_prod = mysql_fetch_array($sql_prod)) {
				$linkhtml.='<a href="'.ZING_URL.'fws/download.php?basketid='.$row_basket['ID'].'&abspath='.ABSPATH.'">'.$row_prod['PRODUCTID'].'</a><br/>';
			}
		}
		if ($linkhtml) {
			echo '<table width="100%" class="datatable">';
			echo '<caption>'.$txt['readorder100'].'</caption>';
			echo '<tr><td>'.$linkhtml.'<br /></td></tr></table>';
		}
	}

	//read order details
	$fp = fopen($orders_dir."/".$webid.".php", "rb") or die($txt['general6']);
	$ordertext = fread($fp, filesize($orders_dir."/".$webid.".php"));
	list($security, $order) = split("\?>", $ordertext);
	fclose($fp);

	// if there are linebreaks, then we have a new order. if not, then it's an old one that needs nl2br
	$pos = strpos ($order, "<br />");
	if ($pos === false) { $order = nl2br($order); }
	?>
<table width="100%" class="datatable">
	<caption><?php echo $webid; ?></caption>
	<tr>
		<td><?php echo $order; ?></td>
	</tr>
</table>
<h4><a href="?page=printorder&orderid=<?php echo $orderid ?>"><?php echo $txt['readorder1'] ?></a><br />
<a href="javascript:history.go(-1)"><?php echo $txt['readorder2'] ?></a></h4>
	<?php } ?>