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
	foreach ($aphps_projects as $id => $project) {
		if ($id != 'player') {
			if (file_exists($project['dir']."classes/index.php")) require($project['dir']."classes/index.php");
		}
	}
	foreach ($aphps_projects as $id => $project) {
		if ($id != 'player' && file_exists($project['dir']."services/index.php")) require($project['dir']."services/index.php");
	}
}
