<?php
function zfCreateTable($entity) {
	$newtable=new aphpsDb();
	$query="CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$entity."`";
	$query.="(
  		`ID` int(11) NOT NULL auto_increment,
  		`DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  		`DATE_UPDATED` datetime default NULL,
  		PRIMARY KEY  (`ID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$newtable->update($query);
}

function zfTableExists($entity) {
	$db=new aphpsDb();
	return $db->exists($entity);
}

function zfCreateColumns($entity,$data)
{
	global $allfields;
	$allfields=array('ID','DATE_CREATED','DATE_UPDATED');

	$newtable=new aphpsDb();
	$query='';
	if (is_new_field($entity,'ID')==1) {
		if (!$query) $query="ALTER TABLE `".DB_PREFIX.$entity."`";
		$query.="ADD COLUMN `ID` int(11) NOT NULL auto_increment PRIMARY KEY";
	}
	if (is_new_field($entity,'DATE_CREATED')==1) {
		if (!$query) $query="ALTER TABLE `".DB_PREFIX.$entity."`";
		else $query.=' , ';
		$query.="ADD COLUMN `DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00'";
	}
	if (is_new_field($entity,'DATE_UPDATED')==1) {
		if (!$query) $query="ALTER TABLE `".DB_PREFIX.$entity."`";
		else $query.=' , ';
		$query.="ADD `DATE_UPDATED` datetime default NULL";
	}
	if ($query)	$newtable->update($query);
	$jdata=zf_json_decode($data,true,true);
	foreach ($jdata as $element) {
		if ($element['column']!='ID' && $element['column']!='DATE_CREATED' && $element['column']!='DATE_UPDATED') {
			$zfrepeatable=isset($element['attributes']['zfrepeatable']) ? $element['attributes']['zfrepeatable'] : '';
			$zfmaxlength=isset($element['attributes']['zfmaxlength']) ? $element['attributes']['zfmaxlength'] : '';
			if ($zfrepeatable || $element['type']=='system_subformproxy') faces_add_repeatable_element($element['column'],$element['type'],$entity,$zfmaxlength);
			else faces_add_element($element['column'],$element['type'],$entity,$zfmaxlength);
		}
	}

	//$fieldsInDb=zfShowColumns($entity);
	//$fieldsToDelete=array_diff($fieldsInDb,$allfields); //nothing is done with this for now

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
	$fields=isset($xmlf->fields->attributes()->count) ? $xmlf->fields->attributes()->count : $xmlf->fields->count();
	
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
		
		if ($maxlength > 0) { 
			$format=preg_replace('/varchar(.*)/','varchar('.$maxlength.')',$format);
			//$format=preg_replace('/tinyint(.*)/','tinyint('.$maxlength.')',$format);
			$format=preg_replace('/int(.*)/','int('.$maxlength.')',$format);
		}
		if (!empty($format) && $format != "none") {
			$isNewField=is_new_field($form_dbtable,$fieldname,$format);
			if ($isNewField==1) { //new field
				if (!$isfirst) $query.= ", ";
				$query.="ADD COLUMN `{$fieldname}` {$format} NULL";
				$isfirst=FALSE;
			} elseif ($isNewField==2) { //updated field
				if (!$isfirst) $query.= ", ";
				$query.="CHANGE `{$fieldname}` `{$fieldname}` {$format} NULL";
				$isfirst=FALSE;
			}
		}
		$allfields[]=$fieldname;
	}

	
	$table=new aphpsDb();
	if (!$isfirst && $table->update($query)) {
		//continue
	}
	
}

function faces_add_repeatable_element($fieldname,$multiformat,$form_dbtable,$maxlength) {
	$newtable=new aphpsDb();
	$query="CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$form_dbtable."_attributes`";
	$query.="(
  		`ID` int(11) NOT NULL auto_increment,
  		`DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  		`DATE_UPDATED` datetime default NULL,
  		`PARENTID` int(11) NOT NULL,
  		`SET` int(11) NOT NULL,
  		`NAME` varchar(64) NOT NULL,
  		`VALUE` text NULL,
  		PRIMARY KEY  (`ID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$newtable->update($query);
}

function zfShowColumns($form_dbtable) {
	$table=new aphpsDb();
	$query = "SHOW COLUMNS FROM `".DB_PREFIX."{$form_dbtable}` ";
	$result = do_query($query);

	$columns=mysql_num_rows($result);
	while ($row = mysql_fetch_row($result))
	{
		$field_array[strtoupper($row[0])] = array('type' => strtoupper($row[1]));

	}

	return $field_array;
}
function is_new_field($form_dbtable,$fieldname,$format='')
{
	$field_array = zfShowColumns($form_dbtable);
	if (isset($field_array[strtoupper($fieldname)])) {
		if (!$format) return 2; //update field
		if ($field_array[strtoupper($fieldname)]['type']== strtoupper($format)) return 0; //no change
		else { 
			return 2; //update field
		}
		
	}
	return 1; //new field
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
