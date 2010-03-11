<?php
global $zing;
$zing=new zing();
$zing->paths[]=dirname(__FILE__).'/fws/';
//$zing->addModule("hosting");

/*
foreach ($zing->modules as $module) {
	require(dirname(__FILE__).'/module-'.$module.'/index.php');
}
*/

class zing {
	var $modules=array();
	var $types=array('inc' => 'includes', 'class' => 'classes');
	var $paths=array();
	var $dashboardWidgets=array();
	
	function zing() {

	}

	function addModule($module) {
		$this->modules[]=$module;
		$this->paths[]=dirname(__FILE__).'/module-'.$module.'/';
	}
	
	function addToDashboard($widget) {
		$this->dashboardWidgets[]=$widget;
	}
}