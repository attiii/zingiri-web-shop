<?php
/*  menu_cart.php
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
<?php
$countCart=CountCart($customerid);
echo "<ul>";
echo "<li"; if ($page == "cart") { echo " id=\"active\""; }; echo "><a href=\"?page=cart&action=show\">".$txt['cart5'].": ".$countCart."<br />";
echo $txt['cart7'].": ".$currency_symbol_pre.myNumberFormat(CalculateCart($customerid), $number_format).$currency_symbol_post."</a></li>";
if ($countCart > 0 && ZING_PROTOTYPE)
{
	echo '<li id="showcart"><a href="javascript:void(0);">&#x25BE; ('.z_('show').')</a></li>';
	echo '<li id="hidecart"><a href="javascript:void(0);">&#x25B4; ('.z_('hide').')</a></li>';
}
$cart="";
$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (`CUSTOMERID` = ".$customerid." AND `STATUS` = 0) ORDER BY ID";
$sql = mysql_query($query) or zfdbexit($query);
while ($row = mysql_fetch_array($sql)) {
	$query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
	$sql_details = mysql_query($query) or die(mysql_error());
	if ($row_details = mysql_fetch_array($sql_details)) {
		$price=$row['PRICE']+calcFeaturesPrice($row['FEATURES']);
		$cart.='<li>';
		$cart.='<a style="display:inline" href="?page=details&prod='.$row[2].'">';
		$cart.=substr($row_details['PRODUCTID'],0,20).' ';
		$cart.='</a>';
		$cart.='<form style="display:inline" id="cart_update'.$row['ID'].'" method="POST" action="?page=cart&action=update">';
		$cart.='<input type="hidden" name="prodid" value="'.$row_details[0].'"/>';
		$cart.='<input type="hidden" name="basketid" value="'.$row[0].'"/>';
		$cart.='<input type="input" size="2" id="numprod" name="numprod" value="'.$row['QTY'].'" READONLY/> ';
		$cart.='<a style="display:inline" href="javascript:void(0);" onClick="sidebarcart.updateCart('.$row['ID'].',1);">';
		$cart.='&#x25B4;';
		$cart.='</a>';
		$cart.='<a style="display:inline" href="javascript:void(0);" onClick="sidebarcart.updateCart('.$row['ID'].',-1);">';
		$cart.='&#x25BE;';
		$cart.='</a>';
		$cart.='</form>';
		$cart.=' '.$currency_symbol_pre.myNumberFormat($price).$currency_symbol_post.' ';
		$cart.='<form style="display:inline" id="cart_remove'.$row['ID'].'" method="POST" action="?page=cart&action=update">';
		$cart.='<input type="hidden" name="prodid" value="'.$row_details[0].'"/>';
		$cart.='<input type="hidden" name="basketid" value="'.$row[0].'"/>';
		$cart.='<input type="hidden" name="numprod" value="0" />';
		$cart.='<a style="display:inline" href="javascript:void(0);" onClick="sidebarcart.removeFromCart('.$row['ID'].');">';
		$cart.='<img align="absmiddle" src="'.ZING_URL.'fws/templates/default/images/warning.gif" height="16px" />';
		$cart.='</a>';
		$cart.='</form>';
		$cart.='</li>';
	}
}
echo '</ul>';
if (!empty($cart)) {
	echo '<div id="shoppingcart"><ul>';
	echo $cart;
	echo '</ul></div>';
}
if ($countCart > 0) {
	echo '<ul>';
	echo '<li><a href="?page=conditions&action=checkout">'.$txt['menu3'].'</a></li>';
	echo '</ul>';
}

if (ZING_PROTOTYPE) {
	?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	document.observe("dom:loaded", function() {
          sidebarcart=new wsCart();
          sidebarcart.contents();
	});
//]]>
</script>
	<?php }?>