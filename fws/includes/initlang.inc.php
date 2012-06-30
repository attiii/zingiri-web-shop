<?php
/*  initlang.inc.php
 Copyright 2006, 2007 Elmar Wenners
 Support site: http://www.chaozz.nl

 This file is part of FreeWebshop.org.

 FreeWebshop.org is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FreeWebshop.org is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
?>
<?php
if (defined('ICL_LANGUAGE_CODE')) $lang=ICL_LANGUAGE_CODE; 
elseif (function_exists("qtrans_getLanguage")) $lang=qtrans_getLanguage();
else {
	// get language from cookie
	if (isset($_COOKIE['cookie_lang'])) { $lang = aphpsSanitize($_COOKIE['cookie_lang']); }
	else { $lang = $default_lang; }
}
// if the lang.txt file from the cookie doesnt exist (anymore), then switch to the default language
if (!file_exists($lang_dir."/".$lang."/lang.txt")) { $lang = $default_lang;}
$lang_file = $lang_dir."/".$lang."/lang.txt";
$main_file = $lang_dir."/".$lang."/main.txt";
if (get_option('zing_webshop_version') < '1.3.0') include ($lang_file);
else {
	$zingPrompts=new zingPrompts($lang);
	$txt=$zingPrompts->load();
}
$charset=get_option("blog_charset");

?>