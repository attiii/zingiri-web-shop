<?php
$zing_version=get_option("zing_webshop_version");

define('APHPS_HTML_EDITOR','wordpress');
if (!defined("ZING_DIG") && get_option('zing_webshop_dig')!="") {
	define("ZING_DIG",ZING_UPLOADS_DIR.'digital-'.get_option('zing_webshop_dig').'/');
}

function zing_admin_notices() {
	$zing_version=get_option("zing_webshop_version");
	$zing_version_pro=get_option("zing_ws_pro_version");

	$message='';
	if (defined('WP_ZINGIRI_LIVE') && $zing_version != ZING_VERSION && get_current_blog_id() != 1) {
		zing_install();
		if ($zing_version) $message='Zingiri Web Shop has been upgraded! Click <a href="'.get_option('home').'">here</a> to continue ...';
		else $message='Zingiri Web Shop has been installed! Click <a href="'.get_option('home').'">here</a> to continue ...';
	} elseif (!$zing_version) {
		if (!isset($_GET['page']) || ($_GET['page']!='zingiri-web-shop') && ZING_CMS=="wp")
		$message='Zingiri Web Shop is almost ready. You need to launch the <a href="admin.php?page=zingiri-web-shop">installation</a> from the integration page.';
		else
		$message='Zingiri Web Shop is almost ready. You need to launch the installation by clicking the Install button below.';
	} elseif (!wsVersion()) {
		if ((!isset($_GET['page']) || ($_GET['page']!='zingiri-web-shop')) && ZING_CMS=="wp") {
			$message='You downloaded Zingiri Web Shop version '.ZING_VERSION;
			//$message.=get_option('zing_webshop_pro') ? '/'.ZING_WS_PRO_VERSION : '';
			$message.=' and need to <a href="admin.php?page=zingiri-web-shop">upgrade</a> your database (currently at version '.$zing_version;
			//$message.=get_option('zing_webshop_pro') ? '/'.$zing_version_pro : '';
			$message.=') from the integration page.';
		} else {
			$message='You downloaded Zingiri Web Shop version '.ZING_VERSION;
			//$message.=get_option('zing_webshop_pro') ? '/'.ZING_WS_PRO_VERSION : '';
			$message.=' and need to upgrade your database (currently at version '.$zing_version;
			//$message.=get_option('zing_webshop_pro') ? '/'.$zing_version_pro : '';
			$message.=') by clicking the Upgrade button below.';
		}
	}
	if ($message) echo "<div id='zing-warning' style='background-color:greenyellow' class='updated fade'><p><strong>".$message."</strong> "."</p></div>";
	echo "<div id='zing-warning' style='background-color:greenyellow' class='updated fade'><p><strong>Please note that the Ideal gateway is no longer include with Web Shop, you can download it for free <a href=\"https://go.zingiri.com/cart.php?gid=18\" target=\"_blank\">here</a>.</strong> "."</p></div>";
}



function zing_ws_error_handler($severity, $msg, $filename="", $linenum=0) {
	if (is_array($msg)) $msg=print_r($msg,true);
	$myFile = APHPS_LOG_FILE;
	if ($fh = fopen($myFile, 'a')) {
		$time=date('Y-m-d h:i:s');
		if (function_exists('microtime')) {
			list($usec,$sec)=explode(" ",microtime());
			$time.=':'.round($usec*100,0);
		}
		fwrite($fh, $time.' '.$msg.' ('.$filename.'-'.$linenum.')'."\r\n");
		fclose($fh);
	}
}
function zing_ws_error_handler_truncate() {
	$myFile = APHPS_LOG_FILE;
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

	if (!defined("DB_PREFIX")) define("DB_PREFIX",$dbtablesprefix);

	zing_ws_error_handler(0,'DB_PREFIX:'.DB_PREFIX);

	$zing_version=get_option("zing_webshop_version");

	zing_db_install($zing_version,dirname(__FILE__).'/fws/db/',$player);

	//Load Apps forms if not loaded yet
	if (!$player) {
		zing_ws_error_handler(0,'Loading Apps forms');
		if ($zing_version) zing_apps_player_install();
		else zing_apps_player_install('1.2.1');
		$player=true;
	}

	//Update default settings
	if (!$zing_version) {
		$query="update ".$dbtablesprefix."settings set sales_mail='".get_bloginfo('admin_email')."' where id=1";
		mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
		$query="update ".$dbtablesprefix."settings set webmaster_mail='".get_bloginfo('admin_email')."' where id=1";
		mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
		$query="update ".$dbtablesprefix."settings set shopname='".get_bloginfo('name')."' where id=1";
		mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
		$query="update ".$dbtablesprefix."settings set shopurl='".get_option('home')."' where id=1";
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
	$dig=ZING_UPLOADS_DIR.'digital-'.get_option('zing_webshop_dig').'/';
	if (!is_dir($dig)) {
		if (mkdir($dig)) {
			$tmp = fopen($dig.'index.php', 'w');
			fclose($tmp);
		}
	}

	//Copy cats, product & order data to data subsdirectory to avoid overwritting with new releases
	zing_ws_error_handler(0,'create directories');

	if (file_exists(BLOGUPLOADDIR)) {
		$dir=ZING_UPLOADS_DIR;
		if (!file_exists($dir)) {
			mkdir($dir);
			chmod($dir,0777);
		}
		if (is_writable($dir)) {
			if ($fh = fopen($dir.'/index.php', 'w')) {
				fwrite($fh,"");
				fclose($fh);
			}
		}

		foreach (array('temp' => 'temp','cats' => 'cats','cache' => 'cache','prodgfx' => 'prodgfx','orders' => 'orders','prodgfx/'.get_option('zing_webshop_dig') => 'digital-'.get_option('zing_webshop_dig')) as $subori => $subdir) {
			$dir=ZING_UPLOADS_DIR.$subdir.'/';
			$ori=ZING_DIR.$subori.'/';
			if (!file_exists($dir)) {
				$createDir=true;
				mkdir($dir);
				chmod($dir,0777);
			} else {
				$createDir=false;
			}
			if (is_writable($dir)) {
				if ($fh = fopen($dir.'/index.php', 'w')) {
					fwrite($fh,"");
					fclose($fh);
				}
			}
			if (file_exists($ori) && ($createDir || !$zing_version)) { //only for new installations
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

	update_option("zing_webshop_version",ZING_VERSION);

	zing_ws_error_handler(0,'completed');
	restore_error_handler();
	error_reporting($wsper);
}

function zing_db_uninstall($dir) {
	zing_db_install('',$dir);
}

function zing_db_install($zing_version,$dir,&$player=true) {
	global $dbtablesprefix;

	//Look for baseline version
	if ($handle = opendir($dir)) {
		if (!$zing_version) { //look for highest baseline
			$baselineVersion='';
			while (false !== ($file = readdir($handle))) {
				if (strstr($file,".sql") && strstr($file,"init-")) {
					$f=explode("-",$file);
					$v=str_replace(".sql","",$f[1]);
					if ($v > $baselineVersion) $baselineVersion=$v;
				}
			}
		} else {
			$baselineVersion=$zing_version;
		}
		closedir($handle);
	}

	if ($handle = opendir($dir)) {
		$files=array();
		$execs=array();
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,".sql") && !strstr($file,"init-")) {
				$f=explode("-",$file);
				$v=str_replace(".sql","",$f[1]);
				if ($baselineVersion < $v) {
					$files[]=array($dir.$file,$v);
				}
			} elseif (strstr($file,".php") && ($file != 'index.php')) {
				$f=explode("-",$file);
				$v=str_replace(".php","",$f[1]);
				if ($baselineVersion < $v) {
					$execs[]=$dir.$file;
				}
			}
		}
		closedir($handle);
		asort($files);
		if(!$zing_version) array_unshift($files,array($dir.'init-'.$baselineVersion.'.sql',$baselineVersion));
		asort($execs);
		if (count($files) > 0) {
			mysql_query('SET storage_engine=InnoDB');
			foreach ($files as $afile) {
				list($file,$v)=$afile;
				zing_ws_error_handler(0,'Process '.$file);
				if ($zing_version && $v>='1.2.7' && !$player) {
					zing_apps_player_install();
					$player=true;
					zing_ws_error_handler(0,'continue with:'.$file);
				}
				$file_content = file($file);
				$query = "";
				foreach($file_content as $sql_line) {
					$tsl = trim($sql_line);
					if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
						if (str_replace("##", $dbtablesprefix, $sql_line) == $sql_line) {
							$sql_line = str_replace("CREATE TABLE `", "CREATE TABLE `".$dbtablesprefix, $sql_line);
							$sql_line = str_replace("CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS`".$dbtablesprefix, $sql_line);
							$sql_line = str_replace("INSERT INTO `", "INSERT INTO `".$dbtablesprefix, $sql_line);
							$sql_line = str_replace("ALTER TABLE `", "ALTER TABLE `".$dbtablesprefix, $sql_line);
							$sql_line = str_replace("UPDATE `", "UPDATE `".$dbtablesprefix, $sql_line);
							$sql_line = str_replace("TRUNCATE TABLE `", "TRUNCATE TABLE `".$dbtablesprefix, $sql_line);
						} else {
							$sql_line = str_replace("##", $dbtablesprefix, $sql_line);
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

}
/**
 * Uninstallation of web shop: removal of database tables
 * @return void
 */
function zing_uninstall() {
	global $wpdb,$dbtablesprefix;

	set_error_handler("zing_ws_error_handler");
	$wsper=error_reporting(E_ALL & ~E_NOTICE);

	$query="show tables like '".$dbtablesprefix."%'";
	$sql = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_row($sql)) {
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
	delete_option('zingiri-web-shop-support-us');

	//remove uploads sub-directory
	rmdir(ZING_UPLOADS_DIR);

	if (function_exists('zing_apps_player_uninstall')) zing_apps_player_uninstall(false);

	if (function_exists('zing_ws_pro_uninstall')) zing_ws_pro_uninstall();

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
	global $saasRet,$aphps;

	require(ZING_GLOBALS);

	$matches=array();

	require (ZING_LOC."./zing.readcookie.inc.php");      // read the cookie

	$to_include="";

	
	switch ($process)
	{
		case "content":
			if (!$zing_loaded) {
				require_once(ZING_LOC."./startmodules.inc.php");
				$zing_loaded=TRUE;
			}
			//apps player integration
			if (isset($_GET['zfaces']) || isset($_POST['zfaces'])) {
				return $content;
			}
			if (isset($post)) $cf=get_post_custom();
			if (isset($_GET['page'])) {
				$page=aphpsSanitize($_GET['page'],'string');
			}  elseif (isset($cf['zing_page'])) {
				$page=$_GET['page']=$cf['zing_page'][0];
				if (isset($cf['zing_action']))
				{
					$_GET['action']=$cf['zing_action'][0];
				}
			} elseif (preg_match('/\[zing-ws:(.*)&amp;(.*)=(.*)\]/',$content,$matches)==1) { //[zing-ws:page&x=y]
				list($prefix,$postfix)=preg_split('/\[zing-ws:(.*)\]/',$content);
				$page=$_GET['page']=$matches[1];
				if ($matches[2]=='cat') $_GET['action']='list';
				$_GET[$matches[2]]=$matches[3];
			} elseif (preg_match('/\[zing-ws:(.*)\]/',$content,$matches)==1) { //[zing-ws:page]
				list($prefix,$postfix)=preg_split('/\[zing-ws:(.*)\]/',$content);
				$page=$_GET['page']=$matches[1];
			} elseif (preg_match('/\[zing-ws-(.*):(.*)\]/',$content,$matches)==1) { //[zing-ws-action:parameters]
				$page=$_GET['page']='parse';
			} else return $content;
			if (isset($cf['cat'])) {
				$_GET['cat']=$cf['cat'][0];
			}
			if (!$page) $page=aphpsSanitize($_GET['page'],'string');
			$to_include="loadmain.php";
			break;
		case "sidebar":
			$to_include="menu_".$content.".php";
			break;
		case "init":
			break;
	}

	//start logging
	if (!defined('WP_DEBUG')) {
		$wsper=error_reporting(E_ALL ^ E_NOTICE); // ^ E_NOTICE
		set_error_handler("user_error_handler");
	}
	if (!$zing_loaded) {
		require_once(ZING_LOC."./startmodules.inc.php");
		$zing_loaded=TRUE;
	} else {
		require (ZING_DIR."./includes/readvals.inc.php");        // get and post values
	}

	if (!ZING_LIVE && $to_include=="loadmain.php" && (($page=='login' && !$_GET['lostlogin']))) {
		//stop logging
		if (!defined('WP_DEBUG')) {
			restore_error_handler();
			error_reporting($wsper);
		}
		header('Location:'.ZING_HOME.'/index.php?page='.$page);
		exit;
	} elseif ($to_include) {
		if (isset($prefix)) echo $prefix;
		if ($process=='content' && $page!='ajax' && $page!='downldr') echo '<div class="zing_ws_page" id="zing_ws_'.aphpsSanitize($_GET['page'],'string').'">';
		$aphps->doAction('content_before');
		include($scripts_dir.$to_include);
		$aphps->doAction('content_after');
		if ($process=='content' && $page!='ajax' && $page!='downldr') echo '</div>';
		if (isset($postfix)) echo $postfix;
		if (!wsIsAdminPage() && $process=='content' && get_option('zing_ws_logo')=='pf') zing_display_logo();
		//stop logging
	}
	if (!defined('WP_DEBUG')) {
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

function jsVars() {
	$v=array();
	$v['wsURL']=ZING_URL;
	$v['wsAjaxURL']=zurl("index.php?page=ajax")."&wscr=";
	$v['wsAnimateImage']=wsSetting('animateimage');
	$v['wsCms']=ZING_CMS;
	$v['wsLive']=isset($_REQUEST['wslive']) ? 1 : 0;

	return $v;
}

function jsScripts() {
	$v=array();
	$v[]=ZING_URL . 'fws/js/jquery-ui-1.7.3.custom.min.js';
	$v[]=ZING_URL . 'fws/js/'.APHPS_JSDIR.'/lib.jquery.js';
	$v[]=ZING_URL . 'fws/js/'.APHPS_JSDIR.'/cookie.jquery.js';
	$v[]=ZING_URL . 'fws/js/'.APHPS_JSDIR.'/checkout.jquery.js';
	$v[]=ZING_URL . 'fws/js/'.APHPS_JSDIR.'/cart.jquery.js';
	$v[]=ZING_URL . 'fws/js/'.APHPS_JSDIR.'/search.jquery.js';

	return $v;
}

function jsAdminScripts() {
	$v=array();
	$v[]=ZING_URL . 'fws/js/'.APHPS_JSDIR.'/lib.jquery.js';
	$v[]=ZING_URL . 'fws/js/'.APHPS_JSDIR.'/cookie.jquery.js';
	$v[]=ZING_URL . 'fws/js/'.APHPS_JSDIR.'/admin.jquery.js';

	return $v;
}

function jsStyleSheets() {
	$v=array();
	$v[]=ZING_URL . 'zing.css';
	if (is_admin()) $v[]=ZING_URL . 'admin.css';

	return $v;
}

function zing_ws_default_page() {
	$ids=get_option("zing_webshop_pages");
	$ida=explode(",",$ids);
	return $ida[0];
}
