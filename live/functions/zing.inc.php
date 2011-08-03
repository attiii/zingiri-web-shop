<?php
/**
 * Main function handling content, footer and sidebars
 * @param $process
 * @param $content
 * @return unknown_type
 */
function zing_wslive_main($process,$content="") {
	//require(ZING_GLOBALS);
	global $remoteMsg,$showPage;
	
	$showPage=true;
	
	$pageId=url_to_postid($_SERVER['REQUEST_URI']);
	
	$ret='';
	$matches=array();

	$to_include="";
	
	switch ($process)
	{
		case "content":
			//apps player integration
			if (isset($_GET['zfaces']) || isset($_POST['zfaces'])) {
				if (!$zing_loaded) {
					//		require (ZING_WSLIVE_LOC."./startmodules.inc.php");
					$zing_loaded=TRUE;
				}
				//return $content;
			}

			$cf=get_post_custom($pageId);
			//print_r($cf);
			//$page=zing_wslive_page($_GET['page_id']);
			//echo 'page='.$_GET['page_id'].$page;
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
			} else {
				$pg='main';//return $content;
				$showPage=false;
			}
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
	
	//die($to_include.'-'.$pg);
	/*
	if ($to_include=="loadmain.php" && ($page=='logout' || ($page=='login' && !$_GET['lostlogin']))) {
		//stop logging
		restore_error_handler();
		error_reporting($wsper);
		header('Location:'.ZING_HOME.'/index.php?page='.$page);
		exit;
	}
	*/
	if ($showPage && $to_include) {
		$ret.=$prefix;
		if ($process=='content') $ret.='<div class="zing_ws_page" id="zing_ws_'.$pg.'">';
		wsConnectURL($pg);
		$ret.=$remoteMsg['main'];
//		print_r($remoteMsg);die('RET=');
		if ($process=='content') $ret.='</div>';
		$ret.=$postfix;
		/*
		print_r($remoteMsg);
		die();
		if ($pg=='login' && $remoteMsg['status']=='loginsuccess') {
			header(get_option('home'));
			die();
		}
		*/		
		return $ret;
	} elseif (!$showPage && $to_include) {
		wsConnectURL($pg);
		return $content;
	}
}

/**
 * The footer is automatically inserted for Artisteer generated themes.
 * For other themes, the function zing_footer should be called from inside the theme.
 * @param $footer
 * @return unknown_type
 */
function zing_wslive_footer($footer="")
{
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) return $footer;
	if (get_option('zing_ws_logo')!='sf' && get_option('zing_ws_logo')!='') return $footer;
	zing_wslive_display_logo();
}

function zing_wslive_display_logo()
{
	//Please contact us if you wish to remove the Zingiri logo
	$ret='<center style="position:relative;clear:both;font-size:smaller;margin-top:5px">';
	$ret.='<a href="http://www.zingiri.com" alt="Zingiri Web Shop">';
	$ret.='<img src="http://www.zingiri.com/logo.png" height="35"/>';
	$ret.='</a>';
	$ret.='</center>';
	return $ret;
}

function zing_wslive_default_page() {
	$ids=get_option("zing_webshop_pages");
	$ida=explode(",",$ids);
	return $ida[0];
}

function zing_wslive_version() {
	return false;
	$s=$p=false;
	if (get_option('zing_webshop_version') == ZING_VERSION) $s=true;
	if (!get_option('zing_webshop_pro') || (get_option('zing_ws_pro_version') == ZING_WS_PRO_VERSION)) $p=true;
	if ($s && $p) return true;
	else return false;
}

function zing_wslive_isadmin() {
	return true;
}

?>