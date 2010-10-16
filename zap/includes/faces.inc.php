<?php
/*  faces.inc.php
 Copyright 2008,2009 Erik Bogaerts
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
function zfKeys($key,&$key1,&$key2)
{
	$key2=(int)substr($key,-2);
	$key1=(int)substr($key,0,strlen($key)-2);
}

function zfGetForm($formid)
{
	$db=new db();
	if ($db->select("select `name` from `##faces` where id=".qs($formid))) {
		$db->next();
		return $db->get('name');
	} else return false;
}


function faces_get_xml($type,$dir="") {
	global $zing;

	if (!empty($dir)) {
		$url_details=$dir.$type.".xml";
		if (file_exists($url_details)) {
			if ($xmlf=simplexml_load_file($url_details)) return $xmlf;
		}
	}

	$url_details=ZING_APPS_PLAYER_DIR.'fields/'.$type.".xml";
	if (file_exists($url_details)) {
		if ($xmlf=simplexml_load_file($url_details)) return $xmlf;
	}

	if ($zing) {
		foreach ($zing->paths as $path) {
			$url_details=$path.'apps/fields/'.$type.".xml";
			if (file_exists($url_details)) {
				if ($xmlf=simplexml_load_file($url_details)) return $xmlf;
			}
		}
	}

	$url_details=ZING_APPS_PLAYER_DIR.'fields/text.xml';
	if (file_exists($url_details)) {
		if ($xmlf=simplexml_load_file($url_details)) return $xmlf;
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
	if ($handle=opendir($dir)) {
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
				if ($system[1] == $filters[$f]){
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

function zfqs($value)
{
	if( is_array($value) ) {
		return array_map("quote_smart", $value);
	} else {


		if( get_magic_quotes_gpc() ) {
			$value = stripslashes($value);
		}

		//		if( $value == '') {
		if( $value == '' && $value != 0) {
			$value = '';
		}
		//	       if( !is_numeric($value) || $value[0] == '0' ) {
		if( !is_numeric($value) ) {


			$value = "'".mysql_escape_string($value)."'";
		}
		return $value;
	}
}

function do_query($query) {
	$result=mysql_query($query) or zing_apps_error_handler(1,$query);
	return $result;
}

function zf_json_decode($json,$assoc=true,$strip=true) {
	if ($strip) {
		$json=str_replace('\"','"',$json);
		$json=str_replace("\\",'',$json);
		$json=str_replace("\'",'"',$json);
	}
	zing_apps_error_handler(0,'stripped:'.$json);

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

function zfDumpQuery($query,$table="") {
	$include=array("frole","faccess","flink");
	if (!defined("ZING_APPS_BUILDER")) return true;
	if (!empty($table) && !in_array($table,$include)) return true;
	$query=str_replace(DB_PREFIX,"##",$query);
	if (defined("ZING_APPS_CUSTOM")) $dir=ZING_APPS_CUSTOM.'../tmp/';
	else $dir=ZING_APPS_PLAYER_DIR.'db/';
	$file=$dir."apps.db.sql";
	if ($handle = fopen($file, "a")) {
		if (!fwrite($handle, $query.";\r\n"))
		{
			return false;
		}
		fclose($handle);
	}

	if (defined("ZING_APPS_CUSTOM_SRCDIR")) {
		if ($handle = fopen (ZING_APPS_CUSTOM_SRCDIR.'../../../tmp/apps.db.sql', "a")) {
			if (fwrite($handle, $query.";\r\n")) fclose($handle);
		}
	}

	//chmod($file,0666);
	return true;
}

function showForm() {
	global $line;
	$notitle=true;
	require(dirname(__FILE__).'/../scripts/form.php');
}

function appsForm($form,$action,$formURL,$noRedirect=true) {
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

if (!function_exists('qs')) {
	function qs($value) {
		if( is_array($value) ) {
			return array_map("quote_smart", $value);
		} else {
			if( get_magic_quotes_gpc() ) {
				$value = stripslashes($value);
			}
			if( $value == '' ) {
				$value = '';
			}
			if( !is_numeric($value) || $value[0] == '0' ) {
				$value = "'".mysql_escape_string($value)."'";
			}
			return $value;
		}
	}
}

if (!function_exists('zurl')) {
	function zurl($url,$printurl=false) {

		if (ZING_CMS=='wp') {
			if (is_admin()) $url=str_replace('index.php','admin.php',$url);
			else {
				if (strstr($url,ZING_HOME)===false) $url=str_replace('index.php',ZING_HOME.'/index.php',$url);
			}
		} elseif (ZING_CMS=='jl') {
			if ($url=='index.php') $url='index.php?option=com_zingiriwebshop';
			if (is_admin() && !strstr($url,'option=com_zingiriwebshop')) $url=str_replace('?','?option=com_zingiriwebshop&',$url);
			if (!is_admin() && !strstr($url,'option=com_zingiriwebshop')) $url=str_replace('?','?option=com_zingiriwebshop&',$url);
		} elseif (ZING_CMS=='dp') {
			if ($url=='index.php') $url='index.php?q=webshop';
			if (!is_admin() && !strstr($url,'webshop')) $url=str_replace('?','?q=webshop&',$url);
			if (is_admin()) $url=str_replace("index.php","",$url);
		}

		if ($printurl) echo $url;
		else return $url;
	}
}

if (!function_exists('actionComplete()')) {
	function actionComplete() {
		
	}
}
?>