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
 Plugin URI: http://www.zingiri.com/web-shop
 Description: Zingiri Web Shop is a full featured software package that allows you to set up your own online webshop within minutes.
 Author: EBO
 Version: 2.0.1
 Author URI: http://www.zingiri.com/
 */

define('ZING_CMS','wp');

require(dirname(__FILE__).'/bootstrap.php');

register_activation_hook(__FILE__,'zing_activate');
register_deactivation_hook(__FILE__,'zing_deactivate');
?>