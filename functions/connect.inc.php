<?php
function wsConnectURL($page='') {
	global $user,$remoteMsg;

	if (empty($page)) {
//		if (isset($_GET['page'])) $page=$_GET['page'];
//		elseif (isset($_POST['page'])) $page=$_POST['page'];
		//else 
		$page='main';
	}
	
	$remoteMsg==array();
	$url=get_option('zing_ws_baseurl').get_option('zing_ws_accname').'/?page='.$page;
	//$url.='&wslivekey='.urlencode(get_option('zing_ws_acckey'));
	$url.='&wsliveusermail='.urlencode($user->mail);
	$url.='&wsliveuserid='.urlencode($user->uid);
	$url.='&wsliveusername='.urlencode($user->name);
	$url.='&wsliveurl='.urlencode(get_option('home'));
	$url.='&wslivepage_id='.zing_ws_default_page();
	$url.='&wslive='.ZING_CMS;
	$and='&';
	if (count($_GET) > 0) {
		foreach ($_GET as $n => $v) {
			if ($n!="page")
			{
				if (!is_array($v)) {
					$vars.= $and.$n.'='.urlencode($v);
					$and="&";
				}
			}
		}
	}

	$url.=$vars;
	//if (isset($_REQUEST['wscr'])) echo $url.'<br />';
	//print_r($_POST);
				$cf=get_post_custom();
	//var_dump($cf);
	//die('URL:'.$url);
	//echo $url.'<br />';
	$news = new HTTPRequest($url);
	if ($news->live()) {
		$msg=$news->DownloadToString(true);
		if ($news->redirect) {
			header($msg);
			exit;
		}
		$remoteMsg=$ret=json_decode($msg,true);
		if (!is_array($ret)) return array('main' => 'Problem connecting to server');
		return $ret;
	} else return false;
}