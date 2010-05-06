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

function get_form_dbtable($form_id)
{
	//get form DB table
	$query = "select form_dbtable from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	return $row['form_dbtable'];

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

	die("no file loaded");
}




function faces_simple_element($element_name,$element_info,$table_data,$element_data) {

	$fieldname = $element_info['dbfield'];
	$prefix="";
	if (substr($fieldnames[0],0,1) == "#")
	{
		$prefix=substr($fieldnames[0],1-strlen($fieldnames[0]));
	}

	if (empty($prefix) && empty($fieldname)) {
		$xmlf=faces_get_xml($element_info['type']);
		$fieldname=$xmlf->fields->field1->name;
	} elseif (!empty($prefix) && empty($fieldname)) {
		$xmlf=faces_get_xml($element_info['type']);
		$fieldname=$prefix."_".$xmlf->fields->field1->name;
	}

	$table_data["{$fieldname}"] = $element_data;
	return $table_data;
}

function faces_simple_entry($fieldname,$multiformat,$entry_data) {

	if (empty($fieldname)) {
		$xmlf=faces_get_xml($multiformat);
		$fieldname=$xmlf->fields->field1->name;
	}
	return $entry_data["{$fieldname}"];
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

function faces_directory($dir,$filters) {
	$handle=opendir($dir);
	$files=array();
	if ($filters == "all"){while(($file = readdir($handle))!==false){$files[] = $file;}}
	if ($filters != "all") {
		$filters=explode(",",$filters);
		while (($file = readdir($handle))!==false) {
			for ($f=0;$f<sizeof($filters);$f++):
			$system=explode(".",$file);
			if ($system[1] == $filters[$f]){
				$files[] = $file;
			}
			endfor;
		}
	}
	closedir($handle);
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

function faces_enrich_field($row,$column_type_lookup,$column_name,$column_enrich_lookup) {

	if (($column_enrich_lookup[$column_name]['type'] == "sql") && !empty($column_enrich_lookup[$column_name]['rule'])) {
		$query=$column_enrich_lookup[$column_name]['rule'];
		$query=str_replace("##",DB_PREFIX,$query);

		while (strpos($query,"?")) {
			$i=strpos($query,"?");
			$before=substr($query,0,$i); //first part of query string
			$after=strstr($query,"?"); //second part of query string
			$after=substr($after,1); // shift second part 1 position
			$i=strpos($after,"?");
			$variable=substr($after,0,$i); //variable part of second part of query string
			$after=strstr($after,"?"); //part after variable
			$after=substr($after,1); //shift part 1 position
			$value=$row[$variable];
			$query=$before.$value.$after;
		}

		$result = do_query($query);
		while ($res = mysql_fetch_row($result))
		{
			return $res[0];
		}
	} elseif ($column_enrich_lookup[$column_name]['type'] == "function") {
		$f=$column_enrich_lookup[$column_name]['rule'];
		if (function_exists($f))
		{
			return $f($row,$column_name);
		}
	} else {
		return htmlspecialchars(str_replace("\r","",str_replace("\n"," ",$row[$column_name])),ENT_QUOTES);
	}

}


function psi_time($row,$column_name) {
	global $psi_incpath;
	if (empty($psi_incpath)) { $psi_incpath=ZING_SCHED_SUB."/phpScheduleIt/"; }
	global $conf;

	require_once($psi_incpath.'lib/Time.class.php');
	$conf['app']['timeFormat']=24;
	$time=new Time();
	return $time->formatTime($row[$column_name]);
}

function psi_date($row,$column_name) {
	global $psi_incpath;
	if (empty($psi_incpath)) { $psi_incpath=ZING_SCHED_SUB."/phpScheduleIt/"; }
	global $conf;
	require_once($psi_incpath.'lib/Time.class.php');
	$conf['app']['timeFormat']=24;
	$time=new Time();
	return $time->formatDate($row[$column_name]+60*$row['STARTTIME'],"%d-%m-%y");
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
	$result=mysql_query($query) or zing_ws_error_handler(1,$query);
	return $result;
}

function AllowAccess($zfaces,$formid="",$action="") {
	Global $dbtablesprefix;

	if (ZingAppsIsAdmin()) $group="ADMIN";
	elseif (function_exists('faces_group')) $group=faces_group();
	else $group="GUEST";

	switch ($zfaces)
	{
		case "form":
		case "list":
			$role=new zfDB();
			$query="select ##faccess.id from ##frole,##faccess where ##faccess.roleid=##frole.id and ##frole.name=".zfqs($group)." and (##faccess.formid=0 OR ##faccess.formid=".zfqs($formid).")";
			if ($role->select($query)) return true;
			break;
		case "edit":
		case "summary":
		case "face":
			if (ZingAppsIsAdmin()) return true;
			break;
	}
	echo "You don't have access to this form";
	return false;

}


function zf_json_decode($json,$assoc=true,$strip=true) {
	if ($strip) {
		$json=str_replace('\"','"',$json);
		$json=str_replace("\\",'',$json);
		$json=str_replace("\'",'"',$json);
	}
	zing_ws_error_handler(0,'stripped:'.$json);

	if (!extension_loaded("json")){
		require_once(dirname(__FILE__).'/JSON.php');
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
	$include=array("frole","faccess","flink","settings");
	if (!defined("ZING_APPS_BUILDER")) return true;
	if (!empty($table) && !in_array($table,$include)) return true;
	$query=str_replace(DB_PREFIX,"##",$query);
	if (defined("ZING_APPS_CUSTOM")) $dir=ZING_APPS_CUSTOM.'../tmp/';
	else $dir=ZING_APPS_PLAYER_DIR.'db/';
	$file=$dir."apps.db.sql";
	$handle = fopen($file, "a");
	if (!fwrite($handle, $query.";\r\n"))
	{
		return false;
	}
	else {
		fclose($handle);
	}
	//chmod($file,0666);
	return true;
}

function showForm() {
	global $line;
	$notitle=true;
	require(dirname(__FILE__).'/../scripts/form.php');
}

function showList() {
	global $line;
	$notitle=true;
	require(dirname(__FILE__).'/../scripts/list.php');
}

?>