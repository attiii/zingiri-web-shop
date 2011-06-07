<?php 
require(dirname(__FILE__)."/sub.default.class.php");
require(dirname(__FILE__)."/rule.class.php");

if ($handle = opendir(dirname(__FILE__).'')) {
	while (false !== ($file = readdir($handle))) {
		if (strstr($file,"class.php") && ($file != "sub.default.class.php") && ($file != "rule.class.php")) {
			require_once(dirname(__FILE__)."/".$file);
		}
	}
	closedir($handle);
}

if ($handle = opendir(dirname(__FILE__).'/rules')) {
	while (false !== ($file = readdir($handle))) {
		if (strstr($file,"class.php") && ($file != "rule.class.php")) {
			require_once(dirname(__FILE__)."/rules/".$file);
		}
	}
	closedir($handle);
}

?>