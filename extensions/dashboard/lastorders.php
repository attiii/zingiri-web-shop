<?php
/*  dashboard.php
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
function lastorders_widget() {
	global $txt;

	//lastorders
	echo '<div id="dashboard_lastOrders" class="dashboard">';
	$sql="select ##order.date,##customer.lastname,##order.topay from ##order,##customer where ##order.customerid=##customer.id order by ##order.id desc limit 5";
	$headers=array();
	$headers[]=$txt['errorlogadmin6'];
	$headers[]=$txt['customeradmin1'];
	$headers[]=$txt['cart7'];
	display_table($txt['dashboard6'],$headers,null,$sql);
	echo '</div>';

}
?>