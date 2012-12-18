<?php
function aphpsAutoLoader($class) {
	$c=explode('_',$class);
	$path='';
	if (count($c) == 2) {
		$path=dirname(dirname(__FILE__)).'/'.$c[0].'/classes/'.strtolower($c[1]).'.class.php';
	}
	if (file_exists($path)) require($path);
}

spl_autoload_register('aphpsAutoLoader');
