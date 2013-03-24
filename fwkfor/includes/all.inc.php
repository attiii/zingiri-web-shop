<?php
if (!defined("FACES_DIR")) {
	define("FACES_DIR", dirname(__FILE__)."/../fields/");
}
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');

if (!function_exists('json_decode')) require(dirname(__FILE__).'/JSON.php');

require(dirname(__FILE__)."/../includes/faces.inc.php");

require(dirname(__FILE__)."/../classes/index.php");

if (!defined("ZING_APPS_MAX_ROWS"))
define ("ZING_APPS_MAX_ROWS",15);

global $aphps_projects;
if (isset($aphps_projects)) {
	//classes
	foreach ($aphps_projects as $id => $project) {
		if (isset($project['dir']) && ($id != 'player')) {
			if (file_exists($project['dir']."classes/index.php")) require($project['dir']."classes/index.php");
			elseif (file_exists($project['dir']."classes/subs")) {
				if ($handle = opendir($project['dir']."classes/subs")) {
					while (false !== ($file = readdir($handle))) {
						if (strstr($file,"class.php")) {
							require_once($project['dir']."classes/subs/".$file);
						}
					}
					closedir($handle);
				}
			}
		}
	}
	//functions
	foreach ($aphps_projects as $id => $project) {
		if (isset($project['dir']) && ($id != 'player')) {
			if (file_exists($project['dir']."functions/index.html")) {
				if ($handle = opendir($project['dir']."functions")) {
					while (false !== ($file = readdir($handle))) {
						if (strstr($file,"inc.php")) {
							require_once($project['dir']."functions/".$file);
						}
					}
					closedir($handle);
				}
			}
		}
	}
	
	//services
	foreach ($aphps_projects as $id => $project) {
		if (isset($project['dir']) && ($id != 'player') && file_exists($project['dir']."services/index.php")) {
			require($project['dir']."services/index.php");
		} elseif (isset($project['dir']) && ($id != 'player') && is_dir($project['dir']."services")) {
			$dirs=array('.');
			foreach ($dirs as $dir) {
				if ($handle = opendir($project['dir']."services/".$dir)) {
					while (false !== ($file = readdir($handle))) {
						if (strstr($file,"class.php")) {
							require_once($project['dir']."services/".$dir.'/'.$file);
						}
					}
					closedir($handle);
				}
			}

		}
	}
}
