<?php
if (get_option("zing_apps_player_version")) {
	add_action("init","zing_apps_player_init");
	add_filter('the_content', 'zing_apps_player_content', 11, 3);
	add_action('wp_head','zing_apps_player_header',100);
}
add_action('admin_menu', 'zing_apps_cp_submenus',20);

if (!function_exists("ZingAppsIsAdmin")) {
	function ZingAppsIsAdmin() {
		if (function_exists('current_user_can') && current_user_can('manage_options')) return true;
		if (function_exists("IsAdmin")) { return IsAdmin(); }
		return false;
	}
}

/**
 * Header hook: loads FWS addons and css files
 * @return unknown_type
 */
function zing_apps_player_header()
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
	
	echo $ret;
	//echo '<script type="text/javascript" language="javascript">';
	//echo '</script>';
	
	if (wsIsAdminPage()) echo '<link rel="stylesheet" href="' . ZING_APPS_PLAYER_URL . 'css/apps_wp_admin.css" type="text/css" media="screen" />';
	echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/' . APHPS_JSDIR . '/tablesorter.jquery.js"></script>'; 
}
?>