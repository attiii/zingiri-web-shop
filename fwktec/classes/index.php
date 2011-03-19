<?php 
if ($handle = opendir(dirname(__FILE__).'')) {
	while (false !== ($file = readdir($handle))) {
		if (strstr($file,"class.php")) {
			require_once(dirname(__FILE__)."/".$file);
		}
	}
	closedir($handle);
}