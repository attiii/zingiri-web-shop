<?php
if ($handle = opendir(dirname(__FILE__))) {
	while (false !== ($filex = readdir($handle))) {
		if (strstr($filex,"class.php")) {
			require_once(dirname(__FILE__)."/".$filex);
		}
	}
	closedir($handle);
}

foreach ($zing->paths as $wsPath) {
	if (str_replace('\\','/',$wsPath.'classes') != str_replace('\\','/',dirname(__FILE__))) {
			
		if (file_exists($wsPath.'classes') && $handlex = opendir($wsPath.'classes')) {
			while (false !== ($filex = readdir($handlex))) {
				if (strstr($filex,"class.php")) {
					require_once($wsPath."classes/".$filex);
				}
			}
			closedir($handlex);
		}
	}
}
