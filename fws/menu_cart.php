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

	   echo "<h1>".$txt['menu2']."</h1>\n"; 
	   echo "<ul id=\"navlist\">\n";
	   echo "<li"; if ($page == "cart") { echo " id=\"active\""; }; echo "><a href=\"?page=cart&action=show\">".$txt['cart5'].": ".CountCart($customerid)."<br />";
	   echo $txt['cart7'].": ".$currency_symbol_pre.myNumberFormat(CalculateCart($customerid), $number_format).$currency_symbol_post."</a></li>";
	   echo "</ul>";
?>