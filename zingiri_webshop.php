<?php
/*  zingiri.webshop.php
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
/**
 * @package Zingiri Web Shop
 * @author Erik Bogaerts
 * @version 0.9.17
 */
/*
 Plugin Name: Zingiri Web Shop
 Plugin URI: http://www.zingiri.com/WebShop
 Description: This plugin integrates the fabulous Free Web Shop e-commerce solution with Wordpress.
 Author: Erik Bogaerts
 Version: 0.9.17
 Author URI: http://www.zingiri.com/
 */

// Pre-2.6 compatibility for wp-content folder location
if (!defined("WP_CONTENT_URL")) {
	define("WP_CONTENT_URL", get_option("siteurl") . "/wp-content");
}
if (!defined("WP_CONTENT_DIR")) {
	define("WP_CONTENT_DIR", ABSPATH . "wp-content");
}

define("ZING_VERSION","0.9.17");

if (!defined("ZING_PLUGIN")) {

	$zing_plugin=substr(dirname(__FILE__),strlen(WP_CONTENT_DIR)+9,strlen(dirname(__FILE__))-strlen(WP_CONTENT_DIR)-9);
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
		define("ZING_SUB", "wordpress/wp-content/plugins/".ZING_PLUGIN."/fws/");
	}
}
if (!defined("ZING_DIR")) {
	define("ZING_DIR", WP_CONTENT_DIR . "/plugins/".ZING_PLUGIN."/fws/");
}
if (!defined("ZING_LOC")) {
	define("ZING_LOC", WP_CONTENT_DIR . "/plugins/".ZING_PLUGIN."/");
}
if (!defined("ZING_URL")) {
	define("ZING_URL", WP_CONTENT_URL . "/plugins/".ZING_PLUGIN."/");
}
if (!defined("ZING_HOME")) {
	define("ZING_HOME", get_option("home"));
}

$dbtablesprefix = $wpdb->prefix."zing_";
$dblocation = DB_HOST;
$dbname = DB_NAME;
$dbuser = DB_USER;
$dbpass = DB_PASSWORD;

$zing_version=get_option("zing_webshop_version");

if ($zing_version) {
require (ZING_LOC."./zing.startfunctions.inc.php");
add_action("init","zing_init");
add_filter('option_art_footer_content','zing_footer');
add_filter('get_pages','zing_exclude_pages');
add_action("plugins_loaded", "zing_sidebar_init");
add_filter('the_content', 'zing_content', 10, 3);
add_action('get_header','zing_get_header');
add_action('wp_head','zing_header');
}

//register_activation_hook(__FILE__,'zing_activate');
//register_deactivation_hook(__FILE__,'zing_deactivate');

require_once(dirname(__FILE__) . '/controlpanel.php'); 
/**
 * Output activation messages to log
 * @param $stringData
 * @return unknown_type
 */
function zing_echo($stringData) {
	$myFile = ZING_LOC."/log.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, $stringData);
	fclose($fh);
}

/**
 * Activation of web shop: creation of database tables & set up of pages
 * @return unknown_type
 */
function zing_activate() {
	global $wpdb;

	$zing_version=get_option("zing_webshop_version");
	if (!$zing_version)
	{
		add_option("zing_webshop_version",ZING_VERSION);
	}
	else
	{
		update_option("zing_webshop_version",ZING_VERSION);
	}
	
	$wpdb->show_errors();
	$url=ZING_DIR.'FreeWebshop.sql';
	$prefix=$wpdb->prefix."zing_";
	$file_content = file($url);
	$query = "";
	foreach($file_content as $sql_line) {
		$tsl = trim($sql_line);
		if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
			$sql_line = str_replace("CREATE TABLE `", "CREATE TABLE `".$prefix, $sql_line);
			$sql_line = str_replace("INSERT INTO `", "INSERT INTO `".$prefix, $sql_line);
			$sql_line = str_replace("ALTER TABLE `", "ALTER TABLE `".$prefix, $sql_line);
			$sql_line = str_replace("UPDATE ", "UPDATE ".$prefix, $sql_line);
			$sql_line = str_replace("TRUNCATE TABLE `", "TRUNCATE TABLE `".$prefix, $sql_line);
			$query .= $sql_line;

			if(preg_match("/;\s*$/", $sql_line)) {
				$wpdb->query($query);
				$query = "";
			}
		}
	}

	//set default language to English
	$query="UPDATE ".$prefix."settings SET `default_lang` = 'en'";
	$wpdb->query($query);
	$pages=array();
	$pages[]=array("Shop","main","*",0);
	$pages[]=array("Cart","cart","",0);
	$pages[]=array("Checkout","conditions","checkout",6);
	$pages[]=array("Admin","admin","",9);
	$pages[]=array("Personal","my","",3);
	$pages[]=array("Login","my","login",1);
	$pages[]=array("Logout","logout","*",3);
	$pages[]=array("Register","customer","add",1);

	$ids="";
	foreach ($pages as $i =>$p)
	{
		$my_post = array();
		$my_post['post_title'] = $p['0'];
		$my_post['post_content'] = '';
		$my_post['post_status'] = 'publish';
		$my_post['post_author'] = 1;
		$my_post['post_type'] = 'page';
		//  $my_post['post_category'] = array(8,39);
		$my_post['menu_order'] = 100+$i;
		$id=wp_insert_post( $my_post );
		if (empty($ids)) { $ids.=$id; } else { $ids.=",".$id; }
		if (!empty($p[1])) add_post_meta($id,'zing_page',$p[1]);
		if (!empty($p[2])) add_post_meta($id,'zing_action',$p[2]);
		if (!empty($p[3])) add_post_meta($id,'zing_security',$p[3]);
	}
	if (get_option("zing_webshop_pages"))
	{
		update_option("zing_webshop_pages",$ids);
	}
	else {
		add_option("zing_webshop_pages",$ids);
	}
}

/**
 * Deactivation of web shop: removal of database tables
 * @return unknown_type
 */
function zing_deactivate() {
	global $wpdb;

	$prefix=$wpdb->prefix."zing_";
	$rows=$wpdb->get_results("show tables like '".$prefix."%'",ARRAY_N);
	foreach ($rows as $id => $row) {
		zing_echo(print_r($row,true));
		$query="drop table ".$row[0];
		$wpdb->query($query);
	}
	$ids=get_option("zing_webshop_pages");
	$ida=explode(",",$ids);
	foreach ($ida as $id) {
		wp_delete_post($id);	
	}
	delete_option("zing_webshop_version",ZING_VERSION);
}

/**
 * Main function handling content, footer and sidebars
 * @param $process
 * @param $content
 * @return unknown_type
 */
function zing_main($process,$content="") {

	global $post;
	global $wpdb;
	global $aboutus_page;
	global $action;
	global $author;
	global $autosubmit;
	global $bankaccount;
	global $bankaccountowner;
	global $bankbic;
	global $bankcity;
	global $bankcountry;
	global $bankiban;
	global $bankname;
	global $brands_dir;
	global $breadcrumb;
	global $cat;
	global $catdesc;
	global $category_thumb_height;
	global $category_thumb_width;
	global $cntry;
	global $conditions_page;
	global $create_pdf;
	global $currency;
	global $currency_pos;
	global $currency_symbol;
	global $currency_symbol_post;
	global $currency_symbol_pre;
	global $customerid;
	global $date_format;
	global $date_format_ext;
	global $db_lang;
	global $db_prices_including_vat;
	global $dblocation;
	global $dbname;
	global $dbpass;
	global $dbtablesprefix;
	global $dbuser;
	global $default_lang;
	global $description;
	global $gfx_dir;
	global $guarantee_page;
	global $hide_outofstock;
	global $index_refer;
	global $isbn_access_key;
	global $keywords;
	global $lang;
	global $lang_dir;
	global $lang_file;
	global $lang2;
	global $lang3;
	global $live_news;
	global $main_file;
	global $make_thumbs;
	global $max_description;
	global $name;
	global $new_days;
	global $new_page;
	global $no_vat;
	global $number_format;
	global $order_from_pricelist;
	global $order_prefix;
	global $order_suffix;
	global $orderby;
	global $ordering_enabled;
	global $orders_dir;
	global $page;
	global $page_footer;
	global $page_title;
	global $paymentdays;
	global $pictureid;
	global $pricelist_format;
	global $pricelist_thumb_height;
	global $pricelist_thumb_width;
	global $product_dir;
	global $product_max_height;
	global $product_max_width;
	global $products_dir;
	global $products_per_page;
	global $rate;
	global $region;
	global $sales_mail;
	global $scripts_dir;
	global $search_prodgfx;
	global $send_default_country;
	global $shipping_page;
	global $shop_disabled;
	global $shop_disabled_reason;
	global $shop_disabled_title;
	global $shop_logo;
	global $shop_name;
	global $shopfax;
	global $shopname;
	global $shoptel;
	global $shopurl;
	global $show_stock;
	global $show_vat;
	global $slogan;
	global $start_year;
	global $stock_enabled;
	global $stock_warning_level;
	global $template;
	global $template_dir;
	global $thumbs_in_pricelist;
	global $title;
	global $titlepage;
	global $topupdelta;
	global $topuplow;
	global $topupmin;
	global $txt;
	global $use_captcha;
	global $use_datefix;
	global $use_imagepopup;
	global $use_phpmail;
	global $use_prodgfx;
	global $use_stock_warning;
	global $use_wysiwyg;
	global $vat;
	global $webmaster_mail;
	global $weight_metric;
	global $zing_loaded;

	include (ZING_LOC."./zing.globals.inc.php");
//	error_reporting(E_ALL & ~E_NOTICE);
//	ini_set('display_errors', '1');

	switch ($process)
	{
		case "content":

			$cf=get_post_custom();

			if (isset($_GET['page']))
			{
				//do nothing, page already set
			}
			elseif (isset($cf['zing_page']))
			{
				$_GET['page']=$cf['zing_page'][0];
				if (isset($cf['zing_action']))
				{
					$_GET['action']=$cf['zing_action'][0];
				}
			}
			else
			{
				return $content;
			}
			if (isset($cf['cat'])) {
				$_GET['cat']=$cf['cat'][0];
			}

			$to_include="loadmain.php";
			break;
		case "footer":
			$to_include="footer.php";
			break;
		case "sidebar":
			$to_include="menu_".$content.".php";
			break;
	}
	if (!$zing_loaded)
	{
		require (ZING_LOC."./zing.startmodules.inc.php");
		$zing_loaded=TRUE;
	} else {
		require (ZING_DIR."./includes/readvals.inc.php");        // get and post values
	}
//	echo $scripts_dir."/".$to_include;
	include($scripts_dir."/".$to_include);
//	echo "ok";
}

/**
 * Page content filter
 * @param $content
 * @return unknown_type
 */
function zing_content($content) {
	return zing_main("content",$content);
}


/**
 * Header hook: loads FWS addons and css files
 * @return unknown_type
 */
function zing_header()
{
	echo '<link rel="stylesheet" href="' . ZING_URL . 'fws/addons/lightbox/lightbox.css" type="text/css" media="screen" />';
	echo '<script type="text/javascript" src="' . ZING_URL . 'fws/addons/lightbox/lightbox.js"></script>';
	echo '<link rel="stylesheet" type="text/css" href="' . ZING_URL . 'zing.css" media="screen" />';
}

/**
 * Manage redirects for login, logout, set language
 * @return unknown_type
 */
function zing_get_header()
{
	global $dbtablesprefix;
	global $dblocation;
	global $dbname;
	global $dbuser;
	global $dbpass;
	global $product_dir;
	global $brands_dir;
	global $orders_dir;
	global $lang_dir;
	global $template_dir;
	global $gfx_dir;
	global $scripts_dir;
	global $products_dir;
	global $index_refer;
	global $name;
	global $customerid;
	
	require (ZING_LOC."./zing.readcookie.inc.php");      // read the cookie

}

/**
 * Sidebar general menu widget
 * @param $args
 * @return unknown_type
 */
function widget_sidebar_general($args) {

	global $txt;
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $txt['menu14'];
	echo $after_title;
	echo '<div id="zing-sidebar">';
	echo '<style type="text/css">';
	echo 'h1 {display:none; }';
	echo '</style>';
	zing_main("sidebar","general");
	echo '</div>';
	echo $after_widget;

}

/**
 * Sidebar products menu widget
 * @param $args
 * @return unknown_type
 */
function widget_sidebar_products($args) {
	global $txt;
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $txt['menu15'];
	echo $after_title;
	echo '<div id="zing-sidebar">';
	echo '<style type="text/css">';
	echo 'h1 {display:none; }';
	echo '</style>';
	zing_main("sidebar","products");
	echo "</div>";
	echo $after_widget;
}

/**
 * Sidebar cart menu widget
 * @param $args
 * @return unknown_type
 */
function widget_sidebar_cart($args) {
	global $txt;
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $txt['menu2'];
	echo $after_title;
	echo '<div id="zing-sidebar">';
	echo '<style type="text/css">';
	echo 'h1 {display:none; }';
	echo '</style>';
	zing_main("sidebar","cart");
	echo '</div>';
	echo $after_widget;

}

/**
 * Register sidebar widgets
 * @return unknown_type
 */
function zing_sidebar_init()
{
	register_sidebar_widget(__('Zingiri Web Shop Cart'), 'widget_sidebar_cart');
	register_sidebar_widget(__('Zingiri Web Shop General'), 'widget_sidebar_general');
	register_sidebar_widget(__('Zingiri Web Shop Products'), 'widget_sidebar_products');
}

/**
 * Initialization of page, action & page_id arrays
 * @return unknown_type
 */
function zing_init()
{
	global $dbtablesprefix;
	global $dblocation;
	global $dbname;
	global $dbuser;
	global $dbpass;
	global $product_dir;
	global $brands_dir;
	global $orders_dir;
	global $lang_dir;
	global $template_dir;
	global $gfx_dir;
	global $scripts_dir;
	global $products_dir;
	global $index_refer;
	global $name;
	global $customerid;
	
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) { return $pages; }

	global $zing_page_id_to_page, $zing_page_to_page_id, $wpdb;
	global $name;
	global $customerid;

	$zing_page_id_to_page=array();
	$zing_page_to_page_id=array();

	$sql = "SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key = 'zing_page'";
	$a = $wpdb->get_results( $sql );

	foreach ($a as $i => $o )
	{
		$zing_page_id_to_page[$o->post_id][0]=$o->meta_value;
	}
	$sql = "SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key = 'zing_action'";
	$a = $wpdb->get_results( $sql );
	foreach ($a as $i => $o )
	{
		$zing_page_id_to_page[$o->post_id][1]=$o->meta_value;
	}

	$zing_page_to_page_id=array();
	foreach ($zing_page_id_to_page as $i => $a)
	{
		$page=$a[0];
		$action=$a[1];
		if (isset($a[0]) && isset($a[1]))
		{
			$zing_page_to_page_id[$page][$action]=$i;
		}
		if (isset($a[0]) && !isset($a[1]))
		{
			$zing_page_to_page_id[$page]['*']=$i;
		}
	}

	if ($_POST['page']=="login")
	{
		include (ZING_LOC."./zing.startmodules.inc.php");
		require(ZING_DIR."login.php");
		exit;
	}
	if (!empty($_GET['page_id']) && ($_GET['page_id']==zing_page_id("logout")))
	{
		include (ZING_LOC."./zing.startmodules.inc.php");
		require(ZING_DIR."logout.php");
		exit;
	}
		
	if (!isset($_GET['page_id']) && isset($_GET['page']))
	{
		//cat is a parameter used by Wordpress for categories
		if (isset($_GET['cat']) && isset($_GET['page'])) {
			$_GET['kat']=$_GET['cat'];
			unset($_GET['cat']);
		}
		$_GET['page_id']=zing_page_id("main");
	}
}

/**
 * Look up FWS page name based on Wordpress page_id
 * @param $page_id
 * @return unknown_type
 */
function zing_page($page_id)
{
	global $zing_page_id_to_page;
	if (isset($zing_page_id_to_page[$page_id]))
	{
		return $zing_page_id_to_page[$page_id];
	}
	return "main";
}

/**
 * Look up Wordpress page_id based on FWS page and action
 * @param $page
 * @param $action
 * @return unknown_type
 */
function zing_page_id($page,$action="*")
{
	global $zing_page_to_page_id;

	if (isset($zing_page_to_page_id[$page][$action]))
	{
		return $zing_page_to_page_id[$page][$action];
	}
	elseif (isset($zing_page_to_page_id[$page]))
	{
		echo $page;
		echo $action;
		echo "this case";die();
		return $zing_page_to_page_id[$page];
	}
	return "";
}

/**
 * Exclude certain pages from the menu depending on whether the user is logged in
 * or is an administrator. This depends on the custom field "security":
 * 	0 - show if not logged in
 * 	1 - show if not logged in but hide if logged in
 *  2 - show if customer logged in
 *  3 - show if customer or user or admin logged in
 * 	4 - show if not logged in or if customer logged in
 *  5 - show if user or customer logged in
 *  6 - show if user or admin logged in
 *  9 - show if admin logged in
 * @param $pages
 * @return unknown_type
 */
function zing_exclude_pages( & $pages )
{
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) return $pages;

	Global $dbtablesprefix;
	Global $cntry;
	Global $lang;
	Global $lang2;
	Global $lang3;

	require (ZING_DIR."./includes/settings.inc.php");        // database settings

	$loggedin=LoggedIn();
	if ($loggedin) $isadmin=IsAdmin();
	if (!$isadmin) $iscustomer=true;

	$unsetpages=array();
	$l=count($pages);
	for ( $i=0; $i<$l; $i++ ) {
		$page = & $pages[$i];
		$security=get_post_meta($page->ID,"zing_security",TRUE);
		$show=false;
		if ($security == 0) {
			$show=true;
		}
		elseif ($security == "1" && !$loggedin) {
			$show=true;
		}
		elseif ($security == "2" && $loggedin && $iscustomer) {
			$show=true;
		}
		elseif ($security == "3" && $loggedin) {
			$show=true;
		}
		elseif ($security == "4" && (!$loggedin || $iscustomer)) {
			$show=true;
		}
		elseif ($security == "5" && $loggedin && !$isadmin && ($isuser || $iscustomer)) {
			$show=true;
		}
		elseif ($security == "6" && $loggedin && ($isuser || $isadmin)) {
			$show=true;
		}
		elseif ($security == "9" && $loggedin && $isadmin) {
			$show=true;
		}
		if (!$show)
		{
			unset($pages[$i]);
			$unsetpages[$page->ID]=true;
		}
	}

	$l=count($pages);
	for ( $i=0; $i<$l; $i++ ) {
		$page = & $pages[$i];
		$parent=$page->post_parent;
		if (isset($unsetpages[$parent]))
		{
			unset($pages[$i]);
		}
	}

	return $pages;
}

/**
 * The footer is automatically inserted for Artisteer generated themes.
 * For other themes, the function zing_footer should be called from inside the theme.
 * @param $footer
 * @return unknown_type
 */
function zing_footer($footer="")
{
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) return $footer;
	if (empty($footer))
	{
		zing_main("footer",$footer);
	}
	else {
		echo $footer;
	}
	//Please contact us if you wish to remove the Zingiri logo in the footer
	echo '<div style="margin-top:5px"><a href="http://www.zingiri.com" alt="Zingiri Web Shop"><img src="'.ZING_URL.'zingiri-logo.png"></a></div>';
}

/**
 * Recording of database errors
 * @param $query
 * @param $loc
 * @return unknown_type
 */
function zing_dberror($query,$loc) {
	echo $query."<br />";
	echo $loc."<br />";
	echo mysql_error();
	die();
}

?>