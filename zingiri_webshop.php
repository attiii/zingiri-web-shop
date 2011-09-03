<?php
/*  zingiri_webshop.php
 Copyright 2008-2011 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Web Shop.

 Zingiri Web Shop is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Web Shop is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
/*
 Plugin Name: Zingiri Web Shop
 Plugin URI: http://www.zingiri.com/web-shop
 Description: Zingiri Web Shop is a Wordpress plugin that adds fantastic ecommerce capabilities to a great content management system.
 Author: Zingiri
 Version: 2.2.0
 Author URI: http://www.zingiri.com/
 */

if (!defined('ZING_CMS')) define('ZING_CMS','wp');
if (isset($_REQUEST['wscr'])) define('ZING_AJAX',true);
else define('ZING_AJAX',false);

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
