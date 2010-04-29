<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');

if ($handle = opendir(dirname(__FILE__))) {
	while (false !== ($file = readdir($handle))) {
		if (!strstr($file,"index.php") && strstr($file,".php")) {
			require_once(dirname(__FILE__).'/'.$file);
		}
	}
	closedir($handle);
}
?>