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

	$url_details=ZING_APPS_CUSTOM.'fields/'.$type.".xml";
	if (file_exists($url_details)) {
		if ($xmlf=simplexml_load_file($url_details)) return $xmlf;
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

function faces_data_element($element_name,$element_info,$user_input,$table_data){

	global $facesforcedvalues;

	$fieldnameset=$element_info['dbfield'];
	$fieldnames = array();
	$fieldnames = explode(',',$fieldnameset);

	$prefix="";
	if (substr($fieldnames[0],0,1) == "#")
	{
		$prefix=substr($fieldnames[0],1-strlen($fieldnames[0]));
	}

	if ($element_info['type'] == 'bddress') {
		$multiformat=$element_info['constraint'];
	} else {
		$multiformat=$element_info['type'];
	}

	$xmlf=faces_get_xml($multiformat);
	$fields=$xmlf->fields->attributes()->count;
	for ($i=1; $i <= $fields; $i++) {
		if (empty($prefix) && empty($fieldnames[$i-1])) {
			$fieldname=$xmlf->fields->{'field'.$i}->name;
		} elseif (!empty($prefix)) {
			$fieldname=$prefix.'_'.$xmlf->fields->{'field'.$i}->name;
		} else {
			$fieldname=$fieldnames[$i-1];
		}
		$format=$xmlf->fields->{'field'.$i}->format;
		if ($format != "none") {
			$el_name = substr($element_name,0,-1).$i;
			if ($xmlf->fields->{'field'.$i}->type == "password") {
				$table_data["{$fieldname}"] = md5($user_input[$el_name]);
			} elseif (isset($facesforcedvalues["{$fieldname}"])) {
				$table_data["{$fieldname}"] = $facesforcedvalues["{$fieldname}"];
			} elseif (($xmlf->fields->{'field'.$i}->format == "double") && (empty($user_input[$el_name]))) {
				$table_data["{$fieldname}"] = NULL;
			} else {
				$table_data["{$fieldname}"] = $user_input[$el_name];
			}
		}
	}

	return $table_data;

}

function faces_lookup_headers($fieldnameset,$multiformat,$type,&$column_name_lookup,&$column_type_lookup,$element_title,&$column_enrich_lookup = null) {

	if ($type == 'section') { return ""; }

	$fieldnames = array();
	$fieldnames = explode(',',$fieldnameset);

	$prefix="";
	if (substr($fieldnames[0],0,1) == "#")
	{
		$prefix=substr($fieldnames[0],1-strlen($fieldnames[0]));
	}

	$xmlf=faces_get_xml($multiformat);
	$fields=$xmlf->fields->attributes()->count;
	for ($i=1; $i <= $fields; $i++) {
		if (empty($prefix) && empty($fieldnames[$i-1])) {
			$fieldname=$xmlf->fields->{'field'.$i}->name;
		} elseif (!empty($prefix)) {
			$fieldname=$prefix.'_'.$xmlf->fields->{'field'.$i}->name;
		} else {
			$fieldname=$fieldnames[$i-1];
		}
		$format=$xmlf->fields->{'field'.$i}->format;
		$el_name = substr($element_name,0,-1).$i;
		if ($format != 'none') {
			if ($fields == 1) {
				$column_name_lookup["{$fieldname}"] = $element_title;
				if (isset($xmlf->fields->{'field'.$i}->enrich)) {
					$column_enrich_lookup["{$fieldname}"]['type'] = (string) $xmlf->fields->{'field'.$i}->enrich->attributes()->type;
				}
				$column_enrich_lookup["{$fieldname}"]['rule'] = (string) $xmlf->fields->field1->enrich->query;
				$column_type_lookup["{$fieldname}"] = $type;
			} else {
				$column_name_lookup["{$fieldname}"] = (string) $element_title." ".$xmlf->fields->{'field'.$i}->label;
				//ebo				$column_name_lookup["{$fieldname}"] = $xmlf->fields->{'field'.$i}->label;
				if (isset($xmlf->fields->{'field'.$i}->enrich)) {
					$column_enrich_lookup["{$fieldname}"]['type'] = (string) $xmlf->fields->{'field'.$i}->enrich->attributes()->type;
				}
				$column_enrich_lookup["{$fieldname}"]['rule'] = (string) $xmlf->fields->{'field'.$i}->enrich->query;
				$column_type_lookup["{$fieldname}"] = $type;
			}
		}
	}
}

function faces_entry_fields($element_id,$fieldnameset,$multiformat,&$form_values,$entry_data) {

	if ($multiformat == 'section') { return ""; }

	$fieldnames = array();
	$fieldnames = explode(',',$fieldnameset);

	$xmlf=faces_get_xml($multiformat);
	$fields=$xmlf->fields->attributes()->count;

	$prefix="";
	if (substr($fieldnames[0],0,1) == "#")
	{
		$prefix=substr($fieldnames[0],1-strlen($fieldnames[0]));
	}

	for ($i=1; $i <= $fields; $i++) {
		if (empty($prefix) && empty($fieldnames[$i-1])) {
			$fieldname=$xmlf->fields->{'field'.$i}->name;
		} elseif (!empty($prefix)) {
			$fieldname=$prefix.'_'.$xmlf->fields->{'field'.$i}->name;
		} else {
			$fieldname=$fieldnames[$i-1];
		}
		$format=$xmlf->fields->{'field'.$i}->format;
		if ($format != "none") {
			if ($values=$xmlf->fields->{'field'.$i}->type != "password") {
				if ($fields == 1) {
					$form_values['element_'.$element_id]['default_value'] = $entry_data["{$fieldname}"];
				} else {
					$form_values['element_'.$element_id.'_'.$i]['default_value'] = $entry_data["{$fieldname}"];
				}
			}
		}
	}

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

function faces_count($face) {
	$xmlf=faces_get_xml($face);
	$fields=$xmlf->fields->attributes()->count;
	return $fields;
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
	$result=mysql_query($query) or die($query);
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


function zf_json_decode($json,$assoc=true) {
	$json=str_replace('\"','"',$json);
	$json=str_replace("\\",'',$json);
	$json=str_replace("\'",'"',$json);
	if (!extension_loaded('json')){
		$j = new Services_JSON;
		$ret = $j->unserialize($json);
	}
	else{
		$ret = json_decode($json,$assoc);
	}
	return $ret;
}

function zfDumpQuery($query,$table="") {
	if (!defined("ZING_APPS_BUILDER")) return true;
	$query=str_replace(DB_PREFIX,"##",$query);
	if (defined("ZING_APPS_CUSTOM")) $dir=ZING_APPS_CUSTOM.'db/';
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
	chmod($file,0666);
	return true;
}
?>