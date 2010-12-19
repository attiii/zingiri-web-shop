<?php
/*  form.class.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of APhPS.

 APhPS is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 APhPS is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with APhPS; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
class zfForm {
	var $form;
	var $id;
	var $json;
	var $input;
	var $output=array();
	var $type;
	var $entity;
	var $elementcount;
	var $error=false;
	var $elements=array();
	var $query;
	var $db;
	var $headers=array();
	var $allheaders=array();
	var $fields=array();
	var $allfields=array();
	var $map=array();
	var $format=array();
	var $rowsCount;
	var $label;
	var $search;
	var $searchable=false; //whether form contains searcheable fields
	var $action;
	var $errorMessage;
	var $recid;
	var $rec;
	var $success;
	var $page;
	var $filter;
	var $data=array();
	var $orderKeys="`ID`";
	var $before=array();
	var $allFieldAttributes=array(); //all field attributes
	var $maxRows;

	function zfForm($form,$id=0,$post=null,$action="",$page="",$id='') {
		$this->recid=$id;
		$this->page=$page;
		$this->action=$action;
		$this->form=$form;
		$this->maxRows=ZING_APPS_MAX_ROWS;
		$table=new db();
		if ($form) $query="select * from `".DB_PREFIX."faces` WHERE `NAME`=".zfqs($form);
		else $query="select * from `".DB_PREFIX."faces` WHERE `ID`=".zfqs($id);
		$table->select($query);
		if ($row=$table->next())
		{
			$this->id=$row['ID'];
			$this->form=$row['NAME'];
			$post=$this->filter($post);
			$this->label=$row['LABEL'];
			if ($row['CUSTOM']!='') $this->json=zf_json_decode($row['CUSTOM'],true); //form data
			else $this->json=zf_json_decode($row['DATA'],true);
			$this->elementcount=$row['ELEMENTCOUNT'];
			$this->type=$row['TYPE'];
			$this->entity=$row['ENTITY'];
				
			//check if form has a sub form to include
			$this->includeSubForm();

			foreach ($this->json as $i => $value)
			{
				$key=$value['id'];
				$element=new element($value['type']);
				$this->elements['name'][$key]=$element->name;
				$this->elements['label'][$key]=$element->sublabel;
				$this->elements['format'][$key]=$element->format;
				$this->elements['cat'][$key]=$element->cat;
				$this->column[$key]=$this->json[$key]['column'];
				if (count($value['rules']) > 0) $this->elements['rules'][$key]=$value['rules'];
			}
			$this->headers=$this->Headers();
			$this->headersCount=count($this->headers);
			$this->allheaders=$this->Headers(true);

			$this->post=$post;
		}
		else
		{
			$this->json=false;
			$this->error=true;
		}
		$this->init();
	}

	function includeSubForm() {
		$setsOnly=array();

		//check if there are any sets to include but not initialised yet
		if ($this->action=='add' && !$this->setId) {
			foreach ($this->json as $i => $value)
			{
				if ($value['type']=='system_subformproxy') {
					$linkedElement=$value['subelements'][1]['populate'];
					$dbValue=$_POST['element_'.$linkedElement.'_1'];
					if (is_numeric($dbValue)) {
						$dbField=$value['subelements'][2]['populate'];
						$dbKey=$this->json[$linkedElement]['subelements'][2]['populate'];
						$dbTable=$this->json[$linkedElement]['subelements'][4]['populate'];
						$query="select ".$dbField." from ##".$dbTable." where ".$dbKey."=".qs($dbValue);
						//echo '<br />'.$linkedElement.'-'.$dbField.'-'.$dbKey.'-'.$dbTable;
						$db=new db();
						$db->select($query);
						if ($row=$db->next()) {
							$this->setId=$db->get($dbField);
						}
						$this->json[$i]['readonly']=1;
					} else {
						$setsOnly[$i]=$this->json[$linkedElement];
					}
				}
			}
			if (count($setsOnly)>0) {
				$this->json=$setsOnly;
				$this->newstep='poll';
			}
		} elseif (($this->action=='edit' || $this->action=='view') && !$this->setId) {
			foreach ($this->json as $i => $value)
			{
				if ($value['type']=='system_subformproxy') {
					$linkedElement=$value['subelements'][1]['populate'];
					$getField=$this->json[$linkedElement]['column'];
					$db=new db();
					$query="select ".$getField." from ##".$this->entity. " where id=".qs($this->recid);
					$db->select($query);
					if ($db->next()) {
						$dbValue=$db->get($getField);
					}
					//echo '<br />'.$linkedElement.'-'.$dbField.'-'.$dbKey.'-'.$dbTable;
						
					if (is_numeric($dbValue)) {
						$dbField=$value['subelements'][2]['populate'];
						$dbKey=$this->json[$linkedElement]['subelements'][2]['populate'];
						$dbTable=$this->json[$linkedElement]['subelements'][4]['populate'];
						$query="select ".$dbField." from ##".$dbTable." where ".$dbKey."=".qs($dbValue);
						$db=new db();
						$db->select($query);
						if ($row=$db->next()) {
							$this->setId=$db->get($dbField);
						}
						$this->json[$i]['hidden']=1;
					}
				}
			}
		}
		if (($this->action=='add' && $this->setId) || $this->action=='edit' || $this->action=='view') {
			$json_set=array();
			$maxId=0;
			foreach ($this->json as $i => $value)
			{
				$maxI=max($maxI,$i);
				$maxId=max($maxId,$value['id']);
				if ($value['type']=='system_subformproxy') {
					//$setsOnly[$i]=$value;
					$db=new db();
					$db->select("select * from `".DB_PREFIX."faces` WHERE `ID`=".zfqs($this->setId));
					if ($row=$db->next()) {
						if ($row['CUSTOM']!='') $json_set=zf_json_decode($row['CUSTOM'],true); //form data
						else $json_set=zf_json_decode($row['DATA'],true);
					}

				}
			}
			if (count($json_set)>0) {
				foreach ($json_set as $i => $value) {
					$maxId++;
					$maxI++;
					$json_set[$i]['id']=$maxId;
					$json_set[$i]['attributes']['zfmeta']=1;
					$this->json[$maxI]=$json_set[$i];
				}
			}
		}
	}

	function init() {
		return true;
	}

	function filter($post='') {
		$linksin=new db();
		$query="select * from ##flink where (displayout='".$this->page."' or displayout='any') and formout='".$this->id."' and mapping <> ''";
		$linksin->select($query);
		while ($l=$linksin->next()) {
			if (!empty($l['CONTEXT'])) {
				$context=eval('return '.$l['CONTEXT'].';');
			} else {
				$context=true;
			}
			if ($context) {
				$s=explode(",",$l['MAPPING']);
				foreach ($s as $m) {
					$f=explode(":",$m);
					$post[$f[0]]=$f[1];
				}
			}
		}
		$this->filter=$post;
		return $post;
	}

	function Headers($all=false)
	{
		$h=array(); //unsorted headers, indexed by sub element number
		$c=array(); //map element to entity field name
		$f=array(); //unsorted fields, indexed by sub element number
		$s=array(); //sort order for headers (excluding attributes), indexed by sub element number
		$sa=array(); //sort order for headers (including attributes), indexed by sub element number
		$g=array(); //sorted headers, indexed by sub element number
		$e=array(); //sorted fields, indexed by sub element number
		$m=array(); //sorted fields (excluding attributes), indexed by sub element number

		foreach ($this->json as $i => $value)
		{
			$key1=$value['id'];
			foreach ($value['subelements'] as $key2 => $value2)
			{
				if ($this->elements['format'][$key1][$key2] != 'none') {
					if ($all || !$value2['hide']) {
						if (!isset($value2['sortorder'])) $value2['sortorder']=1;
						if (!$value['attributes']['zfrepeatable'] && !$value['attributes']['zfmeta']) {
							$s[$key1*100+$key2]=$value2['sortorder'];
						} else {
							$sa[$key1*100+$key2]=$value2['sortorder'];
						}
					}
				}
			}
		}
		asort($s);
		asort($sa);

		foreach ($this->json as $i => $value)
		{
			$key1=$value['id'];
			if (!count($value))
			{
				if ($this->elements['format'][$key1] != 'none') {
					$h[$key1*100+1]=$value['label'];
					$c['element_'.$key1]=strtolower($value['column']);
				}
			}
			else
			{
				$count=$this->countSubelements($value['subelements'],$key1);
				foreach ($value['subelements'] as $key2 => $value2)
				{
					if ($this->elements['format'][$key1][$key2] != 'none') {
						if ($all || !$value2['hide']) {
								
							if ($count > 1) {
								$f[$key1*100+$key2]=strtoupper('`'.$value['column'].'_'.$this->elements['name'][$key1][$key2].'`');
								if (defined("ZING_APPS_TRANSLATE")) {
									$tempfunc=ZING_APPS_TRANSLATE;
									//$h[$key1*100+$key2]=$tempfunc($value['label']).' '.$tempfunc($this->elements['label'][$key1][$key2]);
									$h[$key1*100+$key2]=$tempfunc($this->elements['label'][$key1][$key2]);
								} else {
									$h[$key1*100+$key2]=$value['label'].' '.$this->elements['label'][$key1][$key2];
								}
							}
							else {
								$f[$key1*100+$key2]=strtoupper('`'.$value['column'].'`');
								if (defined("ZING_APPS_TRANSLATE")) {
									$tempfunc=ZING_APPS_TRANSLATE;
									$h[$key1*100+$key2]=$tempfunc($value['label']);
								} else {
									$h[$key1*100+$key2]=$value['label'];
								}
							}
						}
						$c['element_'.$key1."_".$key2]=strtolower($value['column']);
					}
				}
			}
		}

		foreach ($s as $key => $sortorder) {
			$g[$key]=$h[$key];
			$e[$key]=$f[$key];
		}
		foreach ($sa as $key => $sortorder) {
			$m[$key]=$f[$key];
		}

		if ($all) $this->map=$c;
		if ($all) $this->allfields=$e; else $this->fields=$e;
		if ($all) $this->allFieldAttributes=$m;

		return $g;

	}

	function countSubelements($sub,$key1) {
		$count=0;
		foreach ($sub as $key2 => $value2) {
			if ($this->elements['format'][$key1][$key2] != 'none') $count++;
		}
		return $count;
	}

	function Render($mode="edit",$prefix="")
	{
		$jsRules=array();
		$ret='';
		$js='';
		$tabs='';
		$dividers=array();
		$numDiv=0;

		$populated_value=array();
		$populated_column=array();
		if ($a=$this->json)
		{
			$isFirst=true;
			foreach ($a as $i => $value)
			{
				$key=$value['id'];
				$element=new element($value['type']);
				$element->isRepeatable=$value['attributes']['zfrepeatable'];
				$element->title=$value['label'];
				$element->id=$value['id'];
				$element->is_error=$this->elements['is_error'][$key];
				$element->error_message=$this->elements['error_message'][$key];
				$element->is_required=$value['mandatory'];
				$element->is_searchable=$value['searchable'];
				if ($value['searchable']) $this->searchable=true;
				$element->readonly=$value['readonly'];
				if ($value['searchable'] && $mode=="search") $element->readonly='';
				$element->hidden=$value['hidden'];
				$element->attributes=$value['attributes'];
				$element->unique=$value['unique'];
				$element->linksin=$value['links'];
				$element->rules=$this->elements['rules'][$key];
				$element->showSubscript=true;

				$c=$this->countSubelements($value['subelements'],$key);

				$ca=0;
				if ($element->isRepeatable) {
					foreach ($value['subelements'] as $key2 => $sub) {
						$ca=max($ca,count($this->input['element_'.$key.'_'.$key2]));
					}
				}

				//if (!$element->isRepeatable || $ca==0) {
				//if (1==1) {
				foreach ($value['subelements'] as $key2 => $sub)
				{
					if (isset($this->elements['cat'][$key][$key2]) && $this->elements['cat'][$key][$key2]=='parameter') {
						$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
					}
					elseif (isset($sub['populate']) && empty($this->input))
					{
						$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
					}
					elseif (!empty($this->input))
					{
						$populated_value['element_'.$key.'_'.$key2]=$this->input['element_'.$key.'_'.$key2];
					}
					if ($c > 1) {
						$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
					} else {
						$f=$this->column[$key];
					}
					$populated_column[$f]=$populated_value['element_'.$key.'_'.$key2];
				}
				if ($isFirst) {
					$ret.='<ul id="zfaces'.$numDiv.'" class="zfaces">';
					$numDiv++;
				}
				elseif ($element->constraint=='system_divider') {
					$ret.='</ul>';
					$ret.='<ul id="zfaces'.$numDiv.'" class="zfaces">';
					$numDiv++;
				}
				$element_markup='<li class="zfli" style="background-image:none;">';
				$element->populated_value=$populated_value;
				$element->populated_column=$populated_column;
				$element->column=$this->column;
				$element->prepare();
				$retDisplay=$element->display($mode);
				if (count($retDisplay['jsrule']) > 0) $jsRules[]=$retDisplay['jsrule'];
				if ($prefix) $element_markup.=str_replace('element_',$prefix.'_element_',$retDisplay['markup']);
				else $element_markup.=$retDisplay['markup'];
				$element_markup.='</li>';
				if ($element->constraint=='system_divider') {
					$dividers[]=$element->divider;
				} else {
					$ret.=$element_markup;
				}
				$isFirst=false;
				/*
				 } else {
					for ($a=0; $a<$ca; $a++) {
					if ($a<$ca-1) $element->showSubscript=false;
					else $element->showSubscript=true;
					foreach ($value['subelements'] as $key2 => $sub)
					{
					if (isset($this->elements['cat'][$key][$key2]) && $this->elements['cat'][$key][$key2]=='parameter') {
					$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
					}
					elseif (isset($sub['populate']) && empty($this->input))
					{
					$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
					}
					elseif (!empty($this->input))
					{
					$populated_value['element_'.$key.'_'.$key2]=$this->input['element_'.$key.'_'.$key2][$a];
					}
					if ($c > 1) {
					$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
					} else {
					$f=$this->column[$key];
					}
					$populated_column[$f]=$populated_value['element_'.$key.'_'.$key2];
					}
					$ret.='<li class="zfli" style="background-image:none;">';
					$element->populated_value=$populated_value;
					$element->populated_column=$populated_column;
					$element->column=$this->column;
					$element->prepare();
					if ($prefix) $ret.=str_replace('element_',$prefix.'_element_',$element->display($mode));
					else $ret.=$element->display($mode);
					$ret.='</li>';
					}
					}
					*/

				$this->elements[$key]=$element->name;

			}
		}
		$ret.='</ul>';
		if (count($dividers) > 0) {
			$tabs='<ul>';
			foreach ($dividers as $id => $divider) {
				$tabs.='<li class="zfacestab"><a href="#zfaces'.$id.'">';
				$tabs.=$divider;
				$tabs.='</a></li>';
			}
			$tabs.='<ul>';
			$js='<script type="text/javascript" language="javascript">';
			$js.='//<![CDATA['.chr(13);
			$js.='jQuery(document).ready(function() {';
			$js.="jQuery('#zfacestabs').tabs();";
			$js.="});";
			$js.=chr(13)." //]]>";
			$js.="</script>";
		}
		$ret=$tabs.$ret;
		error_reporting(E_ALL & ~E_NOTICE);
		ini_set('display_errors', '1');
		
		$ret='<div id="zfacestabs">'.$ret.'</div>'.$js;
		if (count($jsRules) > 0) {
			$js_markup='<script type="text/javascript">';
			$js_markup.='jQuery(document).ready(function() {';
			foreach ($jsRules as $jsRule) {
				$data=$jsRule[0];
				if (isset($data['fnct']) && function_exists($data['fnct'])) {
					$fnct=$data['fnct'];
					$data['value']=$this->input['element_'.$data['field'].'_'.$data['subField']];
					$r=$fnct($data);
				}
				$js_markup.="wsFormField.add(".json_encode($data,JSON_FORCE_OBJECT).",".$jsRule[1].",".$r['result'].");";
			}
			$js_markup.='});';
			$js_markup.='</script>';
		}
		$ret.=$js_markup;
		echo $ret;
		return $ret;
	}

	function Verify($input,$id=0)
	{
		if ($id) { //get image of record before update
			$query="select * from `".DB_PREFIX.$this->entity."` where `ID`=".zfqs($id);
			$db=new db();
			$db->select($query);
			$this->before=$db->next();
		}
		$success=true;
		$this->input=$this->sanitize($input);
		foreach ($this->json as $key => $value)
		{
			$element=new element($value['type']);
			$element->id=$key;
			$element->is_required=$value['mandatory'];
			$element->is_searchable=$value['searchable'];
			$element->readonly=$value['readonly'];
			$element->hidden=$value['hidden'];
			$element->unique=$value['unique'];
			$element->rules=$this->elements['rules'][$key];

			$c=$this->countSubelements($value['subelements'],$key);
			foreach ($value['subelements'] as $key2 => $sub)
			{
				if (isset($this->elements['cat'][$key][$key2]) && $this->elements['cat'][$key][$key2]=='parameter') {
					$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
				}
				elseif (isset($sub['populate']) && empty($this->input))
				{
					$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
				}
				elseif (!empty($this->input))
				{
					$populated_value['element_'.$key.'_'.$key2]=$this->input['element_'.$key.'_'.$key2];
				}
				if ($c > 1) {
					$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
				} else {
					$f=$this->column[$key];
				}
				$populated_column[$f]=$this->input['element_'.$key.'_'.$key2];
			}
			$element->populated_value=$populated_value;
			$element->populated_column=$populated_column;
			$sv=$element->Verify($this->input,$this->output);
			$success=$success && $sv;
			$this->elements['name'][$key]=$element->name;
			$this->elements['is_error'][$key]=$element->is_error;
			$this->elements['error_message'][$key]=$element->error_message;
			$this->elements['format'][$key]=$element->format;
			$this->data=$this->populated_column;
		}
		return $success;
	}

	function alert($message)
	{
		echo '<div class="zfalert">'.$message.'</div>';
	}

	function Delete($id)
	{
		//get image of record before update
		$query="select * from `".DB_PREFIX.$this->entity."` where `ID`=".zfqs($id);
		$db=new db();
		$db->select($query);
		$this->before=$db->next();

		$success=true;
		if ($this->type=="DB") $success=$this->DeleteDB($id);
		$this->postDelete();
		$this->alert("Record delete successfull!");
		return $success;
	}

	function DeleteDB($id)
	{
		$keys=array();
		$keys['ID']=$id;
		DeleteRecord($this->entity,$keys,"");
	}

	function postDelete() {
		return true;
	}

	function Save($id=0)
	{
		$success=true;
		if ($this->type=="DB") $id=$this->SaveDB($id);
		$this->recid=$id;
		$this->postSaveElements();
		$this->postSave();
		$this->alert("Save successfull!");
		return $success;
	}

	function postSave() {
		return true;
	}

	function makeRow($id) {
		$row=array();
		$grid=array();
		foreach ($this->json as $key => $value)
		{
			if (!$value['attributes']['zfrepeatable'] && !$value['attributes']['zfmeta']) {
				$count=$this->countSubelements($value['subelements'],$key);
				foreach ($value['subelements'] as $key2 => $sub)
				{
					if ($this->elements['format'][$key][$key2] != "none") {
						if ($count>1 && isset($this->elements['name'][$key][$key2]) && !empty($this->elements['name'][$key][$key2])) $f=$value['column']."_".$this->elements['name'][$key][$key2];
						else $f=$value['column'];
						$v=$this->output['element_'.$key.'_'.$key2];
						$row[$f]=$v;
					}
				}
			} else {
				$count=$this->countSubelements($value['subelements'],$key);
				foreach ($value['subelements'] as $key2 => $sub)
				{
					if ($this->elements['format'][$key][$key2] != "none") {
						if ($count>1 && isset($this->elements['name'][$key][$key2]) && !empty($this->elements['name'][$key][$key2])) $f=$value['column']."_".$this->elements['name'][$key][$key2];
						else $f=$value['column'];
						$v=$this->output['element_'.$key.'_'.$key2];
						$grid[strtoupper($f)]=$v;
					}
				}
			}
		}

		if ($id)
		{
			$row['DATE_UPDATED']=date("Y-m-d H:i:s");

		} else {
			$row['DATE_CREATED']=date("Y-m-d H:i:s");
		}
		$this->rec=$row;
		$this->grid=$grid;
	}

	function SaveDB($id=0)
	{
		$db=new db();

		$this->makeRow($id);
		$row=$this->rec;

		//insert or update main record
		$keys=array();
		if ($id) {
			$keys['ID']=$id;
			$db->updateRecord($this->entity,$keys,$row,"");
		} else {
			$id=$db->insertRecord($this->entity,$keys,$row,"");
		}

		//insert or update attribute records
		if (count($this->grid) > 0) {

			$keys=array();
			$row=array();
			$row['PARENTID']=$id;
			//echo 'save grid'.print_r($this->grid,true);
			foreach ($this->grid as $name => $values) {
				$db->update('delete from ##'.$this->entity.'_attributes where parentid='.qs($id).' and name='.qs($name));
				$row['NAME']=$name;
				$set=0;
				if (is_array($values)) {
					foreach ($values as $value) {
						$set++;
						$row['VALUE']=$value;
						$row['SET']=$set;
						$db->insertRecord($this->entity.'_attributes',array(),$row);
					}
				} else {
					$row['VALUE']=$values;
					$row['SET']=$set;
					$db->insertRecord($this->entity.'_attributes',array(),$row);
				}
			}
		}
		return $id;
	}

	function postSaveElements()
	{
		$success=true;
		foreach ($this->json as $key => $value)
		{
			$element=new element($value['type']);
			$element->id=$key;
			$element->is_required=$value['mandatory'];
			$element->is_searchable=$value['searchable'];
			$element->readonly=$value['readonly'];
			$element->hidden=$value['hidden'];
			$element->unique=$value['unique'];
			$element->rules=$this->elements['rules'][$key];

			$c=$this->countSubelements($value['subelements'],$key);
			foreach ($value['subelements'] as $key2 => $sub)
			{
				if (isset($this->elements['cat'][$key][$key2]) && $this->elements['cat'][$key][$key2]=='parameter') {
					$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
				}
				elseif (isset($sub['populate']) && empty($this->input))
				{
					$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
				}
				elseif (!empty($this->input))
				{
					$populated_value['element_'.$key.'_'.$key2]=$this->input['element_'.$key.'_'.$key2];
				}
				if ($c > 1) {
					$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
				} else {
					$f=$this->column[$key];
				}
				$populated_column[$f]=$this->input['element_'.$key.'_'.$key2];
				$elementToColumn['element_'.$key.'_'.$key2]=$f;
			}
			$element->populated_value=$populated_value;
			$element->populated_column=$populated_column;
			$element->elementToColumn=$elementToColumn;
			$element->action=$this->action;
			$element->type=$this->type;
			$element->entity=$this->entity;
				
			$sv=$element->postSave($this->input,$this->output,$this->recid);
			$success=$success && $sv;
			$this->elements['name'][$key]=$element->name;
			$this->elements['is_error'][$key]=$element->is_error;
			$this->elements['error_message'][$key]=$element->error_message;
			$this->elements['format'][$key]=$element->format;
		}
		return $success;
	}

	function sanitize($input,$escape_mysql=false,$sanitize_html=false,$sanitize_special_chars=false,$allowable_tags=''){
		//FIXME: check this
		//	unset($input['submit']); //we use 'submit' variable for all of our form

		$input_array = $input;
		//array is not referenced when passed into foreach
		//this is why we create another exact array
		foreach ($input as $key=>$value){
			if(!empty($value)){

				//stripslashes added by magic quotes
				if(get_magic_quotes_gpc()){
					if (is_array($input_array[$key])) {
						foreach ($input_array[$key] as $key2 => $value2) {
							$input_array[$key][$key2] = stripslashes($value2);
						}
					} else $input_array[$key] = stripslashes($input_array[$key]);
				}

				if($sanitize_html){
					$input_array[$key] = strip_tags($input_array[$key],$allowable_tags);
				}

				if($sanitize_special_chars){
					$input_array[$key] = htmlspecialchars($input_array[$key]);
				}

				if($escape_mysql){
					$input_array[$key] = mysql_real_escape_string($input_array[$key]);
				}
			}
		}
		return $input_array;
	}

	function setSearch($search,&$map) {
		if (isset($_GET['search']) && is_array($_GET['search'])) $search=array_merge($_GET['search'],$search);

		if (!empty($search)) $this->search=$this->Sanitize($search);
		else return;
		$s="";
		//$where=array();

		if (isset($_GET['search']) && is_array($_GET['search'])) {
			foreach ($this->Sanitize($_GET['search']) as $i => $v) {
				if (!isset($_GET[$i])) $_GET[$i]=$v;
			}
		}

		foreach ($this->search as $id => $value) {
			list($prefix,$key1,$key2)=explode('_',$id);
			$key=100*$key1+$key2;
			if ($value!="" && $prefix=='element') {
				$field=str_replace('`','',$this->allfields[$key]);
				$map[$field]=array('like', $value);
				$s.='&search['.$id.']='.urlencode($value);
			}
		}
		return $s;
	}

	function SelectRows($where="",$pos=0)
	{
		if (empty($pos)) $pos=0;
		if ($this->type=="DB") return $this->SelectRowsDB($where,$pos);
	}

	function SelectRowsDB($where="",$pos=0)
	{
		$s=implode(",",$this->fields);
		$this->query="select `ID`,".$s." from `".DB_PREFIX.$this->entity."` ";
		if ($where) {
			$qwhere="";
			foreach ($where as $field => $value) {
				$qwhere.=empty($qwhere) ? " where " :  " and ";
				if (is_array($value)) {
					if ($value[0]=='like') $qwhere.="`".$field."` LIKE '%".$value[1]."%'";
					else $qwhere.="`".$field."`=".zfqs($value[1]);
				} else {
					if (function_exists($value)) $v=$value();
					elseif (isset($_GET[$value])) $v=$_GET[$value];
					elseif (isset($_POST[$value])) $v=$_POST[$value];
					else $v=$value;
					$qwhere.="`".$field."`=".zfqs($v);
				}
			}
			$this->query.=$qwhere;
		}
		$this->query.=" ORDER BY ".$this->orderKeys;
		$this->db=new db();
		$this->rowsCount=$this->db->select($this->query);

		$this->query.=' LIMIT '.$pos.','.$this->maxRows;

		return $this->db->select($this->query);

	}

	function CountRowsDB() {
	}

	function NextRows()
	{
		$rows=array();
		while ($r=$this->db->next())
		{
			$id=$r['ID'];
			unset($r['ID']);
			$input=array();
			foreach ($this->fields as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				$input['element_'.$key1."_".$key2]=$r[str_replace('`','',$column)];
			}
			foreach ($this->json as $key1 => $sub) {
				foreach ($sub['subelements'] as $key2 => $data) {
					if (!isset($input['element_'.$key1."_".$key2])) $input['element_'.$key1."_".$key2]=$data['populate'];
				}
			}
			$output=$this->output($input,"list");
			$o=array();
			foreach ($this->fields as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				$o[str_replace('`','',$column)]=$output['element_'.$key1."_".$key2];
			}
			$rows[$id]=$o;

		}
		return $rows;
	}

	/*
	 * Converts input format to output
	 */
	function output($input,$mode="edit")
	{

		$output=array();
		foreach ($this->json as $key => $value)
		{
			$element=new element($value['type']);
			$element->id=$value['id'];
			$success=$element->output($input,$output,$mode);
		}

		return $output;
	}

	/*
	 * Prepares form data before displaying it.
	 *
	 * If the data record ID is not filled, a blank form is prefilled with values coming from:
	 * 	$_POST/$_GET in the form of 'element_x' or 'element_x_y'
	 *
	 * If the data record ID is filled, the data record with that ID will be retrieved. Any filters passed via $_POST are
	 * also verified.
	 */
	function Prepare($id=null)
	{
		$this->recid=$id;
		$input=array();
		if (!$id) {
			foreach ($this->allfields as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				if ($_POST['element_'.$key1."_".$key2]) {
					$input['element_'.$key1."_".$key2]=$_POST['element_'.$key1."_".$key2];
				} elseif ($_POST['element_'.$key1]) {
					$input['element_'.$key1]=$_POST['element_'.$key1];
				} elseif ((($f=$this->map['element_'.$key1."_".$key2]) && ($value=$this->post[$f])) || (($f=$this->map['element_'.$key1]) && ($value=$this->post[$f]))) {
					if (function_exists($value)) $v=$value();
					elseif (isset($_GET[$value])) $v=$_GET[$value];
					elseif (isset($_POST[$value])) $v=$_POST[$value];
					else $v=$value;
					$input['element_'.$key1."_".$key2]=$v;
				} elseif ($_GET['element_'.$key1."_".$key2]) {
					$input['element_'.$key1."_".$key2]=$_GET['element_'.$key1."_".$key2];
				} elseif ($_GET['element_'.$key1]) {
					$input['element_'.$key1]=$_GET['element_'.$key1];
				}
			}
		} else {

			$this->query="select * from `".DB_PREFIX.$this->entity."` where `ID`=".zfqs($id);
			if (count($this->post)) {
				foreach ($this->post as $f => $value) {
					if (function_exists($value)) $v=$value();
					elseif (isset($_GET[$value])) $v=$_GET[$value];
					elseif (isset($_POST[$value])) $v=$_POST[$value];
					else $v=$value;
					$this->query.=' AND '.$f.'='.zfqs($v);
				}
			}
			$this->db=new db();
			$this->db->select($this->query);
			if ($r=$this->db->next())
			{
				foreach ($r as $field => $value) {
					$r[strtoupper($field)]=$value;
				}
				foreach ($this->allfields as $key => $column)
				{
					zfKeys($key,$key1,$key2);
					$input['element_'.$key1."_".$key2]=$r[str_replace('`','',$column)];
				}
			} else {
				return $this->postPrepare(false);
			}
			$this->data=$r;

			//load attributes
			//print_r($this->allFieldAttributes);
			$db=new db();
			foreach ($this->allFieldAttributes as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				$query="select * from `".DB_PREFIX.$this->entity."_attributes` where `PARENTID`=".zfqs($id)." AND `NAME`=".zfqs(str_replace('`','',$column))." ORDER BY `SET`";
				$db->select($query);
				while ($r=$db->next()) {
					foreach ($r as $field => $value) {
						$r[strtoupper($field)]=$value;
					}
					if ($r['SET']==0) $input['element_'.$key1."_".$key2]=$r['VALUE'];
					else $input['element_'.$key1."_".$key2][]=$r['VALUE'];
				}
			}
		}
		$this->input=$this->sanitize($input);
		$this->output=$this->output($this->input);
		return $this->postPrepare(true);
	}

	function postPrepare($success) {
		return $success;
	}

	function DeleteMe()
	{
		$keys=array();
		$keys['NAME']=$this->form;
		DeleteRecord("faces",$keys,"");
		return true;
	}

	function searchByFieldName($name) {
		foreach ($this->allfields as $e => $fn) {
			if ($name == str_replace('`','',$fn)) {
				zfKeys($e,$key1,$key2);
				return $this->input['element_'.$key1.'_'.$key2];
			}
		}
		return false;
	}

	function allowAccess() {
		$access=new zfAccess($this->id,$this->page,$this->action,$this->filter,$this->data);
		$allowed=$access->allowed();
		if ($allowed) return true;
		else {
			$this->errorMessage="You don't have access to this form";
			return false;
		}
	}

}
?>