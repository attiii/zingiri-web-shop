<?php
/*  zingiri_webshop.php
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
//echo 'uploads='.BLOGUPLOADURL.'-'.BLOGUPLOADDIR;

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
$dbtablesprefix = $wpdb->prefix."zing_";
$dblocation = DB_HOST;
$dbname = DB_NAME;
$dbuser = DB_USER;
$dbpass = DB_PASSWORD;

$zing_version=get_option("zing_webshop_version");
require (ZING_LOC."./zing.startfunctions.inc.php");
require_once(dirname(__FILE__) . '/zing.integrator.class.php');
if ($zing_version) {
	add_action("init","zing_init");
	add_filter('wp_footer','zing_footer');
	add_filter('get_pages','zing_exclude_pages');
	add_action("plugins_loaded", "zing_sidebar_init");
	add_filter('the_content', 'zing_content', 10, 3);
	add_action('wp_head','zing_header');
	add_action('wp_head','zing_ws_header_custom',100);
	add_filter('wp_title','zing_ws_title');
	add_filter('the_title','zing_ws_page_title');
	if ($integrator->wpCustomer) {
		add_action('wp_login','zing_login');
		add_action('wp_logout','zing_logout');
		add_filter('check_password','zing_check_password',10,4);
		//add_action('personal_options_update','zing_profile_pre'); //before wp error check and update
		//add_action('edit_user_profile_update','zing_profile_pre'); //before wp error check and update
		//add_action('user_profile_update_errors','zing_profile_check_errors',10,3); //check errors after wp checks done
		add_action('profile_update','zing_profile'); //post wp update
		add_action('user_register','zing_profile'); //post wp update
		//add_action('show_user_profile','zing_profile_show');
		//add_action('edit_user_profile','zing_profile_edit');
		add_action('delete_user','zing_delete_user');
	}
}
add_action("init","zing_init_uninstall");
add_action('admin_notices','zing_admin_notices');

if (!defined("ZING_DIG") && get_option('zing_webshop_dig')!="") {
	define("ZING_DIG",BLOGUPLOADDIR.'/zingiri-web-shop/digital-'.get_option('zing_webshop_dig').'/');
}

require_once(dirname(__FILE__) . '/controlpanel.php');

function zing_admin_notices() {
	$zing_version=get_option("zing_webshop_version");

	if (!$zing_version) {
		if ($_GET['page']!='zingiri-web-shop')
		$message='Zingiri Web Shop is almost ready. You need to launch the <a href="admin.php?page=zingiri-web-shop">installation</a> from the integration page.';
		else
		$message='Zingiri Web Shop is almost ready. You need to launch the installation by clicking the Install button below.';
	} elseif ($zing_version != ZING_VERSION) {
		if ($_GET['page']!='zingiri-web-shop')
		$message='You downloaded Zingiri Web Shop version '.ZING_VERSION.' and need to <a href="admin.php?page=zingiri-web-shop">upgrade</a> your database (currently at version '.$zing_version.') from the integration page.';
		else
		$message='You downloaded Zingiri Web Shop version '.ZING_VERSION.' and need to upgrade your database (currently at version '.$zing_version.') by clicking the Upgrade button below.';
	}
	if ($message) echo "<div id='zing-warning' style='background-color:greenyellow' class='updated fade'><p><strong>".$message."</strong> "."</p></div>";


}

function zing_init_uninstall() {
	if (current_user_can('edit_plugins') && $_GET['zingiri']=='uninstall') {
		zing_uninstall();
		zing_apps_player_uninstall();
		header("Location: options-general.php?page=zingiri-web-shop&uninstalled=true");
	}
}

/**
 * Check if the web shop has been properly activated
 * @return boolean
 */
function zing_check() {
	global $lang_dir;
	$errors=array();
	$warnings=array();
	$files=array();
	$dirs=array();
	$zing_version=get_option("zing_webshop_version");

	//if ($zing_version == "") {
	//$errors[]='Please proceed with a clean install or deactivate your plugin';
	//return array('errors'=> $errors, 'warnings' => $warnings);
	//}
	//elseif ($zing_version != ZING_VERSION) $errors[]='You downloaded version '.ZING_VERSION.' and need to upgrade your database (currently at version '.$zing_version.').';

	//if ($zing_version < '1.2.0') return $errors;

	$files[]=ZING_LOC.'log.txt';
	$files[]=ZING_DIR.'banned.txt';

	foreach ($files as $file) {
		//		if (!file_exists($file)) $warnings[]='File '.$file. " doesn't exist";
		if (!is_writable($file)) $warnings[]='File '.$file.' is not writable, please chmod to 666';
	}

	$dirs[]=ZING_DIR.'addons/captcha';
	$dirs[]=ZING_DIR.'addons/tinymce/jscripts/up';
	foreach ($dirs as $file) {
		if (!is_writable($file)) $warnings[]='Directory '.$file.' is not writable, please chmod to 777';
	}

	if ($zing_version) {
		$dirs=array();
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop';
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop/prodgfx';
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop/cats';
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop/orders';
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop/digital-'.get_option('zing_webshop_dig');

		foreach ($dirs as $file) {
			if (!file_exists($file)) $warnings[]='Directory '.$file. " doesn't exist";
			elseif (!is_writable($file)) $warnings[]='Directory '.$file.' is not writable, please chmod to 777';
		}
	}

	if (phpversion() < '5')	$warnings[]="You are running PHP version ".phpversion().". If you wish to use the PDF invoice generation functionality, you will need to upgrade to version 5.x.x";
	if (ini_get("zend.ze1_compatibility_mode")) $warnings[]="You are running PHP in PHP 4 compatibility mode. The PDF invoice functionality requires this mode to be turned off.";

	//check files hash
	$c=new filesHash();
	$checksumErrors=$c->compare();
	if (count($checksumErrors) > 25) {
		$errors[]="Can't verify integrity of the installation, make sure you have uploaded your files using ftp binary mode";
	} elseif (count($checksumErrors) > 0) {
		foreach ($checksumErrors as $file => $error) {
			if ($error == 1) $errors[]="File ".$file." is missing";
			if ($error == 2) $warnings[]="File ".$file." is not the correct version";
		}
	}

	return array('errors'=> $errors, 'warnings' => $warnings);

}

function zing_ws_error_handler($severity, $msg, $filename="", $linenum=0) {
	if (is_array($msg)) $msg=print_r($msg,true);
	$myFile = dirname(__FILE__)."/log.txt";
	if ($fh = fopen($myFile, 'a')) {
		fwrite($fh, date('Y-m-d h:i:s').' '.$msg.' ('.$filename.'-'.$linenum.')'."\r\n");
		fclose($fh);
	}
}
function zing_ws_error_handler_truncate() {
	$myFile = dirname(__FILE__)."/log.txt";
	if ($fh = fopen($myFile, 'w')) {
		fclose($fh);
	}
}
/**
 * Activation of web shop: creation of database tables & set up of pages
 * @return unknown_type
 */
function zing_activate() {
	//nothing happening here
}

function zing_install() {
	global $wpdb,$zingPrompts,$dbtablesprefix;

	$player=false;

	zing_ws_error_handler_truncate();
	set_error_handler("zing_ws_error_handler");
	error_reporting(E_ALL & ~E_NOTICE);

	$wpdb->show_errors();
	$prefix=$wpdb->prefix."zing_";
	$dbtablesprefix=$prefix;
	if (!defined("DB_PREFIX")) define("DB_PREFIX",$prefix);

	zing_ws_error_handler(0,'DB_PREFIX:'.DB_PREFIX);

	$zing_version=get_option("zing_webshop_version");
	if (!$zing_version)
	{
		add_option("zing_webshop_version",ZING_VERSION);
	}
	else
	{
		update_option("zing_webshop_version",ZING_VERSION);
	}


	if ($handle = opendir(dirname(__FILE__).'/fws/db')) {
		$files=array();
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,".sql")) {
				$f=explode("-",$file);

				$v=str_replace(".sql","",$f[1]);
				if ($zing_version < $v) {
					$files[]=array(dirname(__FILE__).'/fws/db/'.$file,$v);
				}
			}
		}
		closedir($handle);
		asort($files);
		if (count($files) > 0) {
			foreach ($files as $afile) {
				list($file,$v)=$afile;
				zing_ws_error_handler(0,'Process '.$file);
				if ($v>='1.2.7' && !$player) {
					zing_apps_player_install();
					$player=true;
					zing_ws_error_handler(0,'continue with:'.$file);
				}
				$file_content = file($file);
				$query = "";
				foreach($file_content as $sql_line) {
					$tsl = trim($sql_line);
					if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
						if (str_replace("##", $prefix, $sql_line) == $sql_line) {
							$sql_line = str_replace("CREATE TABLE `", "CREATE TABLE `".$prefix, $sql_line);
							$sql_line = str_replace("CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS`".$prefix, $sql_line);
							$sql_line = str_replace("INSERT INTO `", "INSERT INTO `".$prefix, $sql_line);
							$sql_line = str_replace("ALTER TABLE `", "ALTER TABLE `".$prefix, $sql_line);
							$sql_line = str_replace("UPDATE `", "UPDATE `".$prefix, $sql_line);
							$sql_line = str_replace("TRUNCATE TABLE `", "TRUNCATE TABLE `".$prefix, $sql_line);
						} else {
							$sql_line = str_replace("##", $prefix, $sql_line);
						}
						$query .= $sql_line;
						if(preg_match("/;\s*$/", $sql_line)) {
							zing_ws_error_handler(0,$query);
							mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
							//$wpdb->query($query);
							$query = "";
						}
					}
				}
			}
		}
	}
	//Load Apps forms if not loaded yet
	if (!$player) {
		zing_ws_error_handler(0,'Loading Apps forms');
		zing_apps_player_install();
		$player=true;
	}

	//Update default settings
	$query="update ".$prefix."settings set sales_mail='".get_bloginfo('admin_email')."' where id=1";
	mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
	$query="update ".$prefix."settings set webmaster_mail='".get_bloginfo('admin_email')."' where id=1";
	mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
	$query="update ".$prefix."settings set shopname='".get_bloginfo('name')."' where id=1";
	mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
	$query="update ".$prefix."settings set shopurl='".get_option('home')."' where id=1";
	mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
	
	//Load language files
	zing_ws_error_handler(0,'load language files');

	if (!isset($zingPrompts)) $zingPrompts=new zingPrompts();
	$zingPrompts->installAllLanguages();

	//Create default pages
	zing_ws_error_handler(0,'create default pages');

	if ($zing_version < '0.9.15') {
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
			$my_post['post_content'] = 'Do not delete this page unless you know what you are doing';
			$my_post['post_status'] = 'publish';
			$my_post['post_author'] = 1;
			$my_post['post_type'] = 'page';
			$my_post['comment_status'] = 'closed';
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
	//set comment status to closed
	elseif ($zing_version < '1.2.0') {
		$ids=get_option("zing_webshop_pages");
		$ida=explode(",",$ids);
		foreach ($ida as $id) {
			$my_post = array();
			$my_post['ID']=$id;
			$my_post['comment_status'] = 'closed';
			wp_update_post($my_post);
		}
	}

	//Create digital products directory if it doesn't exist yet
	if (!get_option('zing_webshop_dig')) {
		update_option('zing_webshop_dig',CreateRandomCode(15));
	}
	$dig=BLOGUPLOADDIR.'zingiri-web-shop/digital-'.get_option('zing_webshop_dig').'/';
	if (!is_dir($dig)) {
		if (mkdir($dig)) {
			$tmp = fopen($dig.'index.php', 'w');
			fclose($tmp);
		}
	}

	//default Apps page
	$ps=explode(",",get_option("zing_webshop_pages"));
	update_option("zing_apps_player_page",$ps[0]);

	//Copy cats, product & order data to data subsdirectory to avoid overwritting with new releases
	zing_ws_error_handler(0,'create directories');

	if (file_exists(BLOGUPLOADDIR)) {
		$dir=BLOGUPLOADDIR.'zingiri-web-shop';
		if (!file_exists($dir)) {
			mkdir($dir);
			chmod($dir,0777);
		}
		foreach (array('cats' => 'cats','prodgfx' => 'prodgfx','orders' => 'orders','prodgfx/'.get_option('zing_webshop_dig') => 'digital-'.get_option('zing_webshop_dig')) as $subori => $subdir) {
			$dir=BLOGUPLOADDIR.'zingiri-web-shop/'.$subdir.'/';
			$ori=ZING_DIR.$subori.'/';
			if (!file_exists($dir)) {
				mkdir($dir);
				chmod($dir,0777);
			}
			if (file_exists($ori)) {
				if ($handle = opendir($ori)) {
					while (false !== ($file = readdir($handle))) {
						if (strstr($file,'.php') || strstr($file,'.jpg') || strstr($file,'.png') || strstr($file,'.gif') || strstr($file,'.pdf')) {
							copy($ori.$file,$dir.$file);
						}
					}
					closedir($handle);
				}
			}
		}
	}

	zing_ws_error_handler(0,'completed');
	restore_error_handler();

}

/**
 * Deactivation of web shop
 * @return void
 */
function zing_deactivate() {
	if (function_exists('zing_apps_player_deactivate')) zing_apps_player_deactivate();
}

/**
 * Uninstallation of web shop: removal of database tables
 * @return void
 */
function zing_uninstall() {
	global $wpdb;

	set_error_handler("zing_ws_error_handler");
	error_reporting(E_ALL & ~E_NOTICE);

	$wpdb->show_errors();

	$prefix=$wpdb->prefix."zing_";
	$rows=$wpdb->get_results("show tables like '".$prefix."%'",ARRAY_N);
	if (count($rows) > 0) {
		foreach ($rows as $id => $row) {
			if (strpos($row[0],'_mybb_')===false && strstr($row[0],'_ost_')===false) {
				$query="drop table ".$row[0];
				$wpdb->query($query);
			}
		}
	}
	$ids=get_option("zing_webshop_pages");
	$ida=explode(",",$ids);
	foreach ($ida as $id) {
		if (!empty($id)) {
			wp_delete_post($id,true);
			$query="delete from ".$wpdb->prefix."postmeta where meta_key in ('zing_page','zing_action','zing_security')";
			echo $query.'<br />';
			$wpdb->query($query);
		}
	}
	delete_option("zing_webshop_version");
	delete_option("zing_webshop_pages");
	delete_option("zing_webshop_dig");
	delete_option('zing_ws_widget_options');
	delete_option('zing_ws_news');

	//remove uploads sub-directory
	rmdir(BLOGUPLOADDIR.'zingiri-web-shop');

	if (function_exists('zing_apps_player_uninstall')) zing_apps_player_uninstall(false);

	restore_error_handler();
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
	global $product_url,$orders_url,$brands_url;
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
	global $charset;
	global $zing_loaded;
	global $menus;
	global $integrator;
	global $zing;
	global $zingPrompts;

	$matches=array();

	//start logging
	error_reporting(E_ALL ^ E_NOTICE); // ^ E_NOTICE
	set_error_handler("user_error_handler");

	require (ZING_LOC."./zing.readcookie.inc.php");      // read the cookie

	$to_include="";

	switch ($process)
	{
		case "content":
			//apps player integration
			if (isset($_GET['zfaces']) || isset($_POST['zfaces'])) {
				if (!$zing_loaded) {
					require (ZING_LOC."./zing.startmodules.inc.php");
					$zing_loaded=TRUE;
				}
				return $content;
			}

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
			elseif (preg_match('/\[zing-ws:(.*)&amp;(.*)=(.*)\]/',$content,$matches)==1) { //[zing-ws:page&x=y]
				list($prefix,$postfix)=preg_split('/\[zing-ws:(.*)\]/',$content);
				$_GET['page']=$matches[1];
				if ($matches[2]=='cat') $_GET['action']='list';
				$_GET[$matches[2]]=$matches[3];
			}
			elseif (preg_match('/\[zing-ws:(.*)\]/',$content,$matches)==1) { //[zing-ws:page]
				list($prefix,$postfix)=preg_split('/\[zing-ws:(.*)\]/',$content);
				$_GET['page']=$matches[1];
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
		case "init":
			break;
	}

	if (!$zing_loaded)
	{
		require (ZING_LOC."./zing.startmodules.inc.php");
		$zing_loaded=TRUE;
	} else {
		require (ZING_DIR."./includes/readvals.inc.php");        // get and post values
	}

	if ($to_include=="loadmain.php" && ($page=='logout' || ($page=='login' && !$_GET['lostlogin'])))
	{
		//stop logging
		restore_error_handler();
		header('Location:'.ZING_HOME.'/index.php?page='.$page);
		exit;
	}
	elseif ($to_include) {
		echo $prefix;
		if ($process=='content') echo '<div class="zing_ws_page" id="zing_ws_'.$_GET['page'].'">';
		include($scripts_dir.$to_include);
		if ($process=='content') echo '</div>';
		echo $postfix;
		//stop logging
		restore_error_handler();
	}
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
	echo '<script type="text/javascript" language="javascript">';
	echo "var wsURL='".ZING_URL."fws/ajax/';";
	echo '</script>';

	if (ZING_PROTOTYPE) {
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/checkout.proto.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/cart.proto.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/search.proto.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/cookie.proto.js"></script>';
	} elseif (ZING_JQUERY) {
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/jquery-ui-1.7.3.custom.min.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/lib.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/Class-0.0.2.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/cookie.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/checkout.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/cart.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/search.jquery.js"></script>';
	}
	echo '<link rel="stylesheet" type="text/css" href="' . ZING_URL . 'zing.css" media="screen" />';

	echo '<link rel="stylesheet" href="' . ZING_URL . 'fws/addons/lightbox/lightbox.css" type="text/css" media="screen" />';
	echo '<script type="text/javascript" src="' . ZING_URL . 'fws/addons/lightbox/lightbox.js"></script>';
}

function zing_ws_header_custom()
{
	echo '<link rel="stylesheet" type="text/css" href="' . BLOGUPLOADURL . 'zingiri-web-shop/custom.css" media="screen" />';
}

/**
 * Sidebar general menu widget
 * @param $args
 * @return unknown_type
 */
function widget_sidebar_general($args) {

	global $txt;
	zing_main("init");
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $txt['menu14'];
	echo $after_title;
	echo '<div id="zing-sidebar-general">';
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
	zing_main("init");
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $txt['menu15'];
	echo $after_title;
	echo '<div id="zing-sidebar-products">';
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
	zing_main("init");
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $txt['menu2'];
	echo $after_title;
	echo '<div id="zing-sidebar-cart">';
	zing_main("sidebar","cart");
	echo '</div>';
	echo $after_widget;
}

/**
 * Sidebar cart menu widget
 * @param $args
 * @return unknown_type
 */
function widget_sidebar_search($args) {
	global $txt;
	zing_main("init");
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $txt['menu4'];
	echo $after_title;
	echo '<div id="zing-sidebar-search">';
	zing_main("sidebar","search");
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
	if (ZING_PROTOTYPE || ZING_JQUERY) register_sidebar_widget(__('Zingiri Web Shop Search'), 'widget_sidebar_search');
	register_widget_control(__('Zingiri Web Shop Search'), 'widget_control_search');
}

function widget_control_search() {
	$data = get_option('zing_ws_widget_options');
	echo '<p><label>Size of search input field<input name="ws_zing_search_size" type="text" value="'.$data['search_size'].'" /></label></p>';
	if (isset($_POST['ws_zing_search_size'])){
		$data['search_size'] = attribute_escape($_POST['ws_zing_search_size']);
		update_option('zing_ws_widget_options', $data);
	}
}

/**
 * Initialization of page, action & page_id arrays
 * @return unknown_type
 */
function zing_init()
{
	session_start();

	if (is_admin() || !defined("ZING_PROTOTYPE") || ZING_PROTOTYPE) {
		wp_enqueue_script('prototype');
		wp_enqueue_script('scriptaculous');
	} elseif (!defined("ZING_JQUERY") || ZING_JQUERY) {
		wp_enqueue_script('jquery');
	}

	ob_start();

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
	global $integrator;

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
	if ((!empty($_GET['page_id'])) && ($_GET['page_id']==zing_page_id("logout")) || (!empty($_GET['page']) && $_GET['page']=="logout"))
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
function zing_exclude_pages( $pages )
{
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) return $pages;

	Global $dbtablesprefix;
	Global $cntry;
	Global $lang;
	Global $lang2;
	Global $lang3;

	//require (ZING_DIR."./includes/settings.inc.php");        // database settings

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
		elseif ($security == "6" && ($iscustomer || $isadmin)) { //should really be shown only if something in cart
			$show=true;
		}
		elseif ($security == "9" && $loggedin && $isadmin) {
			$show=true;
		}
		if (!$show || get_option("zing_ws_show_menu_".$page->ID)=="No")
		{
			unset($pages[$i]);
			$unsetpages[$page->ID]=true;
		}
	}

	return $pages;

	/*
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
		*/
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
	//Please contact us if you wish to remove the Zingiri and FWS logos from the footer
	echo '<center style="position:relative;clear:both;font-size:smaller;margin-top:5px">';
	echo '<a href="http://www.zingiri.com" alt="Zingiri Web Shop">';
	echo '<img src="'.ZING_URL.'/zingiri-logo.png" height="35"/>';
	echo '</a>';
	echo '</center>';
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

function zing_login($loginname) {
	global $dbtablesprefix;

	$query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE `LOGINNAME`=%s", quote_smart($loginname));
	$sql = mysql_query($query) or die(mysql_error());
	if ($row = mysql_fetch_row($sql)) {
		$id = $row[0];
		$name = $row[1];
		$pass = $row[2];
		$group = $row[13];
		if (isset($_COOKIE['fws_guest'])) {
			$fws_cust = $_COOKIE['fws_guest'];
			$sessionid = $fws_cust; // read the sessionid

			// now check if this guest has products in his basket
			$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (CUSTOMERID = ".$sessionid." AND STATUS = 0) ORDER BY ID";
			$sql = mysql_query($query) or die(mysql_error());
			while ($row = mysql_fetch_row($sql)) {
				$update_query = "UPDATE `".$dbtablesprefix."basket` SET `CUSTOMERID` = ".$id." WHERE ID = '".$row[0]."'";
				$update_sql = mysql_query($update_query) or die(mysql_error());
			}
			// now kill the cookie
			setcookie ("fws_guest", "", time() - 3600, '/');
		}

		$cookie_data = $name.'-'.$id.'-'.md5($pass); //name userid and encrypted password
			
		// store IP
		$query = "UPDATE `".$dbtablesprefix."customer` SET `IP` = '".GetUserIP()."' WHERE `ID`=".$id;
		$sql = mysql_query($query) or die(mysql_error());
		// make acccesslog entry
		$query = sprintf("INSERT INTO ".$dbtablesprefix."accesslog (login, time, succeeded) VALUES(%s, '".date("F j, Y, g:i a")."', '1')", quote_smart($loginname));
		$sql = mysql_query($query) or die(mysql_error());

		setcookie ("fws_cust",$cookie_data, 0, '/'); //time()+3600
	}
}

function zing_logout() {
	setcookie ("fws_cust","", time() - 3600, '/');
}

function zing_check_password($check,$password,$hash,$user_id) {
	global $dbtablesprefix;

	if (!$check) { //the user could be using his old password, pre Web Shop to Wordpress migration
		$user =  new WP_User($user_id);
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE `LOGINNAME`=%s AND `PASSWORD`=%s", quote_smart($user->data->user_login), quote_smart(md5($password)));
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_row($sql)) return true;
		else return false;
	} else return $check;
}

function zing_profile($user_id) {
	$user=new WP_User($user_id);
	$user_data=$user->data;
	$db=new db();

	$row['LASTNAME']=$user_data->user_lastname;
	$row['INITIALS']=$user_data->user_firstname;
	$row['EMAIL']=$user_data->user_email;
	$row['DATE_UPDATED']=date('Y-m-d');
	$pass=$_POST['pass1'];
	if ($pass != '') $row['PASSWORD']=md5($pass);

	if ($user->has_cap('level_5')) $row['GROUP']='ADMIN';
	else $row['GROUP']='CUSTOMER';

	if ($db->readRecord('customer',array('LOGINNAME' => $user_data->user_login))) {
		$db->updateRecord('customer',array('LOGINNAME' => $user_data->user_login), $row);
		/*
		 $_GET['page']='apps';
		 $_GET['zfaces']='form';
		 $_GET['form']='profile1';
		 $_GET['action']='edit';
		 $_GET['step']='save';
		 unset($_GET['showform']);
		 $_GET['no_redirect']=1;
		 $user=get_userdata($user_id);
		 $_GET['id']=getCustomerByLogin($user->user_login);
		 zing_main('content');
		 zing_apps_player_content('content');
		 $_SESSION['zing']['ProfileNextStep']="";
		 */
	} else {
		$row['LOGINNAME']=$user_data->user_login;
		$row['DATE_CREATED']=date('Y-m-d');
		$db->insertRecord('customer',"",$row);
	}
}
/*
 function zing_profile_show($user_id) {
 zing_profile_edit($user_id);
 }

 function zing_profile_edit($user_id) {
 echo '<link rel="stylesheet" type="text/css" href="'.ZING_APPS_PLAYER_URL.'css/apps_wp_admin.css" />';

 if (isset($_GET['user_id'])) { $id=(int) $_GET['user_id']; 	$user=get_userdata($id); }
 elseif (isset($_POST['user_id'])) { $id=(int) $_POST['user_id']; $user=get_userdata($id); }
 else $user=$user_id;
 $_GET['page']='apps';
 $_GET['zfaces']='form';
 $_GET['form']='profile1';
 $_GET['action']='edit';
 $_GET['step']=$_SESSION['zing']['ProfileNextStep'];
 $_GET['showform']='edit';
 $_GET['no_form']=1;
 $_GET['id']=getCustomerByLogin($user->user_login);
 zing_main('content');
 zing_apps_player_content('content');
 $_SESSION['zing']['ProfileNextStep']="";
 }
 */

/*
 * Check errors before committing user data
 */
/*
 function zing_profile_check_errors(&$errors, $update, &$user) {
 global $zfform,$zfSuccess;

 $_GET['page']='apps';
 $_GET['zfaces']='form';
 $_GET['form']='profile1';
 $_GET['action']='edit';
 if ($_POST['action']=='update') $_GET['step']='check';
 else $_GET['step']="";
 $_GET['showform']=false;
 $_GET['id']=getCustomerByLogin($user->user_login);
 zing_main('content');
 zing_apps_player_content('content');
 if (!$zfSuccess) $errors->errors['invalid']=array('Errors');
 $_SESSION['zing']['ProfileNextStep']="check";
 }
 */

function zing_profile_pre($user_id) {
}

function zing_delete_user($id) {
	$user=get_userdata($id);
	$db=new db();
	$db->deleteRecord('customer',array('LOGINNAME' => $user->user_login));
}

function zing_ws_title($title) {
	if ($_GET['prod']) {
		error_reporting(E_ALL & ~E_NOTICE);
		ini_set('display_errors', '1');

		$prodid=$_GET['prod'];
		$db=new db();
		//echo 'select product from ##product,##category where ##product.catid=##category.id and ##product.id='.qs($prodid);
		$db->select('select `productid`,`desc` from `##product`,`##category` where ##product.catid=##category.id and ##product.id='.qs($prodid));
		if ($db->next()) {
			return $db->get('desc').' &raquo; '.$db->get('productid');
		}
	}
	return $title;
}

function zing_ws_page_title($title) {
	return $title;
}
?>