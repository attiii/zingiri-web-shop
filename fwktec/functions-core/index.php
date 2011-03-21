<?php 
if ($handle = opendir(dirname(__FILE__))) {
	while (false !== ($file = readdir($handle))) {
		if (strstr($file,".php")  && ($file != "index.php")) {
			require_once(dirname(__FILE__)."/".$file);
		}
	}
	closedir($handle);
}