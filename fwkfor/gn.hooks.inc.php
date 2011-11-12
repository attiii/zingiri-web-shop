<?php
function get_post_custom() {
}

function is_admin() {
	return IsAdmin();
}

if (!function_exists("ZingAppsIsAdmin")) {
	function ZingAppsIsAdmin() {
		if (function_exists("IsAdmin")) { return IsAdmin(); }
		return false;
	}
}

/**
 * Header hook: loads FWS addons and css files
 * @return unknown_type
 */
function zing_apps_player_header($display=true)
{
	$wsVars=zVars();
	$ret='';
	$ret.='<script type="text/javascript" language="javascript">';
	foreach ($wsVars as $v => $c) {
		$ret.="var ".$v."='".$c."';";
	}
	$ret.='</script>';
	
	$wsScripts=zScripts();
	foreach ($wsScripts as $s) {
		$ret.='<script type="text/javascript" src="' . $s . '"></script>';
	}
	
	$wsStyleSheets=zStyleSheets();
	foreach ($wsStyleSheets as $s) {
		$ret.='<link rel="stylesheet" type="text/css" href="' . $s . '" media="screen" />';
	}
	
	//$ret.='<script type="text/javascript" language="javascript">';
	//$ret.='</script>';
	
	if (wsIsAdminPage()) $ret.='<link rel="stylesheet" href="' . ZING_APPS_PLAYER_URL . 'css/apps_wp_admin.css" type="text/css" media="screen" />';
	$ret.='<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/' . APHPS_JSDIR . '/sorttable.js"></script>';

	if ($display) echo $ret;
	else return $ret;
}
?>