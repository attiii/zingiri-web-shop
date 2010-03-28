<?php
function zfCreateTable($entity) {
	$newtable=new zfDB();
	$query="CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$entity."`";
	$query.="(
  		`ID` int(11) NOT NULL auto_increment,
  		`DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  		`DATE_UPDATED` datetime default NULL,
  		PRIMARY KEY  (`ID`))";
	if ($newtable->update($query) && function_exists('zfDumpQuery')) zfDumpQuery($query);
}
function zfTableExists($entity) {
	$db=new zfDB();
	return $db->exists($entity);
}
function zfCreateColumns($entity,$data)
{
	global $allfields;
	$allfields=array('ID','DATE_CREATED','DATE_UPDATED');
	
	$newtable=new zfDB();
	if (is_new_field($entity,'ID')) {
		$query="ALTER TABLE `".DB_PREFIX.$entity."`";
		$query.="ADD COLUMN `ID` int(11) NOT NULL auto_increment PRIMARY KEY";
		if ($newtable->update($query)) zfDumpQuery($query);
	}
	if (is_new_field($entity,'DATE_CREATED')) {
		$query="ALTER TABLE `".DB_PREFIX.$entity."`";
		$query.="ADD COLUMN `DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00'";
		if ($newtable->update($query)) zfDumpQuery($query);
	}
	if (is_new_field($entity,'DATE_UPDATED')) {
		$query="ALTER TABLE `".DB_PREFIX.$entity."`";
		$query.="ADD `DATE_UPDATED` datetime default NULL";
		if ($newtable->update($query)) zfDumpQuery($query);
	}
	$jdata=zf_json_decode($data,true);
	foreach ($jdata as $element) {
		faces_add_element($element['column'],$element['type'],$entity);
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
function faces_add_element($fieldname,$multiformat,$form_dbtable){
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

	$table=new zfDB();
	if (!$isfirst && $table->update($query)) zfDumpQuery($query);
}

function zfShowColumns($form_dbtable) {
	$table=new zfDB();
	$query = "SHOW COLUMNS FROM `".DB_PREFIX."{$form_dbtable}` ";
	$result = do_query($query);

	$columns=mysql_num_rows($result);
	while ($row = mysql_fetch_row($result))
	{
		$field_array[] = $row[0];
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

function zfCreate($name,$elementcount,$entity,$type,$data,$label,$id=false) {
	
	$keysread['NAME']=$name;
	$keys="";
	if ($r=zfReadRecord("faces",$keysread))
	{
		$id=$r['ID'];
		$keys['NAME']=$name;
		$row['ELEMENTCOUNT']=$elementcount;
		$row['ENTITY']=$entity;
		$row['TYPE']=$type;
		$row['DATA']=$data;
		$row['LABEL']=$label;
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