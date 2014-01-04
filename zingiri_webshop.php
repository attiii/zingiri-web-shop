<?php
/*
 Plugin Name: Zingiri Web Shop
 Plugin URI: http://www.zingiri.com/web-shop
 Description: Zingiri Web Shop is a Wordpress plugin that adds fantastic ecommerce capabilities to a great content management system.
 Author: Zingiri
 Version: 2.6.5
 Author URI: http://www.zingiri.com/
 */

if (!defined('BASE_DIR')) define('BASE_DIR',dirname(__FILE__).'/');
if (!defined('ZING_CMS')) define('ZING_CMS','wp');
if (!defined('ZING_AJAX')) {
	if (isset($_REQUEST['wscr'])) define('ZING_AJAX',true);
	else define('ZING_AJAX',false);
}

if (file_exists(dirname(__FILE__).'/../maintenance')) {
	define('ZING_MAINTENANCE',1);
} else {
	define('ZING_MAINTENANCE',0);
	require(dirname(__FILE__).'/local/bootstrap.php');
	register_deactivation_hook(__FILE__,'zing_deactivate');
}
register_activation_hook(__FILE__,'zing_activate');

function zing_activate() {
	if (is_plugin_active('zingiri-web-shop/wslive.php') || is_plugin_active('wslive/zingiri_webshop.php')) die("Zingiri and Zingiri Developer Edition can't be activated at the same time.");
}
