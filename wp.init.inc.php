<?php
function wsCurrentCmsUserIsShopAdmin() {
	if (current_user_can('administer_web_shop')) return true;
	else return false;
}

function wsIsAdminPage() {
	return is_admin();
}

define('ZING_CMS','wp');

require(dirname(__FILE__).'/fws/globals.php');
global $zing_version;

$dbtablesprefix = $wpdb->prefix."zing_";
$dblocation = DB_HOST;
$dbname = DB_NAME;
$dbuser = DB_USER;
$dbpass = DB_PASSWORD;

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

if (!defined("BLOGUPLOADDIR")) {
	define("BLOGUPLOADDIR",WP_CONTENT_DIR.'/uploads/');
}
if (!defined("BLOGUPLOADURL")) {
	define("BLOGUPLOADURL",str_replace(ABSPATH,get_option('siteurl').'/',BLOGUPLOADDIR));
}

if (!defined("ZING_PLUGINSDIR")) {
	define("ZING_PLUGINSDIR",realpath(dirname(__FILE__).'/..').'/');
}

if (!defined("ZING_PLUGIN")) {
	$zing_plugin=str_replace(realpath(dirname(__FILE__).'/..'),"",dirname(__FILE__));
	$zing_plugin=substr($zing_plugin,1);
	define("ZING_PLUGIN", $zing_plugin);
}
if (!defined("ZING")) {
	define("ZING", true);
}
if (!defined("ZING_SUB")) {
	if (get_option("siteurl") == get_option("home"))
	{
		define("ZING_SUB", "wp-content/plugins/".ZING_PLUGIN."/fws/");
	}
	else {
		define("ZING_SUB", str_replace(get_option("home")."/","",get_option("siteurl"))."/wp-content/plugins/".ZING_PLUGIN."/fws/");
	}
}

if (!defined("ZING_DIR")) {
	define("ZING_DIR", dirname(__FILE__)."/fws/");
}
if (!defined("ZING_LOC")) {
	define("ZING_LOC",dirname(__FILE__)."/");
}
if (!defined("ZING_URL")) {
	define("ZING_URL", WP_PLUGIN_URL . "/".ZING_PLUGIN."/");
}

define("ZING_APPS_CUSTOM_URL",ZING_URL."fws/");

if (!defined("ZING_HOME")) {
	define("ZING_HOME", get_option("home"));
}
if (!defined("ZING_UPLOADS_URL")) {
	define("ZING_UPLOADS_URL", BLOGUPLOADURL . "zingiri-web-shop/");
}

if (function_exists("qtrans_getLanguage")) {
	session_start();
	if (isset($_GET['lang'])) $_SESSION['lang']=$_GET['lang'];
	elseif (isset($_SESSION['lang'])) $_GET['lang']= $_SESSION['lang'];
}

?>