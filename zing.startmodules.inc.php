<?php
/*  zing.startmodules.inc.php
 Copyright 2008,2009 Erik Bogaerts
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
$dbtablesprefix = $wpdb->prefix."zing_";
$dblocation = DB_HOST;
$dbname = DB_NAME;
$dbuser = DB_USER;
$dbpass = DB_PASSWORD;

//correction for parameter 'cat' used by Wordpress for categories
if (isset($_GET['kat'])) { $_GET['cat']=$_GET['kat']; }

include (ZING_DIR."./includes/readvals.inc.php");        // get and post values
include (ZING_DIR."./includes/readsettings.inc.php");    // read shop settigns
include( ZING_DIR."./includes/setfolders.inc.php");      // set appropriate folders
$product_dir = ZING_SUB.$product_dir;
$brands_dir = ZING_SUB.$brands_dir;
$orders_dir = ZING_SUB.$orders_dir;
$suporders_dir = ZING_SUB.$suporders_dir;
$lang_dir = ZING_SUB.$lang_dir;
$template_dir = ZING_SUB.$template_dir;
$gfx_dir = $template_dir."/".$template."/images";
$scripts_dir = ZING_DIR;

if (function_exists("qtrans_getLanguage")) {
	$lang=qtrans_getLanguage();
	if (!isset($lang)) { $lang = $default_lang; }
	if (!file_exists($lang_dir."/".$lang."/lang.txt")) { $lang = $default_lang;}
	$lang_file = $lang_dir."/".$lang."/lang.txt";
	$main_file = $lang_dir."/".$lang."/main.txt";
} else {
	include (ZING_DIR."./includes/initlang.inc.php");        // init the language
}
include ($lang_file);                         // read the language
$charset=get_option("blog_charset");
?>