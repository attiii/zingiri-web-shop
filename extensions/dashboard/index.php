<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');

if ($handle = opendir(dirname(__FILE__))) {
	while (false !== ($filex = readdir($handle))) {
		if (!strstr($filex,"index.php") && strstr($filex,".php")) {
			require_once(dirname(__FILE__).'/'.$filex);
		}
	}
	closedir($handle);
}
?>