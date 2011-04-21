<?php
/**
 * Check if the web shop has been properly activated
 * @return boolean
 */
function zing_check() {
	global $lang_dir;

	$errors=array();
	$files=array();
	$dirs=array();

	if (get_option('zing_ws_cache')) $dirs[]=get_option('zing_ws_cache');
	if (count($dirs) > 0) {
		foreach ($dirs as $file) {
			if (!file_exists($file)) $errors[]='Directory '.$file.' doesn\'t exist, please create it.';
			elseif (!is_writable($file)) $errors[]='Directory '.$file.' is not writable, please chmod to 777';
		}
	}

	if (phpversion() < '5')	$errors[]="You are running PHP version ".phpversion().". You require PHP version 5 or higher to install the Web Shop.";

	return $errors;
}

/**
 * Main function handling content, footer and sidebars
 * @param $process
 * @param $content
 * @return unknown_type
 */
function zing_main($process,$content="") {
	//require(ZING_GLOBALS);
	global $remoteMsg;

	$ret='';
	$matches=array();

	$to_include="";

	switch ($process)
	{
		case "content":
			//apps player integration
			if (isset($_GET['zfaces']) || isset($_POST['zfaces'])) {
				if (!$zing_loaded) {
					//		require (ZING_LOC."./startmodules.inc.php");
					$zing_loaded=TRUE;
				}
				//return $content;
			}

			$cf=get_post_custom($_GET['page_id']);
			
//			print_r($cf);
//			$page=zing_page($_GET['page_id']);
			if (isset($_GET['page'])) {
				$pg=$_GET['page'];
			} elseif (isset($_POST['page'])) {
				$pg=$_POST['page'];
			} elseif (isset($cf['zing_page'])) {
				$pg=$cf['zing_page'][0];
				if (!$_GET['action'] && isset($cf['zing_action']))
				{
					$_GET['action']=$cf['zing_action'][0];
				}
			} elseif (preg_match('/\[zing-ws:(.*)&amp;(.*)=(.*)\]/',$content,$matches)==1) { //[zing-ws:page&x=y]
				list($prefix,$postfix)=preg_split('/\[zing-ws:(.*)\]/',$content);
				$pg=$matches[1];
				if ($matches[2]=='cat') $_GET['action']='list';
				$_GET[$matches[2]]=$matches[3];
			} elseif (preg_match('/\[zing-ws:(.*)\]/',$content,$matches)==1) { //[zing-ws:page]
				list($prefix,$postfix)=preg_split('/\[zing-ws:(.*)\]/',$content);
				$pg=$matches[1];
			} elseif (preg_match('/\[zing-ws-(.*):(.*)\]/',$content,$matches)==1) { //[zing-ws:page]
				$pg='parse';
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

	if ($to_include=="loadmain.php" && ($page=='logout' || ($page=='login' && !$_GET['lostlogin']))) {
		//stop logging
		restore_error_handler();
		error_reporting($wsper);
		header('Location:'.ZING_HOME.'/index.php?page='.$page);
		exit;
	}
	elseif ($to_include) {
		$ret.=$prefix;
		if ($process=='content') $ret.='<div class="zing_ws_page" id="zing_ws_'.$pg.'">';
		wsConnectURL($pg);
		$ret.=$remoteMsg['main'];
		//print_r($remoteMsg);die('RET=');
		if ($process=='content') $ret.='</div>';
		$ret.=$postfix;
		return $ret;
	}
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
	$ret='<center style="position:relative;clear:both;font-size:smaller;margin-top:5px">';
	$ret.='<a href="http://www.zingiri.com" alt="Zingiri Web Shop">';
	$ret.='<img src="'.ZING_URL.'/zingiri-logo.png" height="35"/>';
	$ret.='</a>';
	$ret.='</center>';
	return $ret;
}

function zing_ws_default_page() {
	$ids=get_option("zing_webshop_pages");
	$ida=explode(",",$ids);
	return $ida[0];
}

?>