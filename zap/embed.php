<?php
/*  embed.php
 Copyright 2008,2009,2010 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Apps.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Zingiri Apps; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
define("ZING_APPS_PLAYER_VERSION","0.9.4");
if (get_option('zing_apps_remote_url')) define("ZING_APPS_REMOTE_URL",get_option('zing_apps_remote_url').'/');
else define("ZING_APPS_REMOTE_URL","http://www.zingiri.com/");

require(dirname(__FILE__).'/'.ZING_CMS.'.init.inc.php');
require(dirname(__FILE__).'/'.ZING_CMS.'.hooks.inc.php');

function zing_apps_player_error_handler($severity, $msg, $filename, $linenum) {
	echo $severity."-".$msg."-".$filename."-".$linenum;
}


/**
 * Output activation messages to log
 * @param $stringData
 * @return unknown_type
 */
function zing_apps_player_echo($stringData) {
	$myFile = ZING_LOC."/log.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, $stringData);
	fclose($fh);
}

/**
 * Activation of web shop: creation of database tables & set up of pages
 * @return unknown_type
 */
function zing_apps_player_activate() {
	global $wpdb,$dbtablesprefix;

	set_error_handler("zing_apps_error_handler");
	error_reporting(E_ALL & ~E_NOTICE);

	$prefix=$dbtablesprefix;
	if (!defined("DB_PREFIX")) define("DB_PREFIX",$prefix);
	zing_apps_player_install();
}

function zing_apps_player_install() {
	global $dbtablesprefix;

	if (!function_exists('zfCreate')) require(dirname(__FILE__).'/includes/create.inc.php');
	if (!function_exists('zfReadRecord')) require(dirname(__FILE__).'/includes/db.inc.php');
	if (!function_exists('zf_json_decode')) require(dirname(__FILE__).'/includes/faces.inc.php');
	if (!class_exists('db')) require(dirname(__FILE__).'/classes/db.class.php');

	$zing_version=get_option("zing_apps_player_version");
	if (!$zing_version)
	{
		add_option("zing_apps_player_version",ZING_APPS_PLAYER_VERSION);
	}
	else
	{
		update_option("zing_apps_player_version",ZING_APPS_PLAYER_VERSION);
	}

	$prefix=$dbtablesprefix;

	if ($handle = opendir(dirname(__FILE__).'/db')) {
		$files=array();
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,".sql") && ($file != 'apps.db.sql')) {
				$f=explode("-",$file);

				$v=str_replace(".sql","",$f[1]);
				if ($zing_version < $v) {
					$files[]=dirname(__FILE__).'/db/'.$file;
				}
			}
		}
		closedir($handle);
		asort($files);
		if (count($files) > 0) {
			foreach ($files as $file) {
				zing_apps_error_handler(0,$file);
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
							zing_apps_error_handler(0,$query);
							mysql_query($query) or zing_apps_error_handler(1,mysql_error().'-'.$query);
							$query = "";
						}
					}
				}
			}
		}
	}
	//load forms
	zing_apps_player_load(ZING_APPS_PLAYER_DIR.'forms/');
	if (defined("ZING_APPS_CUSTOM")) zing_apps_player_load(ZING_APPS_CUSTOM.'apps/forms/');

}

/**
 * Deactivation of web shop: removal of database tables
 * @return unknown_type
 */
function zing_apps_player_deactivate() {
	zing_apps_player_uninstall();
}

/**
 * Uninstallation of web shop: removal of database tables
 * @return void
 */
function zing_apps_player_uninstall($drop=true) {
	global $dbtablesprefix;

	if ($drop) {
		$query="show tables like '".$dbtablesprefix."%'";
		$sql = mysql_query($query) or die(mysql_error());
		while ($row = mysql_fetch_row($sql)) {
			if (($row[0]!=$dbtablesprefix.'options') && strpos($row[0],'_mybb_')===false && strstr($row[0],'_ost_')===false) {
				$query="drop table ".$row[0];
				mysql_query($query) or zing_ws_error_handler(1,mysql_error().'-'.$query);
			}
		}
	}
	delete_option("zing_apps_player_version");
}

/**
 * Page content filter
 * @param $content
 * @return unknown_type
 */
function zing_apps_player_content($content='') {

	global $post;
	global $dbtablesprefix,$page;
	global $zing;
	
	$page=$_GET['page'];
	error_reporting(E_ALL ^ E_NOTICE); // ^ E_NOTICE
	if (function_exists("user_error_handler")) set_error_handler("user_error_handler");
	else ini_set('display_errors', '1');

	if (defined("ZING_APPS_CUSTOM")) { require(ZING_APPS_CUSTOM."globals.php"); }
	
	$cf=get_post_custom();

	if (isset($_GET['zfaces'])) {
		$zfaces=$_GET['zfaces'];
	} elseif (isset($cf['zfaces'])) {
		$zfaces=$cf['zfaces'][0];
	}	elseif (preg_match('/\[apps:(.*)\]/',$content,$matches)==1) { //[apps:form]
		list($prefix,$postfix)=preg_split('/\[apps:(.*)\]/',$content);
		$_GET['form']=$matches[1];
		$_GET['action']='add';
		$zfaces='form';
	} else {
		restore_error_handler();
		return $content;
	}
	
	if ($cf['zing_form'][0]) $_GET['form']=$cf['zing_form'][0];
	if ($cf['zing_action'][0]) $_GET['action']=$cf['zing_action'][0];

	require_once(dirname(__FILE__)."/includes/all.inc.php");
	
	if (isset($zing->paths)) {
		foreach ($zing->paths as $path) {
			require($path."apps/classes/index.php");
		}
		foreach ($zing->paths as $path) {
			require($path."apps/services/index.php");
		}
	}
	
	echo '<div class="zing_ws_page" id="zing_ws_'.$_GET['form'].'">';
	echo $prefix;
	switch ($zfaces)
	{
		case "form":
			require(dirname(__FILE__)."/scripts/form.php");
			break;
		case "list":
			require(dirname(__FILE__)."/scripts/list.php");
			break;
		case "mform":
			require(dirname(__FILE__)."/scripts/mform.php");
			break;
	}
	echo $postfix;
	echo '</div>';
	restore_error_handler();

	return "";

}


/**
 * Header hook: loads FWS addons and css files
 * @return unknown_type
 */
function zing_apps_player_header()
{
	echo '<script type="text/javascript" language="javascript">';
	echo "var zfAppsUrl='".ZING_APPS_PLAYER_URL."';";
	if (defined("ZING_APPS_BUILDER")) {
		echo "var zfurl='".ZING_APPS_BUILDER_URL."ajax/';";
		if (defined("ZING_APPS_CUSTOM")) echo "var zfAppsCustom='".ZING_APPS_CUSTOM."';";
		else echo "var zfAppsCustom='';";
		echo "var zfAppsSystem='".ZING_APPS_PLAYER_DIR."';";
	}
	echo '</script>';
	
	echo '<link rel="stylesheet" href="' . ZING_APPS_PLAYER_URL . 'css/integrated_view.css" type="text/css" media="screen" />';
	if (defined("ZING_APPS_BUILDER") && (!defined("ZING_PROTOTYPE") || ZING_PROTOTYPE)) {
		echo '<script type="text/javascript" src="' . ZING_APPS_BUILDER_URL . 'js/form.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_APPS_BUILDER_URL . 'js/face.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_APPS_BUILDER_URL . 'js/dragtable.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/sorttable.js"></script>';
	}
	if (!defined("ZING_PROTOTYPE") || ZING_PROTOTYPE) {
		echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/sortlist.proto.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/repeatable.proto.js"></script>';
	} elseif (defined("ZING_JQUERY") && ZING_JQUERY) {
		echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/sortlist.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/repeatable.jquery.js"></script>';
	}
		//echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/jquery.json-2.2.js"></script>';
}

function zing_apps_player_header_cp() {
	zing_apps_player_header();
	/*
	if (!defined("ZING_PROTOTYPE") || ZING_PROTOTYPE) {
		echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/sortlist.proto.js"></script>';
	} elseif (defined("ZING_JQUERY") && ZING_JQUERY) {
		echo '<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/sortlist.jquery.js"></script>';
	}
	*/
}

/**
 * Initialization of page, action & page_id arrays
 * @return unknown_type
 */
function zing_apps_player_init()
{
	if (!defined("ZING_PROTOTYPE") || ZING_PROTOTYPE) {
		wp_enqueue_script('prototype');
		wp_enqueue_script('scriptaculous');
	}

	ob_start();
	session_start();

	if (isset($_GET['zfaces']))
	{
		$_GET['page_id']=get_option("zing_apps_player_page");
	}
}

function zing_apps_player_load($dir) {
	global $dbtablesprefix;

	$prefix=$dbtablesprefix;

	if ($handle = opendir($dir)) {
		$files=array();
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,".json")) {
				$file_content = file_get_contents($dir.$file);
				$a=zf_json_decode($file_content,true,false);
				zing_apps_error_handler(0,$file);
				zing_apps_error_handler(0,$file_content);
				zing_apps_error_handler(0,$a);
				zfCreate($a['NAME'],$a['ELEMENTCOUNT'],$a['ENTITY'],$a['TYPE'],$a['DATA'],$a['LABEL'],$a['ID']);
			}
		}
	}
}

if (!function_exists('zing_apps_error_handler')) {
	function zing_apps_error_handler($severity, $msg, $filename="", $linenum=0) {
		if (is_array($msg)) $msg=print_r($msg,true);
		$myFile = dirname(__FILE__)."/../log.txt";
		if ($fh = fopen($myFile, 'a')) {
			fwrite($fh, date('Y-m-d h:i:s').' '.$msg.' ('.$filename.'-'.$linenum.')'."\r\n");
			fclose($fh);
		}
	}
}

function zing_apps_cp_submenus() {
	$name='Zingiri Apps';
	//add_options_page($name." Options", "$name", 8, 'zingiri-apps', 'zing_apps_editor');

	
	//add_submenu_page('zingiri-apps', $name.'- List', 'List', 'administrator', 'zingiri-apps-summary', 'zing_apps_summary');
}

function zing_apps_editor() {
	$url=ZING_APPS_REMOTE_URL;
	$url.="wordpress/wp-login.php";

	$login=true;
	if ($login) {

		if ($_POST['zfremotedata']) {
			if (!function_exists('zfCreate')) require(dirname(__FILE__).'/includes/create.inc.php');
			if (!function_exists('zfReadRecord')) require(dirname(__FILE__).'/includes/db.inc.php');
			if (!function_exists('zf_json_decode')) require(dirname(__FILE__).'/includes/faces.inc.php');
			if (!class_exists('db')) require(dirname(__FILE__).'/classes/db.class.php');

			$data=str_replace('\"','"',$_POST['zfremotedata']);
			parse_str($_POST['zfremotesortorder']);
			$sortorder=$zfaces1;
			$a=json_decode($data,true);
			$b=array();
			if (count($sortorder)>0)
			{
				foreach ($sortorder as $id => $value)
				{
					$b[$value]=$a[$value];
				}
				$data=json_encode($b);
			}
			$a=array();
			$a['ELEMENTCOUNT']=$_POST['zfremoteelementcount'];
			$a['ENTITY']=$_POST['zfformentity'];
			$a['TYPE']=$_POST['zfformtype'];
			$a['DATA']=$data;
			$a['LABEL']=$_POST['zfformlabel'];
			$a['ID']=$_POST['zfremoteid'];
			$form=$a['NAME']=$_POST['zfformname'];
			zfCreate($a['NAME'],$a['ELEMENTCOUNT'],$a['ENTITY'],$a['TYPE'],$a['DATA'],$a['LABEL'],$a['ID'],true);
			$db=new db();
			$db->update('update ##faces set custom='.qs($data).' where name='.qs($form));
			echo '<div id="message" class="updated fade"><p>Form updated</p></div>';
			zing_apps_list();
		} else {
			zing_apps_list();
		}
	}
}
function zing_apps_list() {
	require_once(dirname(__FILE__).'/classes/index.php');
	echo 'Click on the form you want to edit<br />';
	if (defined("ZING_APPS_EDITABLES")) $query="select * from ##faces where name in (".ZING_APPS_EDITABLES.") order by name";
	else $query="select * from ##faces where name not in ('flink','frole','faccess') order by name";
	$db=new db;
	$db->select($query);
	echo '<ul>';
	while ($db->next()) {
		echo '<li>';
		//echo '<p style="position:relative;float:left;clear:left;width:20%">'.$db->get('label').'</p>';
		if ($db->get('custom') != '') $data=$db->get('custom');
		else $data=$db->get('data');
		echo '<div style="position:relative;float:left"><form action="'.ZING_APPS_REMOTE_URL.'index.php?zfaces=redit&remote=1" method="post">';
		echo '<input type="hidden" name="form" value="'.$db->get('name').'" />';
		echo '<input type="hidden" name="data" value="'.rawurlencode($data).'" />';
		echo '<input type="hidden" name="label" value="'.$db->get('label').'" />';
		echo '<input type="hidden" name="elementcount" value="'.$db->get('elementcount').'" />';
		echo '<input type="hidden" name="entity" value="'.$db->get('entity').'" />';
		echo '<input type="hidden" name="type" value="'.$db->get('type').'" />';
		echo '<input type="hidden" name="id" value="'.$db->get('id').'" />';
		echo '<input type="hidden" name="urlback" value="'.get_option("siteurl").'/wp-admin/admin.php?page=zingiri-apps-settings" />';
		echo '<input type="submit" value="'.$db->get('label').'" />';
		echo '</form></div>';
		echo '</li>';
	}
	echo '</ul>';
}

function zing_apps_settings() {
	$options=array();
	$options[]=	array(	"name" => "Remote URL",
			"desc" => "Remote URL to Zingir Apps Builder. Only change this if you know what you are doing.",
			"id" => "zing_apps_remote_url",
			"std" => "http://www.zingiri.com",
			"type" => "text");

	if ( $_GET['page'] == "zingiri-apps" ) {
		if ( 'update' == $_REQUEST['action'] ) {
			foreach ($options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] );
			}

			foreach ($options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) {
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
				} else { delete_option( $value['id'] ); }
			}
		}
	}
	require(dirname(__FILE__).'/includes/controlpanel.inc.php');
}
?>