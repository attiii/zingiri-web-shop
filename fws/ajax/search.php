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
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');
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
	$searchitems = explode (" ", $searchfor);
	if ($stock_enabled == 1) { $searchquery = "WHERE `STOCK` > 0 AND ("; }
	else $searchquery = "WHERE (";

	foreach ($searchitems as $searchitem){
		$searchquery .= "((`DESCRIPTION` LIKE '%" . $searchitem . "%') OR (`PRODUCTID` LIKE '%" . $searchitem . "%'))";
	}
	$searchquery .= ")";
	$query = "SELECT `ID`,`PRODUCTID`,`DESCRIPTION` FROM `".$dbtablesprefix."product` $searchquery ORDER BY `PRODUCTID` ASC LIMIT 10";
	$sql = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_array($sql)) {
		foreach (array('PRODUCTID','DESCRIPTION') as $field) {
			$data=$row[$field];
			foreach ($searchitems as $searchitem) {
				if (($pos=stripos($data,$searchitem)) !== false) {
					$start=0;
					$end=strlen($data);
					for ($i=$pos; $i >= 0; $i--) {
						if ($data[$i]==" ") { $start=$i; break; }
					}
					for ($i=$pos; $i <= strlen($data); $i++) {
						if ($data[$i]==" ") { $end=$i; break; }
					}
					$word=trim(substr($data,$start,$end-$start));
					//$results.='<li>'.substr($data,$start,$end-$start).'-'.$pos.'-'.$start.'-'.$end.'</li>';
					$results.='<li><a href="'.zurl('index.php?page=browse&searchfor='.$word).'">'.$word.'</a></li>';
				}
			}
		}
		//		$results.='<li><a href="'.zurl('index.php?page=details&prod='.$row['ID']).'">'.substr($row['PRODUCTID'],0,20).'</a></li>';
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