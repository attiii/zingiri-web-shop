<?php
function zfCreateTable($entity) {
	$newtable=new db();
	$query="CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$entity."`";
	$query.="(
  		`ID` int(11) NOT NULL auto_increment,
  		`DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  		`DATE_UPDATED` datetime default NULL,
  		PRIMARY KEY  (`ID`))";
	$newtable->update($query);
}

function zfTableExists($entity) {
	$db=new db();
	return $db->exists($entity);
}

function zfCreateColumns($entity,$data)
{
	global $allfields;
	$allfields=array('ID','DATE_CREATED','DATE_UPDATED');

	$newtable=new db();
	if (is_new_field($entity,'ID')) {
		$query="ALTER TABLE `".DB_PREFIX.$entity."`";
		$query.="ADD COLUMN `ID` int(11) NOT NULL auto_increment PRIMARY KEY";
		$newtable->update($query);
	}
	if (is_new_field($entity,'DATE_CREATED')) {
		$query="ALTER TABLE `".DB_PREFIX.$entity."`";
		$query.="ADD COLUMN `DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00'";
		$newtable->update($query);
	}
	if (is_new_field($entity,'DATE_UPDATED')) {
		$query="ALTER TABLE `".DB_PREFIX.$entity."`";
		$query.="ADD `DATE_UPDATED` datetime default NULL";
		$newtable->update($query);
	}
	$jdata=zf_json_decode($data,true);
	foreach ($jdata as $element) {
		if ($element['column']!='ID' && $element['column']!='DATE_CREATED' && $element['column']!='DATE_UPDATED') {
			if ($element['attributes']['zfrepeatable'] || $element['type']=='system_subformproxy') faces_add_repeatable_element($element['column'],$element['type'],$entity,$element['attributes']['zfmaxlength']);
			else faces_add_element($element['column'],$element['type'],$entity,$element['attributes']['zfmaxlength']);
		}
	}

	$fieldsInDb=zfShowColumns($entity);
	$fieldsToDelete=array_diff($fieldsInDb,$allfields); //nothing is done with this for now

}
/**
 * Adds element to database table
 *
 * @param $fieldnameset
 * @param $multiformat
 * @param $form_dbtable
 * @return unknown_type
 */
function faces_add_element($fieldname,$multiformat,$form_dbtable,$maxlength) {
	global $allfields;
	$xmlf=faces_get_xml($multiformat);
	$fields=$xmlf->fields->attributes()->count;

	//check how many database fields are present
	$realfields=0;
	for ($i=1; $i <= $fields; $i++) {
		if (isset($xmlf->fields->{'field'.$i}->format) && ($xmlf->fields->{'field'.$i}->format != "none")) {
			$realfields++;
		}
	}

	$prefix="";
	if ($realfields > 1) $prefix=$fieldname;

	$isfirst=TRUE;
	$query = "ALTER TABLE `".DB_PREFIX."{$form_dbtable}` ";
	for ($i=1; $i <= $fields; $i++) {
		if (!empty($prefix)) {
			$fieldname=$prefix.'_'.$xmlf->fields->{'field'.$i}->name;
		} else {
			$fieldname=$fieldname;
		}
		$fieldname=strtoupper($fieldname);
		if (isset($xmlf->fields->{'field'.$i}->format)) {
			$format=$xmlf->fields->{'field'.$i}->format;
		} else {
			$format="varchar(255)";
		}
		if ($maxlength > 0) $format=preg_replace('/varchar(.*)/','varchar('.$maxlength.')',$format);
		if (!empty($format) && $format != "none") {
			if (!$isfirst) {
				$query.= ", ";
			}
			if (is_new_field($form_dbtable,$fieldname)) { //new field
				$query.="ADD COLUMN `{$fieldname}` {$format} NULL";
			} else { //updated field
				$query.="CHANGE `{$fieldname}` `{$fieldname}` {$format} NULL";
			}
			$isfirst=FALSE;
		}
		$allfields[]=$fieldname;
	}

	$table=new db();
	if (!$isfirst && $table->update($query)) zfDumpQuery($query);
}

function faces_add_repeatable_element($fieldname,$multiformat,$form_dbtable,$maxlength) {
	$newtable=new db();
	$query="CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$form_dbtable."_attributes`";
	$query.="(
  		`ID` int(11) NOT NULL auto_increment,
  		`DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  		`DATE_UPDATED` datetime default NULL,
  		`PARENTID` int(11) NOT NULL,
  		`SET` int(11) NOT NULL,
  		`NAME` varchar(64) NOT NULL,
  		`VALUE` text NULL,
  		PRIMARY KEY  (`ID`))";
	$newtable->update($query);
}

function zfShowColumns($form_dbtable) {
	$table=new db();
	$query = "SHOW COLUMNS FROM `".DB_PREFIX."{$form_dbtable}` ";
	$result = do_query($query);

	$columns=mysql_num_rows($result);
	while ($row = mysql_fetch_row($result))
	{
		$field_array[] = strtoupper($row[0]);
	}

	return $field_array;
}
function is_new_field($form_dbtable,$fieldname)
{
	$field_array = zfShowColumns($form_dbtable);

	if (!in_array($fieldname,$field_array))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function zfCreate($name,$elementcount,$entity,$type,$data,$label,$project,$id=false,$remote=false) {

	$keysread['NAME']=$name;
	$keys="";
	if ($r=zfReadRecord("faces",$keysread))
	{
		$id=$r['ID'];
		$keys['NAME']=$name;
		$row['ELEMENTCOUNT']=$elementcount;
		$row['ENTITY']=$entity;
		$row['TYPE']=$type;
		if ($remote) $row['CUSTOM']=$data;
		else $row['DATA']=$data;
		$row['LABEL']=$label;
		$row['PROJECT']=$project;
		$same=true;
		foreach($row as $k => $v) {
			if ($r[$k] != $v) $same=false;
		}
		if (!$same) {
			UpdateRecord("faces",$keys,$row);
			$msg="Form updated succesfully";
		} else {
			$msg="No changes detected";
		}
	}
	else
	{
		if ($id) $row['ID']=$id;
		else $keys['ID']=true;
		$row['NAME']=$name;
		$row['ELEMENTCOUNT']=$elementcount;
		$row['ENTITY']=$entity;
		$row['TYPE']=$type;
		$row['DATA']=$data;
		$row['LABEL']=$label;
		$row['PROJECT']=$project;

		$id=InsertRecord("faces",$keys,$row);
		$msg="Form saved succesfully";

	}
	if ($type == "DB")
	{
		if (!zfTableExists($entity)) zfCreateTable($entity);
		zfCreateColumns($entity,$data);
	}

	return $msg;

}
?>