<?php
function aphpsAutoLoader($class) {
	global $aphps_projects;
	
	$c=explode('_',$class,2);
	$path='';
	if (count($c) == 2) {
		
		$path=isset($aphps_projects[$c[0]]['dir']) ? $aphps_projects[$c[0]]['dir'].'classes/'.strtolower($c[1]).'.class.php' : dirname(__FILE__).'/'.$c[0].'/classes/'.strtolower($c[1]).'.class.php';
	}
	if (file_exists($path)) require($path);
}

spl_autoload_register('aphpsAutoLoader');
