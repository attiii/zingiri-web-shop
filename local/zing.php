<?php
global $zing;

if (!class_exists('zing')) {
	class zing {
		var $modules=array();
		var $types=array('inc' => 'includes', 'class' => 'classes');
		var $paths=array();
		var $dashboardWidgets=array();
		var $extensions=array();

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
}

if (!isset($zing)) {
	$zing=new zing();
}

