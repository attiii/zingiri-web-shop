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
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
require(dirname(__FILE__).'/../'.ZING_APPS_EMBED.'/includes/faces.inc.php');

if (IsAdmin()) {
	//start here
	$db=new db();
	$db->select("select dashboard from ##settings");
	$row=$db->next();
	$json=$db->get('dashboard');
	$sortorder=zf_json_decode($json);
	$temp1=array();

	# Include FusionCharts PHP Class
	require_once(dirname(__FILE__).'/addons/fusioncharts/Class/FusionCharts_Gen.php');
	echo '<script type="text/javascript" src="'.ZING_URL.'fws/addons/fusioncharts/FusionCharts/FusionCharts.js"></script>';

	echo '<table class="datatable" width="100%">';
	echo '</table>';

	echo '<div style="width:100%">';
	echo '<ul id="zdashboard">';
	if (count($sortorder) > 0) {
		foreach ($sortorder as $f) {
			$temp1[]=$f;
		}
	}
	if (count($zing->dashboardWidgets) > 0) {
		foreach ($zing->dashboardWidgets as $f) {
			if (in_array($f,$temp1) === false) {
				$temp1[]=trim($f);
			}
		}
	}
	foreach ($temp1 as $f) {
		if (function_exists($f)) $f();
	}
	//wrap it up
	echo '</ul>';
	echo '</div>';
	echo '<script type="text/javascript" language="javascript">';
	echo 'jQuery(document).ready(function() {';
	echo 'dashboard.initialize();';
	echo '});';
	echo '</script>';

}





