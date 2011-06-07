<?php
$dirs=array('subs','rules');
foreach ($dirs as $dir) {
	if ($handle = opendir(dirname(__FILE__).'/'.$dir)) {
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,"class.php")) {
				require_once(dirname(__FILE__)."/".$dir.'/'.$file);
			}
		}
		closedir($handle);
	}
}
?>