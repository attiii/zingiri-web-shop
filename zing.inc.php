<?php
/*  zing.inc.php
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
$zing_version=get_option("zing_webshop_version");
require (ZING_LOC."./zing.startfunctions.inc.php");
require_once(dirname(__FILE__) . '/'.ZING_CMS.'.integrator.class.php');
require(dirname(__FILE__).'/'.ZING_CMS.'.hooks.inc.php');

if (!defined("ZING_DIG") && get_option('zing_webshop_dig')!="") {
	define("ZING_DIG",BLOGUPLOADDIR.'/zingiri-web-shop/digital-'.get_option('zing_webshop_dig').'/');
}

require_once(dirname(__FILE__) . '/controlpanel.php');

function zing_admin_notices() {
	$zing_version=get_option("zing_webshop_version");

	if (!$zing_version) {
		if ($_GET['page']!='zingiri-web-shop' && ZING_CMS=="wp")
		$message='Zingiri Web Shop is almost ready. You need to launch the <a href="admin.php?page=zingiri-web-shop">installation</a> from the integration page.';
		else
		$message='Zingiri Web Shop is almost ready. You need to launch the installation by clicking the Install button below.';
	} elseif ($zing_version != ZING_VERSION) {
		if ($_GET['page']!='zingiri-web-shop' && ZING_CMS=="wp")
		$message='You downloaded Zingiri Web Shop version '.ZING_VERSION.' and need to <a href="admin.php?page=zingiri-web-shop">upgrade</a> your database (currently at version '.$zing_version.') from the integration page.';
		else
		$message='You downloaded Zingiri Web Shop version '.ZING_VERSION.' and need to upgrade your database (currently at version '.$zing_version.') by clicking the Upgrade button below.';
	}
	if ($message) echo "<div id='zing-warning' style='background-color:greenyellow' class='updated fade'><p><strong>".$message."</strong> "."</p></div>";


}

/**
 * Check if the web shop has been properly activated
 * @return boolean
 */
function zing_check() {
	global $lang_dir;

	$connected=false;
	if (ZING_CMS=='wp') {
		require(ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php');
		require(ABSPATH.'wp-admin/includes/class-wp-filesystem-ftpext.php');
		if (!defined('FS_CONNECT_TIMEOUT')) define('FS_CONNECT_TIMEOUT',30);
		if (defined('FTP_HOST') && defined('FTP_USER') && defined('FTP_PASS') && ($f=new WP_Filesystem_FTPext(array('hostname'=>FTP_HOST,'username'=>FTP_USER,'password'=>FTP_PASS)))) $connected=true;
		if ($connected) $connected=@$f->connect();
	}

	$errors=array();
	$warnings=array();
	$files=array();
	$dirs=array();
	$zing_version=get_option("zing_webshop_version");

	$files[]=ZING_LOC.'log.txt';
	//$files[]=ZING_DIR.'banned.txt';

	foreach ($files as $file) {
		if ($connected)  @$f->chmod($file,0666);
		if (!is_writable($file)) $warnings[]='File '.$file.' is not writable, please chmod to 666';
	}

	$dirs[]=BLOGUPLOADDIR;
	$dirs[]=ZING_DIR.'addons/captcha';
	foreach ($dirs as $file) {
		if ($connected)  @$f->chmod($file,0777);
		if (!is_writable($file)) $warnings[]='Directory '.$file.' is not writable, please chmod to 777';
	}

	if ($zing_version) {
		$dirs=array();
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop';
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop/prodgfx';
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop/cats';
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop/orders';
		$dirs[]=BLOGUPLOADDIR.ZING_SLUG.'/cache';
		$dirs[]=BLOGUPLOADDIR.'zingiri-web-shop/digital-'.get_option('zing_webshop_dig');

		foreach ($dirs as $file) {
			if (!file_exists($file)) $warnings[]='Directory '.$file. " doesn't exist";
			else {
				if ($connected)  @$f->chmod($dirs,0777);
				if (!is_writable($file)) $warnings[]='Directory '.$file.' is not writable, please chmod to 777';
			}
		}
	}

	if (phpversion() < '5')	$errors[]="You are running PHP version ".phpversion().". You require PHP version 5 or higher to install the Web Shop.";
	if (ini_get("zend.ze1_compatibility_mode")) $warnings[]="You are running PHP in PHP 4 compatibility mode. The PDF invoice functionality requires this mode to be turned off.";
	if (get_magic_quotes_gpc()) $warnings[]='Turn off magic quotes on your installation. Read more about why you should disable this setting <a href="http://www.php.net/manual/en/security.magicquotes.php">here</a>.';
	if (ini_get('register_globals')) $warnings[]="You have set register globals on. It is highly recommended to turn this off as it poses a serious security risk.";

	if (ZING_CMS=='dp') {
		global $db_url;
		if (strpos($db_url,'mysqli') !== false) $errors[]="Mysqli is not supported, please change to Mysql.";
	}

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

function zing_install() {
	global $zingPrompts,$dbtablesprefix;

	$player=false;

	zing_ws_error_handler_truncate();
	set_error_handler("zing_ws_error_handler");
	$wsper=error_reporting(E_ALL & ~E_NOTICE);

	$prefix=$dbtablesprefix;
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
		$execs=array();
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,".sql")) {
				$f=explode("-",$file);
				$v=str_replace(".sql","",$f[1]);
				if ($zing_version < $v) {
					$files[]=array(dirname(__FILE__).'/fws/db/'.$file,$v);
				}
			} elseif (strstr($file,".php")) {
				$f=explode("-",$file);
				$v=str_replace(".php","",$f[1]);
				if ($zing_version < $v) {
					$execs[]=dirname(__FILE__).'/fws/db/'.$file;
				}
			}
		}
		closedir($handle);
		asort($files);
		asort($execs);
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
							$query = "";
						}
					}
				}
			}
		}
	}

	//Running scripts
	zing_ws_error_handler(0,'Running scripts');
	if (count($execs) > 0) {
		foreach ($execs as $exec) {
			require($exec);
		}
	}

	//Load Apps forms if not loaded yet
	if (!$player) {
		zing_ws_error_handler(0,'Loading Apps forms');
		zing_apps_player_install();
		$player=true;
	}

	//Update default settings
	if (!$zing_version) {
		$query="update ".$prefix."settings set sales_mail='".get_bloginfo('admin_email')."' where id=1";
		mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
		$query="update ".$prefix."settings set webmaster_mail='".get_bloginfo('admin_email')."' where id=1";
		mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
		$query="update ".$prefix."settings set shopname='".get_bloginfo('name')."' where id=1";
		mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
		$query="update ".$prefix."settings set shopurl='".get_option('home')."' where id=1";
		mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
	}

	//Load language files
	zing_ws_error_handler(0,'load language files');

	if (!isset($zingPrompts)) $zingPrompts=new zingPrompts();
	$zingPrompts->installAllLanguages();

	//Create default pages
	zing_ws_error_handler(0,'create default pages');
	zing_ws_install_default_pages($zing_version);

	//Define roles & set current user to admin
	if (function_exists('zing_ws_install_roles')) zing_ws_install_roles();

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

	//Copy cats, product & order data to data subsdirectory to avoid overwritting with new releases
	zing_ws_error_handler(0,'create directories');

	if (file_exists(BLOGUPLOADDIR)) {
		$dir=BLOGUPLOADDIR.'zingiri-web-shop';
		if (!file_exists($dir)) {
			mkdir($dir);
			chmod($dir,0777);
		}
		foreach (array('cats' => 'cats','cache' => 'cache','prodgfx' => 'prodgfx','orders' => 'orders','prodgfx/'.get_option('zing_webshop_dig') => 'digital-'.get_option('zing_webshop_dig')) as $subori => $subdir) {
			$dir=BLOGUPLOADDIR.'zingiri-web-shop/'.$subdir.'/';
			$ori=ZING_DIR.$subori.'/';
			if (!file_exists($dir)) {
				mkdir($dir);
				chmod($dir,0777);
			}
			if (is_writable($dir)) {
				if ($fh = fopen($dir.'/index.php', 'a')) {
					fwrite($fh,"");
					fclose($fh);
				}
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

	if (function_exists('zing_ws_pro_install')) zing_ws_pro_install();

	zing_ws_error_handler(0,'completed');
	restore_error_handler();
	error_reporting($wsper);
}

/**
 * Uninstallation of web shop: removal of database tables
 * @return void
 */
function zing_uninstall() {
	global $wpdb,$dbtablesprefix;

	set_error_handler("zing_ws_error_handler");
	$wsper=error_reporting(E_ALL & ~E_NOTICE);

	$prefix=$dbtablesprefix;
	$query="show tables like '".$prefix."%'";
	$sql = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_row($sql)) {
		print_r($row);
		if (strpos($row[0],'_mybb_')===false && strstr($row[0],'_ost_')===false) {
			$query="drop table ".$row[0];
			mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
		}
	}

	zing_ws_uninstall_delete_pages();
	delete_option("zing_webshop_version");
	delete_option("zing_webshop_pages");
	delete_option("zing_webshop_dig");
	delete_option('zing_ws_widget_options');
	delete_option('zing_ws_news');

	//remove uploads sub-directory
	rmdir(BLOGUPLOADDIR.'zingiri-web-shop');

	if (function_exists('zing_apps_player_uninstall')) zing_apps_player_uninstall(false);

	restore_error_handler();
	error_reporting($wsper);
}

function zing_ws_is_shop_page($pid) {
	$isShopPage=false;
	$ids=get_option("zing_webshop_pages");
	$ida=explode(",",$ids);
	foreach ($ida as $id) {
		if (!empty($id) && $pid==$id) $isShopPage=true;
	}
	return $isShopPage;
}

/**
 * Main function handling content, footer and sidebars
 * @param $process
 * @param $content
 * @return unknown_type
 */
function zing_main($process,$content="") {
	require(ZING_GLOBALS);

	$matches=array();

	require (ZING_LOC."./zing.readcookie.inc.php");      // read the cookie

	$to_include="";

	switch ($process)
	{
		case "content":
			//apps player integration
			if (isset($_GET['zfaces']) || isset($_POST['zfaces'])) {
				if (!$zing_loaded) {
					require (ZING_LOC."./startmodules.inc.php");
					$zing_loaded=TRUE;
				}
				return $content;
			}

			$cf=get_post_custom();

			if (isset($_GET['page'])) {
				//do nothing, page already set
			}  elseif (isset($cf['zing_page'])) {
				$_GET['page']=$cf['zing_page'][0];
				if (isset($cf['zing_action']))
				{
					$_GET['action']=$cf['zing_action'][0];
				}
			} elseif (preg_match('/\[zing-ws:(.*)&amp;(.*)=(.*)\]/',$content,$matches)==1) { //[zing-ws:page&x=y]
				list($prefix,$postfix)=preg_split('/\[zing-ws:(.*)\]/',$content);
				$_GET['page']=$matches[1];
				if ($matches[2]=='cat') $_GET['action']='list';
				$_GET[$matches[2]]=$matches[3];
			} elseif (preg_match('/\[zing-ws:(.*)\]/',$content,$matches)==1) { //[zing-ws:page]
				list($prefix,$postfix)=preg_split('/\[zing-ws:(.*)\]/',$content);
				$_GET['page']=$matches[1];
			} elseif (preg_match('/\[zing-ws-(.*):(.*)\]/',$content,$matches)==1) { //[zing-ws:page]
				$_GET['page']='parse';
			} else return $content;
			if (isset($cf['cat'])) {
				$_GET['cat']=$cf['cat'][0];
			}

			$to_include="loadmain.php";
			break;
		case "sidebar":
			$to_include="menu_".$content.".php";
			break;
		case "init":
			break;
	}

	//start logging
	$wsper=error_reporting(E_ALL ^ E_NOTICE); // ^ E_NOTICE
	set_error_handler("user_error_handler");

	if (!$zing_loaded) {
		require (ZING_LOC."./startmodules.inc.php");
		$zing_loaded=TRUE;
	} else {
		require (ZING_DIR."./includes/readvals.inc.php");        // get and post values
	}

	if ($to_include=="loadmain.php" && ($page=='logout' || ($page=='login' && !$_GET['lostlogin']))) {
		//stop logging
		restore_error_handler();
		error_reporting($wsper);
		header('Location:'.ZING_HOME.'/index.php?page='.$page);
		exit;
	}
	elseif ($to_include) {
		echo $prefix;
		if ($process=='content') echo '<div class="zing_ws_page" id="zing_ws_'.$_GET['page'].'">';
		include($scripts_dir.$to_include);
		if ($process=='content') echo '</div>';
		echo $postfix;
		if (!wsIsAdminPage() && $process=='content' && get_option('zing_ws_logo')=='pf') zing_display_logo();
		//stop logging
		restore_error_handler();
		error_reporting($wsper);
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
 * The footer is automatically inserted for Artisteer generated themes.
 * For other themes, the function zing_footer should be called from inside the theme.
 * @param $footer
 * @return unknown_type
 */
function zing_footer($footer="")
{
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) return $footer;
	if (get_option('zing_ws_logo')!='sf' && get_option('zing_ws_logo')!='') return $footer;
	zing_display_logo();
}

function zing_display_logo()
{
	//Please contact us if you wish to remove the Zingiri logo
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


?>