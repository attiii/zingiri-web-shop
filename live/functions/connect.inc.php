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
	if (isset($_SESSION['zingiri-web-shop-live']['txt'])) $url.='&wslive_txt=0';
	else $url.='&wslive_txt=1';

	$and='&';
	if (count($_GET) > 0) {
		foreach ($_GET as $n => $v) {
			if ($n!="page" && $n!='page_id')
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
	//			$cf=get_post_custom();
	//var_dump($cf);
	//die('URL:'.$url);
	//echo '<br />Calling:'.$url.'<br />';
	$news = new wsHTTPRequest($url);
	if ($news->live()) {
		$msg=$news->DownloadToString(true);
		if ($news->redirect) {
			echo 'redirect:'.$msg;
			die();
			//header($msg);
			//exit;
		}
		if ($news->error) {
			$remoteMsg['main']='<div style="text-align:center;font-size:24px;">Web Shop '.$news->error.'</div>';
		} else {
			$remoteMsg=$ret=json_decode($msg,true);
			//print_r($msg);die();
			if (!is_array($ret)) return array('main' => 'Problem connecting to server');
			if (!isset($_SESSION['zingiri-web-shop-live']['txt'])) $_SESSION['zingiri-web-shop-live']['txt']=$remoteMsg['txt'];
			else $remoteMsg['txt']=$_SESSION['zingiri-web-shop-live']['txt'];

			if ($remoteMsg['print']) {
				zing_ws_print($remoteMsg['print_title'],$remoteMsg['main']);
				die();
			} elseif ($remoteMsg['download']) {
				send_file($remoteMsg['download_path'], $remoteMsg['download_file'], $remoteMsg['download_name']);
				die();
			}
			else return $ret;
		}
	} else return false;
}

# Send (download) file via pass thru
#-------------------------------------
function send_file($path, $file, $filename){

	$mainpath = "$path/$file";
	$filesize2 = sprintf("%u", filesize($mainpath));
	set_time_limit(0);

	//header("Cache-Control: ");# leave blank to avoid IE errors
	//header("Pragma: ");# leave blank to avoid IE errors
	//header("Content-type: application/octet-stream");
	//header("Content-type: application/exe");

	$handle = @fopen($mainpath,"rb");
	if ($handle) {
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Transfer-Encoding: binary");
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=\"".$filename."\"");
		header("Content-Type: application/force-download");
		header("Content-length:".(string)($filesize2));
		while(!feof($handle)) {
			print(fread($handle, 1024*8));
			flush_now();
			if (connection_status()!=0) {
				@fclose($handle);
				die();
			}
		}
		@fclose($handle);
	} else {
		print "<p><center><font class=\"changed\">ERROR - Invalid Request (Downloadable file Missing or Unreadable)</font></center><br><br>";
	}
	return;
}


function flush_now() {
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	return true;
}