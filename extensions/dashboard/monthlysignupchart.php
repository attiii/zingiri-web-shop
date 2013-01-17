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
function monthlysignupchart_widget() {
	global $txt,$dbtablesprefix;

	//Monthly signups chart
	echo '<script type="text/javascript" src="'.ZING_URL.'fws/addons/fusioncharts/FusionCharts/FusionCharts.js"></script>';
	echo '<div id="dashboard_monthlySignUpChart" class="dashboard">';
	$FC = new FusionCharts("MSColumn3D","350","220");
	$FC->setSWFPath(ZING_URL."fws/addons/fusioncharts/FusionCharts/");
	$strParam="caption=".$txt['dashboard1'].";xAxisName=".$txt['dashboard2'].";yAxisName=".$txt['cart5'].";decimalPrecision=0;formatNumberScale=1;rotateNames=1";
	$FC->setChartParams($strParam);
	$strQuery = "select concat(year(date_created),'-',month(date_created)) as `Month`,count(*) as `Quantity` from ".$dbtablesprefix."customer where date_created + interval 1 year > curdate() group by concat(year(date_created),'-',month(date_created)) order by date_created";
	$result = mysql_query($strQuery) or die("error");
	if (mysql_num_rows($result)) {
		$FC->addDatasetsFromDatabase($result, $txt['dashboard2'], $txt['cart5'].";");
		$FC->renderChart();
	}
	echo '</div>';
}
?>