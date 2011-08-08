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
 Plugin Name: Zingiri Web Shop Live
 Plugin URI: http://www.zingiri.com
 Description: Zingiri Web Shop is a full featured software package that allows you to set up your own online webshop within minutes.
 Author: Zingiri
 Version: 2.1.1
 Author URI: http://www.zingiri.com
 */

//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');

if (!defined('ZING_CMS')) define('ZING_CMS','wp');

require(dirname(__FILE__).'/live/bootstrap.php');

register_activation_hook(__FILE__,'zing_wslive_activate');
register_deactivation_hook(__FILE__,'zing_wslive_deactivate');

function zing_wslive_activate() {
	if (is_plugin_active('zingiri-web-shop/zingiri_webshop.php')) die("Zingiri and Zingiri Developer Edition can't be activated at the same time.");
}


