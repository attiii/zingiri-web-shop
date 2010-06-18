<?php
zing_carousel_init();

function zing_carousel_init($name='') {
	$name=get_option('zing_ws_carousel');
	define('ZING_CAROUSEL_URL',ZING_URL.'extensions/carousel/'.$name.'/');
	define('ZING_CAROUSEL_DIR',ZING_DIR.'../extensions/carousel/'.$name.'/');
	if ($name=='ekologic') {
		require(dirname(__FILE__).'/'.$name.'/init.php');
	}
}

function zing_carousel() {
	$name=get_option('zing_ws_carousel');
	if ($name=='ekologic') {
		require(dirname(__FILE__).'/'.$name.'/index.php');
	}
}

?>