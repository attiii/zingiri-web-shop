<?php
/*  zingiri_webshop.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Web Shop.

 Zingiri Web Shop is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Web Shop is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
$searchfor=$_POST['searchfor'];
if (empty($searchfor)) exit;

/** Loads the WordPress Environment */
require(dirname(__FILE__).'/../../../../../wp-blog-header.php');

/** Load Zingiri Web Shop */
require(dirname(__FILE__).'/../../zing.readcookie.inc.php');
require(dirname(__FILE__).'/../../zing.startmodules.inc.php');

/** Run search results */
$results="";
if ($searchfor) {
	$searchitem = explode (" ", $searchfor);
	if ($stock_enabled == 1) { $searchquery = "WHERE `STOCK` > 0 AND ("; }
	else $searchquery = "WHERE (";

	$counter = 0;
	while (!$searchitem[$counter] == NULL){
		$searchquery .= "((`DESCRIPTION` LIKE '%" . $searchitem[$counter] . "%') OR (`PRODUCTID` LIKE '%" . $searchitem[$counter] . "%'))";
		$counter += 1;
		if (!$searchitem[$counter] == NULL) { $searchquery .= " ".$searchmethod." "; }
	}
	$searchquery .= ")";
	$query = "SELECT `ID`,`PRODUCTID` FROM `".$dbtablesprefix."product` $searchquery ORDER BY `PRODUCTID` ASC LIMIT 10";
	$sql = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_array($sql)) {
		$results.='<li><a href="'.zurl('index.php?page=details&prod='.$row['ID']).'">'.substr($row['PRODUCTID'],0,20).'</a></li>';	
	}
}

/** Display search results */
if (!empty($results)) {
	echo '<ul>';
	echo $results;
	echo '</ul>';
} else {
	echo $txt['browse5'];
}
?>