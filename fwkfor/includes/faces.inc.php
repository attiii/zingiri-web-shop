<?php
function zfKeys($key,&$key1,&$key2)
{
	$key2=(int)substr($key,-2);
	$key1=(int)substr($key,0,strlen($key)-2);
}

function zfGetForm($formid)
{
	$db=new aphpsDb();
	if ($db->select("select `name` from `##faces` where id=".qs($formid))) {
		$db->next();
		return $db->get('name');
	} else return false;
}


function faces_get_xml($type,$dir="") {
	global $wsCache;
	global $aphps_projects;

	if (isset($wsCache['xmlf_type']) && $wsCache['xmlf_type']==$type) return $wsCache['xmlf_data'];

	if (!empty($dir)) {
		$url_details=$dir.$type.".xml";
		if (file_exists($url_details)) {
			if ($xmlf=simplexml_load_file($url_details)) {
				$wsCache['xmlf_type']=$type;
				$wsCache['xmlf_data']=$xmlf;
				return $xmlf;
			}
		}
	}

	$url_details=ZING_APPS_PLAYER_DIR.'fields/'.$type.".xml";
	if (file_exists($url_details)) {
		if ($xmlf=simplexml_load_file($url_details)) {
			$wsCache['xmlf_type']=$type;
			$wsCache['xmlf_data']=$xmlf;
			return $xmlf;
		}
	}

	if (count($aphps_projects) > 0) {
		foreach ($aphps_projects as $id => $project) {
			if (($id != 'player') && isset($project['dir'])) {
				$url_details=$project['dir'].'fields/'.$type.".xml";
				if (file_exists($url_details)) {
					if ($xmlf=simplexml_load_file($url_details)) {
						$wsCache['xmlf_type']=$type;
						$wsCache['xmlf_data']=$xmlf;
						return $xmlf;
					}
				}
			}
		}
	}

	$url_details=ZING_APPS_PLAYER_DIR.'fields/text.xml';
	if (file_exists($url_details)) {
		if ($xmlf=simplexml_load_file($url_details)) {
			$wsCache['xmlf_type']=$type;
			$wsCache['xmlf_data']=$xmlf;
			return $xmlf;
		}
	}
	echo ZING_APPS_PLAYER_DIR;
	die("no file loaded: ".$type);
}

function faces_log($msg,$fileerr="warning") {

	global $machform_logspath;

	if (!empty($machform_logspath))
	{
		$fileerr=$machform_logspath.date("Ymd")."-".$fileerr.".txt";
	}
	else
	{
		$fileerr="./logs/".date("Ymd")."-".$fileerr.".txt";
	}

	$ferror=fopen($fileerr, "a") or Print("Could not find open the error log ".$fileerr);
	fwrite($ferror, $msg."\r\n");
	fclose ($ferror);

}

function faces_directory($dir,$filters,$dirsOnly=false) {
	$files=array();
	if (file_exists($dir) && ($handle=opendir($dir))) {
		if (!$filters || $filters == "all"){
			while(($file = readdir($handle))!==false){
				if ($file!='.' && $file!='..' && ($dirsOnly==false || ($dirsOnly==true && is_dir($dir.'/'.$file)))) $files[] = $file;
			}
		}
		if ($filters && $filters != "all") {
			$filters=explode(",",$filters);
			while (($file = readdir($handle))!==false) {
				for ($f=0;$f<sizeof($filters);$f++):
				$system=explode(".",$file);
				if (isset($system[1]) && ($system[1] == $filters[$f])) {
					if ($dirsOnly==false || ($dirsOnly==true && is_dir($dir.'/'.$file))) $files[] = $file;
				}
				endfor;
			}
		}
		closedir($handle);
	}
	return $files;
}

function txdie($msg)
{
	global $faces_error;
	$faces_error=1;
	faces_log($msg,"error");
}

function faces_translate($text)
{
	Global $faces_lang;
	$lang="EN";
	$translated=$faces_lang[$lang][$text];
	if (empty($translated)) { $translated=$text; }
	return $translated;

}



function do_query($query) {
	$result=mysql_query($query) or zing_apps_error_handler(1,$query);
	return $result;
}

function zf_json_decode($json,$assoc=true,$strip=true,$stripSlashes=true) {
	if (!get_magic_quotes_gpc()) return json_decode($json,$assoc);

	if ($strip) {
		$json=str_replace('\"','"',$json);
		$json=str_replace("\'",'"',$json);
	}

	if ($stripSlashes) { //Should probably be removed
		$json=str_replace("\\",'',$json);
	}

	if (!extension_loaded("json")){
		if (!class_exists('Services_JSON')) require_once(dirname(__FILE__).'/JSON.php');
		$j = new Services_JSON(16);
		$ret = $j->decode($json);
	}
	else{
		$ret = json_decode($json,$assoc);
	}
	$ret=(array)$ret;
	return $ret;
}

function zf_json_encode($a) {
	if (!extension_loaded('json')){
		$j = new Services_JSON;
		$ret = $j->encode($a);
	}
	else{
		$ret = json_encode($a);
	}
	return $ret;
}

function showForm() {
	global $line;
	$notitle=true;
	require(dirname(__FILE__).'/../scripts/form.php');
}

function appsForm($formname,$action,$formURL,$noRedirect=true) {
	global $line;
	$notitle=true;
	require(dirname(__FILE__).'/../scripts/form.php');
	$zfform->success=$success;
	$zfform->showform=$showform;
	return $zfform;
}

function showList() {
	global $line;
	$notitle=true;
	require(dirname(__FILE__).'/../scripts/list.php');
}

if (!function_exists('zurl')) {
	function zurl($url,$printurl=false,$interface='') {
		if (ZING_CMS=='wp') {
			if (wsIsAdminPage() && ($interface!='front')) $url=str_replace('index.php','admin.php',$url);
			else {
				if (strstr($url,ZING_HOME)===false) $url=str_replace('index.php',ZING_HOME.'/index.php',$url);
			}
		} elseif (ZING_CMS=='jl') {
			if ($url=='index.php') $url='index.php?option=com_zingiriwebshop';
			if (wsIsAdminPage() && !strstr($url,'option=com_zingiriwebshop')) $url=str_replace('?','?option=com_zingiriwebshop&',$url);
			if (!wsIsAdminPage() && !strstr($url,'option=com_zingiriwebshop')) $url=str_replace('?','?option=com_zingiriwebshop&',$url);
		} elseif (ZING_CMS=='dp') {
			if ($url=='index.php') $url='index.php?q=webshop';
			if (!wsIsAdminPage() && !strstr($url,'webshop')) $url=str_replace('?','?q=webshop&',$url);
			if (!wsIsAdminPage() && !strstr($url,'q=webshop')) $url=str_replace('?','?q=webshop&',$url);
			if (wsIsAdminPage()) $url=str_replace("index.php","",$url);
		}

		if ($printurl) echo $url;
		else return $url;
	}
}

if (!function_exists('actionComplete()')) {
	function actionComplete() {

	}
}

if (!function_exists('actionCompleteMessage')) {
	function actionCompleteMessage() {
		global $gfx_dir,$txt;
		$msg='';
		if (isset($_REQUEST['zmsg']) && $_REQUEST['zmsg']) {
			$title=$txt['general13'];
			$message=z_('Changes are saved');
			if ($gfx_dir) $picture=$gfx_dir."/notify.gif";
			else $picture=ZING_APPS_PLAYER_URL.'images/notify.gif';
			$msg ="<table width=\"".$width."%\" class=\"datatable\">";
			$msg.="<tr><td><img src=\"".$picture."\" alt=\"Notify\" height=\"24px\">";
			$msg.='<strong>'.$message.'</strong>'."</td></tr></table>";
			$msg.="<br /><br />";
		}
		return $msg;
	}
}

function loadJavascript($file) {
	global $loadedJavascripts;
	if (!is_array($loadedJavascripts)) {
		$loadedJavascripts=array();
	}
	if (!in_array($file,$loadedJavascripts)) {
		$loadedJavascripts[]=$file;
		return '<script type="text/javascript" src="' . $file . '"></script>';
	} else return '';
}

function aphpsGetFormSaveDir($project,$formName,$prefix='') {
	global $aphps_projects;
	
	if (defined("ZING_APPS_CUSTOM")) $appsFormsDir=ZING_APPS_CUSTOM."apps/forms/";
	elseif (defined("APHPS_DEV_DIR")) $appsFormsDir=APHPS_DEV_DIR.$project."/forms/";
	else $appsFormsDir=ZING_APPS_PLAYER_DIR."forms/";
	if (isset($aphps_projects[$project]['srcdir'])) {
		if ($aphps_projects[$project]['srcdir']) {
			$saveToDir=$aphps_projects[$project]['srcdir'].'forms/'.$prefix.$formName.".json";
		}
	} else {
		if (!in_array($formName,array('faces','frole','flink','faccess'))) {
			$saveToDir=$appsFormsDir.$prefix.$formName.".json";
		} else {
			$saveToDir=ZING_APPS_PLAYER_DIR."forms/".$prefix.$formName.".json";
		}
	}
	return $saveToDir;
}