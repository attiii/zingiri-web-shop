<?php
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

	if (get_option('zing_check')) $warnings[]='If you have previously installed the Zingiri Web Shop Live plugin and you want to continue using it, you should deactivate this plugin and install the <a href="http://wordpress.org/extend/plugins/ws-live/" target="_blank">Zingiri Web Shop Live plugin</a> instead.';
	
	$dirs[]=BLOGUPLOADDIR;
	foreach ($dirs as $file) {
		if ($connected)  @$f->chmod($file,0777);
		if (!is_writable($file)) $warnings[]='Directory '.$file.' is not writable, please chmod to 777';
	}

	if ($zing_version) {
		$dirs=array();
		$dirs[]=ZING_UPLOADS_DIR;
		$dirs[]=ZING_UPLOADS_DIR.'prodgfx';
		$dirs[]=ZING_UPLOADS_DIR.'cats';
		$dirs[]=ZING_UPLOADS_DIR.'orders';
		$dirs[]=ZING_UPLOADS_DIR.'cache';
		$dirs[]=ZING_UPLOADS_DIR.'digital-'.get_option('zing_webshop_dig');

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
	//if (get_magic_quotes_gpc()) $warnings[]='Turn off magic quotes on your installation. Read more about why you should disable this setting <a href="http://www.php.net/manual/en/security.magicquotes.php">here</a>.';
	if (ini_get('register_globals')) $warnings[]="You have set register globals on. It is highly recommended to turn this off as it poses a serious security risk.";

	if (ZING_CMS=='dp') {
		global $db_url;
		if (strpos($db_url,'mysqli') !== false) $errors[]="Mysqli is not supported, please change to Mysql.";
	}

	//check files hash
	/*
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
	*/

	return array('errors'=> $errors, 'warnings' => $warnings);
}

function wsVersion() {
	$s=$p=false;
	if (get_option('zing_webshop_version') == ZING_VERSION) $s=true;
	if (!get_option('zing_webshop_proX') || (get_option('zing_ws_pro_version') == ZING_WS_PRO_VERSION)) $p=true;
	if ($s && $p) return true;
	else return false;
}

function zing_ws_add_admin() {

	global $zing_ws_name, $zing_ws_options, $menus, $txt, $wpdb, $zing_version, $integrator;
	global $dbtablesprefix;
	if ($zing_version) require_once(ZING_LOC.'/startmodules.inc.php');

	if (!defined('WP_ZINGIRI_LIVE')) update_option('zing_ws_install_type','local');
	
	zing_set_options();
	if ((isset($_GET['q']) && (ZING_CMS=='dp') && strstr($_GET['q'],'admin/webshop')) || (ZING_CMS=='wp' && isset($_GET['page']) && ($_GET['page']=='zingiri-web-shop')) || (ZING_CMS=='jl' && isset($_REQUEST['option']) && ($_REQUEST['option'] == "com_zingiriwebshop")) ) {
		if( isset($_REQUEST['sync']) ) {
			if (get_option('zing_ws_install_type') == 'local') {
				$integrator->sync();
			}
			if (ZING_CMS=='wp') header("Location: admin.php?page=zingiri-web-shop&synced=true");
			elseif (ZING_CMS=='jl') header("Location: index?option=com_zingiriwebshop&synced=true");
			elseif (ZING_CMS=='dp') header("Location: index.php?q=admin/webshop/integration&synced=true");
			die;
		}

		if ( isset($_REQUEST['action']) && ('install' == $_REQUEST['action']) ) {
			foreach ($zing_ws_options as $value) {
				if (isset($value['id'])) update_option( $value['id'], isset($_REQUEST[ $value['id'] ]) ? $_REQUEST[ $value['id'] ] : '');
			}

			if (get_option('zing_ws_install_type') == 'local') {
				zing_install();
				$integrator->sync();
			}

			if (ZING_CMS=="dp") { menu_router_build(TRUE); menu_cache_clear_all(); }

			if (ZING_CMS=='wp') header("Location: admin.php?page=zingiri-web-shop&installed=true");
			elseif (ZING_CMS=='jl') header("Location: index.php?option=com_zingiriwebshop&installed=true");
			elseif (ZING_CMS=='dp') header("Location: index.php?q=admin/webshop/integration&installed=true");
			die;
		}

		if( isset($_REQUEST['action']) && ('uninstall' == $_REQUEST['action']) ) {
			if (get_option('zing_ws_install_type') == 'local') {
				zing_uninstall();
			}
			foreach ($zing_ws_options as $value) {
				delete_option( $value['id'] );
			}

			if (ZING_CMS=="dp") { $zing_version=''; menu_router_build(TRUE); menu_cache_clear_all(); }

			if (ZING_CMS=='wp') header("Location: admin.php?page=zingiri-web-shop&uninstalled=true");
			elseif (ZING_CMS=='jl') header("Location: index.php?option=com_zingiriwebshop&uninstalled=true");
			elseif (ZING_CMS=='dp') header("Location: index.php?q=admin/webshop/integration&uninstalled=true");
			die;
		}

	}

	if (ZING_CMS=='wp') {
		zing_ws_admin_menus();
	}

}