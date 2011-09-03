<?php
$files_to_exclude=array('checklogin.inc.php','connectdb.inc.php','httpclass.inc.php','initloc.inc.php','initlang.inc.php','readsettings.inc.php','readvals.inc.php','setfolders.inc.php','settings.inc.php','wp-settings.php');

if ($handlex = opendir(dirname(__FILE__))) {
	while (false !== ($filex = readdir($handlex))) {
		if (strstr($filex,"inc.php") && !in_array($filex,$files_to_exclude)) {
			require_once(dirname(__FILE__)."/".$filex);
		}
	}
	closedir($handlex);
}

foreach ($zing->paths as $wsPath) {
	if (str_replace('\\','/',$wsPath.'includes') != str_replace('\\','/',dirname(__FILE__))) {
			
		if (file_exists($wsPath.'includes') && $handlex = opendir($wsPath.'includes')) {
			while (false !== ($filex = readdir($handlex))) {
				if (strstr($filex,"inc.php")) {
					require_once($wsPath."includes/".$filex);
				}
			}
			closedir($handlex);
		}
	}
}
