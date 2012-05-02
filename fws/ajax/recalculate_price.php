<?php
/*  recalculate_price.php
 Copyright 2008,2009,2010 Erik Bogaerts
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
require(dirname(__FILE__).'/init.inc.php');
$wsProductId=intval($_POST['prodid']);
if (isset($_POST['featuresets'])) {
	$featureSets=intval($_POST['featuresets']);
} else $featureSets=1;

$query = sprintf("SELECT * FROM `".$dbtablesprefix."product` WHERE `ID` = %s", quote_smart($wsProductId));
$sql = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($sql);
$price = 0;

$wsFeatures=new wsFeatures($row['FEATURES'],$row['FEATURESHEADER'],$row['FEATURES_SET']);
for ($i=0;$i<$featureSets;$i++) {
	$wsFeatures->validate($i);
	$price+=$wsFeatures->calcPrice($i,$row['PRICE'],isset($row['PRICE_FORMULA_TYPE']) ? $row['PRICE_FORMULA_TYPE'] : null,isset($row['PRICE_FORMULA_RULE']) ? $row['PRICE_FORMULA_RULE'] : null);
}
$tax=new wsTax($price,$row['TAXCATEGORYID']);
$a['pricein']=$tax->inFtd;
$a['priceex']=$tax->exFtd;
if (isset($wsFeatures->f)) $a['f']=$wsFeatures->f;
if (isset($wsFeatures->p)) $a['p']=$wsFeatures->p;
if (isset($row['PRICE_FORMULA_RULE'])) $a['row']=$row['PRICE_FORMULA_RULE'];
$a['post']=$wsFeatures->post;
echo json_encode($a);
?>