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
function statistics_widget() {

	global $txt;

	//statistics
	echo '<div id="dashboard_statistics" class="dashboard">';
	$stats=new dashboardStats($txt['dashboard3']);
	$stats->add($txt['admin3'],"select count(*) as `result` from ##customer",'#');
	$stats->add($txt['admin2'],"select count(*) as `result` from ##order",'orderadmin');
	$stats->add($txt['dashboard4'],"select sum(`topay`) as `result` from ##order where `status` > '0'",'#','amount');
	$stats->add($txt['dashboard5'],"select avg(`topay`) as `result` from ##order where `status` > '0'",'#','amount');
	$stats->display();
	echo '</div>';

}
?>