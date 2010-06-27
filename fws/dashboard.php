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
require(dirname(__FILE__).'/../zap/includes/faces.inc.php');

if (IsAdmin()) {
	//start here
	$db=new db();
	$db->select("select dashboard from ##settings");
	$row=$db->next();
	$json=$db->get('dashboard');
	$sortorder=zf_json_decode($json);
	$temp1=array();

	# Include FusionCharts PHP Class
	require(dirname(__FILE__).'/addons/fusioncharts/Class/FusionCharts_Gen.php');
	echo '<script type="text/javascript" src="'.ZING_URL.'fws/addons/fusioncharts/FusionCharts/FusionCharts.js"></script>';

	echo '<table class="datatable" width="100%">';
	echo '<caption>'.$txt['admin102'].'</caption>';
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
	echo 'var mydashboard=new dashboard();';
	echo '</script>';

}


class dashboardStats {

	var $stats;
	var $caption;

	function __construct($caption) {
		$this->caption=$caption;
	}

	function add($key,$sql,$href,$format="") {
		global $currency_symbol_pre,$currency_symbol_post;
		$db=new db();
		$db->select($sql);
		$row=$db->next();
		if ($format=="amount") $value=$currency_symbol_pre.myNumberFormat($row['result']).$currency_symbol_post;
		else $value=$row['result'];
		$this->stats[$key]=array('href' => $href, 'value' => $value);
	}

	function display() {
		global $href,$txt;
		echo '<table class="dashboard">';
		echo '<caption>'.$this->caption.'</caption>';
		foreach ($this->stats as $key => $stat) {
			echo '<tr>';
			if ($stat['href']=="#") $h="javascript:void(0);";
			else $h='?page='.$stat['href'];
			echo '<td><a href="'.$h.'">'.$key.'</a></td>';
			echo '<td>'.$stat['value'].'</td>';
			echo '</tr>';
		}
		echo '</table>';
	}


}
function display_table($caption,$headers,$rows=null,$sql=null) {
	global $txt;

	echo '<table class="dashboard" width="100%">';
	echo '<caption>'.$caption.'</caption>';
	foreach ($headers as $header) {
		echo '<th>'.$header.'</th>';
	}

	if ($sql) {
		$db=new db();
		$db->select($sql);
		while ($row=$db->next()) {
			$rows[]=$row;
		}
	}
	if (count($rows) > 0) {
		foreach ($rows as $row) {
			echo '<tr>';
			foreach ($row as $field) {
				echo '<td>'.$field.'</td>';
			}
			echo '</tr>';
		}
	} else {
		echo '<tr><td>'.$txt['dashboard7'].'</td></tr>';
	}
	echo '</table>';

}


function paint_chart($caption,$x,$y,$data) {
	$max=10;
	$FC = new FusionCharts("Column2D","600","350");

	foreach ($data as $key => $value) {
		$max=max($max,$value);
		if ($value!=0) $FC->addChartData($value,"name=".$key);
	}
	$FC->setSWFPath(ZING_URL."fws/addons/fusioncharts/FusionCharts/");

	# Set chart attributes
	$strParam="showValues=0;caption=".$caption.";xAxisName=".$x.";yAxisName=".$y.";decimalPrecision=0;formatNumberScale=0;formatNumber=1;rotateNames=1";
	$strParam.=";yAxisMaxValue=".$max;
	$strParam.=";chartBottomMargin=100";
	//echo $strParam;
	$FC->setChartParams($strParam);
	if ($FC->setCounter) $FC->renderChart();
}
/*
 $FC = new FusionCharts("MSColumn3D","300","250");
 $FC->setSWFPath(ZING_URL."zhg/addons/fusioncharts/FusionCharts/");
 $strParam="caption=Weekly Sales;subcaption=Comparison;xAxisName=Week;yAxisName=Revenue;numberPrefix=$;decimalPrecision=0";

 # Set chart attributes
 $FC->setChartParams($strParam);
 # Add category names
 $FC->addCategory("Week 1");
 $FC->addCategory("Week 2");
 $FC->addCategory("Week 3");
 $FC->addCategory("Week 4");

 # Create a new dataset
 $FC->addDataset("This Month");
 # Add chart values for the above dataset
 $FC->addChartData("40800");
 $FC->addChartData("31400");
 $FC->addChartData("26700");
 $FC->addChartData("54400");

 # Create second dataset
 $FC->addDataset("Previous Month");
 # Add chart values for the second dataset
 $FC->addChartData("38300");
 $FC->addChartData("28400");
 $FC->addChartData("15700");
 $FC->addChartData("48100");

 $FC->renderChart();
 */

?>