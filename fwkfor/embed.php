<?php
define("ZING_APPS_PLAYER_VERSION","1.6.2");

if (!defined('APHPS_XD')) define('APHPS_XD',0);

require(dirname(__FILE__).'/autoload.php');

require_once(dirname(__FILE__).'/aphps.php');
require(dirname(__FILE__).'/'.ZING_CMS.'.init.inc.php');
require(dirname(__FILE__).'/'.ZING_CMS.'.hooks.inc.php');
require(dirname(__FILE__).'/../fwktec/functions/index.php');
require(dirname(__FILE__).'/../fwktec/classes/index.php');
if (defined('ZING_APPS_BUILDER')) {
	global $aphps_projects;
	$aphps_projects['player']['label']='Core';
	$aphps_projects['player']['dir']=ZING_APPS_PLAYER_DIR;
	$aphps_projects['player']['url']=ZING_APPS_PLAYER_URL;
	$aphps_projects['player']['level']='admin';
	$aphps_projects['player']['system']=1;
}

if (get_option('zing_apps_remote_url')) define("ZING_APPS_REMOTE_URL",get_option('zing_apps_remote_url').'/');
else define("ZING_APPS_REMOTE_URL","http://www.aphps.com/");

function zing_apps_player_error_handler($severity, $msg, $filename, $linenum) {
	echo $severity."-".$msg."-".$filename."-".$linenum;
}

/**
 * Activation of web shop: creation of database tables & set up of pages
 * @return unknown_type
 */
function zing_apps_player_activate() {
	global $wpdb,$dbtablesprefix;

	set_error_handler("zing_apps_error_handler");
	$apper=error_reporting(E_ALL & ~E_NOTICE);

	$prefix=$dbtablesprefix;
	if (!defined("DB_PREFIX")) define("DB_PREFIX",$prefix);
	zing_apps_player_install();

	restore_error_handler();
	error_reporting($apper);
}

/*
 * Legacy version of the installer (< v1.4.5)
 */
function zing_apps_player_install($version='',$loadAll=true) {
	global $dbtablesprefix,$aphps_projects;

	if (!function_exists('zfCreate')) require(dirname(__FILE__).'/includes/create.inc.php');
	if (!function_exists('zfReadRecord')) require(dirname(__FILE__).'/includes/db.inc.php');
	if (!function_exists('zf_json_decode')) require(dirname(__FILE__).'/includes/faces.inc.php');
	if (!class_exists('db')) require(dirname(__FILE__).'/classes/db.class.php');

	$zing_version=$version ? $version : get_option("zing_apps_player_version");

	$prefix=$dbtablesprefix;

	//Look for baseline version
	$dir=dirname(__FILE__).'/db/';
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
				zing_apps_error_handler(0,'Process '.$file);
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
							zing_apps_error_handler(0,$query);
							mysql_query($query) or zing_apps_error_handler(0,mysql_error().'-'.$query);
							$query = "";
						}
					}
				}
			}
		}
	}

	//load forms
	zing_apps_player_load(ZING_APPS_PLAYER_DIR.'forms/');
	if ($loadAll && (count($aphps_projects) > 0)) {
		foreach ($aphps_projects as $id => $project) {
			if ($id != 'player') zing_apps_player_load($project['dir'].'forms/');
		}
	}

	//remote forms
	if (get_option('zing_apps_remote_url') == 'http://www.aphps.com') update_option('zing_apps_remote_url','http://forms.aphps.com');

	trigger_error('update version:'.ZING_APPS_PLAYER_VERSION);
	if (!$zing_version) add_option("zing_apps_player_version",ZING_APPS_PLAYER_VERSION);
	else update_option("zing_apps_player_version",ZING_APPS_PLAYER_VERSION);
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
				mysql_query($query) or zing_apps_error_handler(1,mysql_error().'-'.$query);
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
	global $post,$aphps;
	global $dbtablesprefix,$page;
	global $aphps_projects;

	if (isset($_GET['page'])) $page=$_GET['page'];
	if (!defined('WP_DEBUG')) {
		$apper=error_reporting(E_ALL ^ E_NOTICE); // ^ E_NOTICE
		if (function_exists("user_error_handler")) set_error_handler("user_error_handler");
		else ini_set('display_errors', '1');
	}

	if (defined("ZING_APPS_CUSTOM")) { require(ZING_APPS_CUSTOM."globals.php"); }

	if (isset($post)) $cf=get_post_custom();

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
		if (!defined('WP_DEBUG')) {
			restore_error_handler();
			error_reporting($apper);
		}
		return $content;
	}

	$aphps->doAction('content_before');
	if (isset($post)) {
		if (isset($cf['zing_form'][0]) && $cf['zing_form'][0]) $_GET['form']=$cf['zing_form'][0];
		if (isset($cf['zing_action'][0]) && $cf['zing_action'][0]) $_GET['action']=$cf['zing_action'][0];
	}

	require_once(dirname(__FILE__)."/includes/all.inc.php");
	echo actionCompleteMessage();
	echo '<div class="zing_ws_page" id="zing_ws_'.(isset($_GET['form']) ? $_GET['form'] : 'form_'.$_GET['formid']).'">';
	if (isset($prefix)) echo $prefix;
	switch ($zfaces)
	{
		case "form":
			require(dirname(__FILE__)."/scripts/form.php");
			break;
		case "list":
			require(dirname(__FILE__)."/scripts/list.php");
			break;
			break;
		case "ajax":
			require(dirname(__FILE__)."/scripts/ajax.php");
			break;
		default:
			if (isset($_REQUEST['mod'])) {
				$project=$aphps_projects[$_REQUEST['mod']];
				if (file_exists($project['dir'].'scripts/'.$zfaces.'.php')) {
					require($project['dir'].'scripts/'.$zfaces.'.php');
					break;
				}
			} else {
				foreach ($aphps_projects as $id => $project) {
					if (file_exists($project['dir'].'scripts/'.$zfaces.'.php')) {
						require($project['dir'].'scripts/'.$zfaces.'.php');
						break;
					}
				}
			}
			break;
	}
	if (isset($postfix)) echo $postfix;
	echo '</div>';

	$aphps->doAction('content_after');

	if (!defined('WP_DEBUG')) {
		restore_error_handler();
		error_reporting($apper);
	}
	return "";
}




function zing_apps_player_header_cp() {
	zing_apps_player_header();
}

/**
 * Initialization of page, action & page_id arrays
 * @return unknown_type
 */
function zing_apps_player_init()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');

	ob_start();
	if (!session_id()) @session_start();

	if (isset($_GET['zfaces']) && (ZING_CMS!='wp'))
	{
		$_GET['page_id']=get_option("zing_apps_player_page");
	}
}

function zing_apps_player_load($dir) {
	global $dbtablesprefix;

	if (!function_exists('zfCreate')) require(dirname(__FILE__).'/includes/create.inc.php');
	if (!function_exists('zfReadRecord')) require(dirname(__FILE__).'/includes/db.inc.php');
	if (!function_exists('zf_json_decode')) require(dirname(__FILE__).'/includes/faces.inc.php');
	if (!class_exists('db')) require(dirname(__FILE__).'/classes/db.class.php');

	$prefix=$dbtablesprefix;
	if (file_exists($dir) && $handle = opendir($dir)) {
		$files=array();
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,".json")) {
				display('Process '.$file);
				$file_content = file_get_contents($dir.$file);
				$a=zf_json_decode($file_content,true,false,false);
				zfCreate($a['NAME'],$a['ELEMENTCOUNT'],$a['ENTITY'],$a['TYPE'],$a['DATA'],$a['LABEL'],$a['PROJECT'],$a['ID']);
			}
		}
	}
}

function aphpsCreateForm($project,$file){
	global $aphps_projects;

	if (!function_exists('zfCreate')) require(dirname(__FILE__).'/includes/create.inc.php');
	if (!function_exists('zfReadRecord')) require(dirname(__FILE__).'/includes/db.inc.php');
	if (!function_exists('zf_json_decode')) require(dirname(__FILE__).'/includes/faces.inc.php');
	if (!class_exists('db')) require(dirname(__FILE__).'/classes/db.class.php');

	$dir=$aphps_projects[$project]['dir'].'forms/';
	trigger_error('Loading '.$dir.$file.'.json');
	$file_content = file_get_contents($dir.$file.'.json');
	$a=zf_json_decode($file_content,true,false,false);
	zfCreate($a['NAME'],$a['ELEMENTCOUNT'],$a['ENTITY'],$a['TYPE'],$a['DATA'],$a['LABEL'],$a['PROJECT'],$a['ID']);
}

if (!function_exists('zing_apps_error_handler')) {
	function zing_apps_error_handler($severity, $msg, $filename="", $linenum=0) {
		if (is_array($msg)) $msg=print_r($msg,true);
		$myFile = defined('APHPS_LOG_FILE') ? APHPS_LOG_FILE : dirname(__FILE__)."/../log.txt";
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
}

function zing_apps_cp_submenus() {
	$name='APhPS';
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
			zfCreate($a['NAME'],$a['ELEMENTCOUNT'],$a['ENTITY'],$a['TYPE'],$a['DATA'],$a['LABEL'],$a['PROJECT'],$a['ID'],true);
			$db=new aphpsDb();
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
		echo '<input type="hidden" name="urlback" value="'.get_option("siteurl").'/wp-admin/admin.php?page=aphps-player-settings" />';
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
			"std" => "http://forms.aphps.com",
			"type" => "text");

	if ( $_GET['page'] == "aphps-player" ) {
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

function zVars() {
	global $aphps;
	$v=array();
	if (defined('APHPS_AJAX_URL')) $v['aphpsAjaxURL']=APHPS_AJAX_URL;
	elseif (is_admin()) $v['aphpsAjaxURL']=zurl('admin.php?page=ajax&zfaces=ajax&form=');
	else $v['aphpsAjaxURL']=zurl('index.php?zfaces=ajax&form=');
	$v['aphpsURL']=ZING_APPS_PLAYER_URL;
	$v['aphpsDateFormat']=$aphps->jsDateFormat;

	return $v;
}

function zScripts() {
	global $aphps_projects;
	$v=array();
	$v[]=ZING_APPS_PLAYER_URL.'js/'.APHPS_JSDIR.'/sortlist.jquery.js';
	$v[]=ZING_APPS_PLAYER_URL.'js/'.APHPS_JSDIR.'/repeatable.jquery.js';
	$v[]=ZING_APPS_PLAYER_URL.'js/'.APHPS_JSDIR.'/formfield.jquery.js';
	$v[]=ZING_APPS_PLAYER_URL.'js/'.APHPS_JSDIR.'/form.jquery.js';
	$v[]=ZING_APPS_PLAYER_URL.'js/'.APHPS_JSDIR.'/core.jquery.js';

	foreach ($aphps_projects as $id => $project) {
		if (isset($project['js'])) {
			foreach ($project['js'] as $script) {
				$v[]=$project['url'].'js/'.APHPS_JSDIR.'/'.$script;
				//$v[]=$project['url'].'js/'.$script;
			}
		}
	}

	return $v;
}

function zStyleSheets() {
	global $aphps_projects;
	$v=array();
	$v[]=ZING_APPS_PLAYER_URL . 'css/integrated_view.css';

	foreach ($aphps_projects as $id => $project) {
		if (isset($project['css'])) {
			foreach ($project['css'] as $css) {
				$v[]=$project['url'].'css/'.$css;
			}
		}
	}

	return $v;
}

