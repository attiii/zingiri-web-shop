<?php
// Pre-2.6 compatibility for wp-content folder location
if (!defined("WP_CONTENT_URL")) {
	define("WP_CONTENT_URL", get_option("siteurl") . "/wp-content");
}
if (!defined("WP_CONTENT_DIR")) {
	define("WP_CONTENT_DIR", ABSPATH . "wp-content");
}
if (!defined("WP_PLUGIN_URL")) {
	define("WP_PLUGIN_URL", get_option("siteurl") . "/wp-content/plugins");
}
if (!defined("WP_PLUGIN_DIR")) {
	define("WP_PLUGIN_DIR", ABSPATH . "wp-content/plugins");
}

if (!defined("ZING_APPS_EMBED")) {
	define("ZING_APPS_EMBED","");
}

if (!defined("ZING_APPS_PLAYER_PLUGIN")) {
	$zing_apps_player_plugin=str_replace(realpath(dirname(__FILE__).'/../..'),"",dirname(__FILE__));
	$zing_apps_player_plugin=substr($zing_apps_player_plugin,1);
	define("ZING_APPS_PLAYER_PLUGIN", $zing_apps_player_plugin);
}

if (!defined("ZING_APPS_PLAYER")) {
	define("ZING_APPS_PLAYER", true);
}

if (!defined("ZING_APPS_PLAYER_URL")) {
	define("ZING_APPS_PLAYER_URL", WP_PLUGIN_URL . "/".ZING_APPS_PLAYER_PLUGIN."/");
}
if (!defined("ZING_APPS_PLAYER_DIR")) {
	define("ZING_APPS_PLAYER_DIR", WP_PLUGIN_DIR . "/".ZING_APPS_PLAYER_PLUGIN."/");
}
if (!defined("FACES_DIR")) {
	define("FACES_DIR", WP_CONTENT_URL . "/plugins/".ZING_APPS_PLAYER_PLUGIN."/fields/");
}

$dbtablesprefix = $wpdb->prefix."zing_";
if (!defined("DB_PREFIX") && $wpdb->prefix) define("DB_PREFIX",$dbtablesprefix);
$dblocation = DB_HOST;
$dbname = DB_NAME;
$dbuser = DB_USER;
$dbpass = DB_PASSWORD;
?>