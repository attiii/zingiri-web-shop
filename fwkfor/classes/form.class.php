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
	var $errorMessage='Record not found';
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
	var $noAlert=false;
	var $hasSubmit=false;
	var $onSubmitActions=array();
	var $showLabels=true;
	var $formAttributes;

	function zfForm($form=null,$formid=0,$post=null,$action="",$page="",$id='') {
		$this->recid=$id;
		$this->page=$page;
		$this->action=$action;
		$this->form=$form;
		$this->maxRows=ZING_APPS_MAX_ROWS;
		$table=new aphpsDb();
		if ($form) $query="select * from `".DB_PREFIX."faces` WHERE `NAME`=".qs($form);
		else $query="select * from `".DB_PREFIX."faces` WHERE `ID`=".qs($formid);
		$table->select($query);
		if ($row=$table->next())
		{
			$this->id=$row['ID'];
			$this->form=$row['NAME'];
			$post=$this->filter($post);
			$this->label=$row['LABEL'] ? $row['LABEL'] : ucwords($row['NAME']);
			if ($row['CUSTOM']!='') $this->json=zf_json_decode($row['CUSTOM'],true,true,false); //form data
			else $this->json=zf_json_decode($row['DATA'],true,true,false);
			$this->elementcount=$row['ELEMENTCOUNT'];
			$this->type=$row['TYPE'];
			$this->entity=$row['ENTITY'];
			$this->formAttributes=json_decode($row['ATTRIBUTES']);

			//check if form has a sub form to include
			$this->includeSubForm();


			foreach ($this->json as $i => $value)
			{
				$key=$value['id'];
				if ($value['type']=='submit') {
					$this->hasSubmit=true;
				}
				$element=new element($value['type']);
				$this->elements['name'][$key]=$element->name;
				$this->elements['label'][$key]=$element->sublabel;
				$this->elements['format'][$key]=$element->format;
				$this->elements['cat'][$key]=$element->cat;
				$this->column[$key]=$this->json[$key]['column'];
				if (isset($value['rules']) && (count($value['rules']) > 0)) $this->elements['rules'][$key]=$value['rules'];
			}
			$this->headers=$this->Headers();
			$this->headersCount=count($this->headers);
			$this->Headers(true);

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
		if ($this->action=='add' && (!isset($this->setId) || !$this->setId)) {
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
						$db=new aphpsDb();
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
		} elseif (($this->action=='edit' || $this->action=='view') && (!isset($this->setId) || !$this->setId)) {
			foreach ($this->json as $i => $value)
			{
				if ($value['type']=='system_subformproxy') {
					$linkedElement=$value['subelements'][1]['populate'];
					$getField=$this->json[$linkedElement]['column'];
					$db=new aphpsDb();
					$query="select ".$getField." from ##".$this->entity. " where id=".qs($this->recid);
					$db->select($query);
					if ($db->next()) {
						$dbValue=$db->get($getField);
					}

					if (is_numeric($dbValue)) {
						$dbField=$value['subelements'][2]['populate'];
						$dbKey=$this->json[$linkedElement]['subelements'][2]['populate'];
						$dbTable=$this->json[$linkedElement]['subelements'][4]['populate'];
						$query="select ".$dbField." from ##".$dbTable." where ".$dbKey."=".qs($dbValue);
						$db=new aphpsDb();
						$db->select($query);
						if ($row=$db->next()) {
							$this->setId=$db->get($dbField);
						}
						$this->json[$i]['hidden']=1;
					}
				}
			}
		}
		if (($this->action=='add' && isset($this->setId) && $this->setId) || $this->action=='edit' || $this->action=='view') {
			$json_set=array();
			$maxId=0;
			$maxI=0;
			foreach ($this->json as $i => $value)
			{
				$maxI=max($maxI,$i);
				$maxId=max($maxId,$value['id']);
				if ($value['type']=='system_subformproxy') {
					//$setsOnly[$i]=$value;
					$db=new aphpsDb();
					$db->select("select * from `".DB_PREFIX."faces` WHERE `ID`=".qs($this->setId));
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
		$linksin=new aphpsDb();
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
					//if (function_exists($f[1])|| isset($_GET[$value]) || isset($_POST[$value]))
					//$post[$f[0]]=$f[1];
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
		$t=array(); //sorted headers, excluding submit fields

		foreach ($this->json as $i => $value)
		{
			$key1=$value['id'];
			if (count($value['subelements']) > 0) {
				foreach ($value['subelements'] as $key2 => $value2)
				{
					if ($this->elements['format'][$key1][$key2] != 'none') {
						if ($all || !isset($value2['hide']) || !$value2['hide']) {
							if (!isset($value2['sortorder'])) $value2['sortorder']=1;
							if ((!isset($value['attributes']['zfrepeatable']) || !$value['attributes']['zfrepeatable']) && (!isset($value['attributes']['zfmeta']) || !$value['attributes']['zfmeta'])) {
								$s[$key1*100+$key2]=$value2['sortorder'];
							} else {
								$sa[$key1*100+$key2]=$value2['sortorder'];
							}
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
				if (count($value['subelements']) > 0) {
					foreach ($value['subelements'] as $key2 => $value2)
					{
						if ($this->elements['format'][$key1][$key2] != 'none') {
							if ($all || !isset($value2['hide']) || !$value2['hide']) {

								if ($count > 1) {
									$f[$key1*100+$key2]=strtoupper('`'.$value['column'].'_'.$this->elements['name'][$key1][$key2].'`');
									if (defined("ZING_APPS_TRANSLATE")) {
										$tempfunc=ZING_APPS_TRANSLATE;
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
		}

		foreach ($s as $key => $sortorder) {
			$g[$key]=$h[$key];
			$e[$key]=$f[$key];
			$i=round($key/100,0);
			if ($this->json[$i]['type']!='submit') $t[$key]=$h[$key];
		}

		foreach ($sa as $key => $sortorder) {
			$m[$key]=$f[$key];
		}

		if ($all) $this->map=$c;
		if ($all) $this->allfields=$e; else $this->fields=$e;
		if ($all) $this->allFieldAttributes=$m;
		if ($all) $this->allheaders=$t;
		else $this->listHeaders=$t;

		return $g;

	}

	function countSubelements($sub,$key1) {
		$count=0;
		if (count($sub) > 0) {
			foreach ($sub as $key2 => $value2) {
				if ($this->elements['format'][$key1][$key2] != 'none') $count++;
			}
		}
		return $count;
	}

	function Render($mode="edit",$prefix="",$display=true)
	{
		$jsRules=array();
		$ret='';
		$js='';
		$tabs='';
		$dividers=array();
		$numDiv=0;
		$js_markup='';

		$populated_value=array();
		$populated_column=array();
		$parameters=array();
		if ($a=$this->json)
		{
			$isFirst=true;
			foreach ($a as $i => $value)
			{
				$columnName=null;
				$key=$value['id'];
				$element=new element($value['type']);
				$element->isRepeatable=isset($value['attributes']['zfrepeatable']) ? $value['attributes']['zfrepeatable'] : '';
				$element->title=$value['label'];
				$element->id=$value['id'];
				$element->is_error=isset($this->elements['is_error'][$key]) ? $this->elements['is_error'][$key] : '';
				$element->error_message=isset($this->elements['error_message'][$key]) ? $this->elements['error_message'][$key] : '';
				$element->is_required=isset($value['mandatory']) ? $value['mandatory'] : '';
				$element->is_searchable=isset($value['searchable']) ? $value['searchable'] : false;
				if (($mode=='search') && !$element->is_searchable) continue;
				if ($element->is_searchable) $this->searchable=true;
				$element->readonly=isset($value['readonly']) ? $value['readonly'] : '';
				if (isset($value['searchable']) && $value['searchable'] && $mode=="search") $element->readonly='';
				$element->hidden=isset($value['hidden']) ? $value['hidden'] : '';
				$element->attributes=$value['attributes'];
				$element->unique=isset($value['unique']) ? $value['unique'] : '';
				$element->linksin=isset($value['links']) ? $value['links'] : '';
				$element->rules=isset($this->elements['rules'][$key]) ? $this->elements['rules'][$key] : '';
				$element->showSubscript=true;
				$element->showLabels=$this->showLabels;
				$element->formAttributes=$this->formAttributes;

				$c=$this->countSubelements($value['subelements'],$key);
				$ca=0;
				if ($element->isRepeatable) {
					foreach ($value['subelements'] as $key2 => $sub) {
						$ca=max($ca,isset($this->input['element_'.$key.'_'.$key2]) ? count($this->input['element_'.$key.'_'.$key2]) : 0);
					}
				}

				if (count($value['subelements']) > 0) {
					foreach ($value['subelements'] as $key2 => $sub)
					{
						if (isset($sub['parameters'])) {
							$parameters[$key][$key2]=$sub['parameters'];
						}
						if (isset($this->elements['cat'][$key][$key2]) && $this->elements['cat'][$key][$key2]=='parameter') {
							$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
						}
						elseif (isset($sub['populate']) && empty($this->input))
						{
							$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
						}
						elseif (!empty($this->input) && isset($this->input['element_'.$key.'_'.$key2]))
						{
							$populated_value['element_'.$key.'_'.$key2]=$this->input['element_'.$key.'_'.$key2];
						}
						if ($c > 1) {
							$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
						} else {
							$f=$this->column[$key];
						}
						if (isset($populated_value['element_'.$key.'_'.$key2])) {
							$populated_column[$f]=$populated_value['element_'.$key.'_'.$key2];
						}
					}
				}
				$columnName=$this->column[$key];
				if ($columnName && !fwktecLicensedFor(array('module'=>'fwkfor','page'=>'form','action'=>'render','par1'=>$columnName))) continue;
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
				$element->columnName=$columnName;
				$element->parameters=$parameters ? $parameters : (isset($value['parameters']) ? $value['parameters'] : '');
				$element->column=$this->column;
				$element->prepare();
				$retDisplay=$element->display($mode);
				if (isset($retDisplay['jsrule']) && is_array($retDisplay['jsrule']) && count($retDisplay['jsrule']) > 0) {
					foreach ($retDisplay['jsrule'] as $rule) $jsRules[]=$rule;
				}
				if ($prefix) $element_markup.=str_replace('element_',$prefix.'_element_',$retDisplay['markup']);
				elseif (isset($retDisplay['markup'])) $element_markup.=$retDisplay['markup'];
				$element_markup.='</li>';
				if ($element->constraint=='system_divider') {
					$dividers[]=$element->divider;
				} else {
					$ret.=$element_markup;
				}
				$isFirst=false;
				$this->elements[$key]=$element->name;

			}
		}
		if ($ret) $ret.='</ul>';

		if (count($dividers) > 0) {
			$tabs='<ul>';
			foreach ($dividers as $id => $divider) {
				$tabs.='<li class="zfacestab"><a href="#zfaces'.$id.'">';
				$tabs.=$divider;
				$tabs.='</a></li>';
			}
			$tabs.='</ul>';
			$tabs.='<div class="zfclear"></div>';
			$js='<script type="text/javascript" language="javascript">';
			$js.='jQuery(document).ready(function() {';
			$js.="jQuery('#zfacestabs').tabs();";
			$js.="});";
			$js.="</script>";
		}
		$ret=$tabs.$ret;
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
				} else {
					$r['result']="'".$this->input['element_'.$data['field'].'_'.$data['subField']]."'";
				}
				if (version_compare (PHP_VERSION,'5.3.0') >= 0) $js_markup.="wsFormField.add(".json_encode($data,JSON_FORCE_OBJECT).",".$jsRule[1].",".$r['result'].");";
				else $js_markup.="wsFormField.add(".json_encode($data).",".$jsRule[1].",".$r['result'].");";
			}
			$js_markup.='});';
			$js_markup.='</script>';
		}
		$ret.=$js_markup;
		if ($display) echo $ret;
		return $ret;
	}

	function printout($mode="print",$prefix="",$display=false)
	{
		$jsRules=array();
		$ret='';
		$js='';
		$tabs='';
		$dividers=array();
		$numDiv=0;
		$js_markup='';

		$populated_value=array();
		$populated_column=array();
		if ($a=$this->json)
		{
			$isFirst=true;
			foreach ($a as $i => $value)
			{
				$parameters=array();
				$key=$value['id'];
				if ($value['type']=='submit') continue;
				$element=new element($value['type']);
				$element->isRepeatable=isset($value['attributes']['zfrepeatable']) ? $value['attributes']['zfrepeatable'] : '';
				$element->title=$value['label'];
				$element->id=$value['id'];
				$element->is_error=isset($this->elements['is_error'][$key]) ? $this->elements['is_error'][$key] : '';
				$element->error_message=isset($this->elements['error_message'][$key]) ? $this->elements['error_message'][$key] : '';
				$element->is_required=isset($value['mandatory']) ? $value['mandatory'] : '';
				$element->is_searchable=isset($value['searchable']) ? $value['searchable'] : '';
				$this->searchable=isset($value['searchable']) && $value['searchable'] ? true : false;
				$element->readonly=isset($value['readonly']) ? $value['readonly'] : '';
				if (isset($value['searchable']) && $value['searchable'] && $mode=="search") $element->readonly='';
				$element->hidden=isset($value['hidden']) ? $value['hidden'] : '';
				$element->attributes=$value['attributes'];
				$element->unique=isset($value['unique']) ? $value['unique'] : '';
				$element->linksin=isset($value['links']) ? $value['links'] : '';
				$element->rules=isset($this->elements['rules'][$key]) ? $this->elements['rules'][$key] : '';
				$element->showSubscript=true;
				$element->attributes=$value['attributes'];
				$element->displayGuidelines=false;

				$c=$this->countSubelements($value['subelements'],$key);

				$ca=0;
				if ($element->isRepeatable) {
					foreach ($value['subelements'] as $key2 => $sub) {
						$ca=max($ca,isset($this->input['element_'.$key.'_'.$key2]) ? count($this->input['element_'.$key.'_'.$key2]) : 0);
					}
				}

				foreach ($value['subelements'] as $key2 => $sub)
				{
					if (isset($sub['parameters'])) {
						$parameters[$key][$key2]=$sub['parameters'];
					}
					if (isset($this->elements['cat'][$key][$key2]) && $this->elements['cat'][$key][$key2]=='parameter') {
						$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
					}
					elseif (isset($sub['populate']) && empty($this->input))
					{
						$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
					}
					elseif (!empty($this->input) && isset($this->input['element_'.$key.'_'.$key2]))
					{
						$populated_value['element_'.$key.'_'.$key2]=$this->input['element_'.$key.'_'.$key2];
					}
					if ($c > 1) {
						$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
					} else {
						$f=$this->column[$key];
					}
					if (isset($populated_value['element_'.$key.'_'.$key2])) $populated_column[$f]=$populated_value['element_'.$key.'_'.$key2];
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
				$element->parameters=$parameters;
				$retDisplay=$element->display($mode);
				if (isset($retDisplay['jsrule']) && is_array($retDisplay['jsrule']) && count($retDisplay['jsrule']) > 0) {
					$jsRules[]=$retDisplay['jsrule'];
				}
				if ($prefix) $element_markup.=str_replace('element_',$prefix.'_element_',$retDisplay['markup']);
				elseif (isset($retDisplay['markup'])) $element_markup.=$retDisplay['markup'];
				$element_markup.='</li>';
				if ($element->constraint=='system_divider') {
					$dividers[]=$element->divider;
				} else {
					$ret.=$element_markup;
				}
				$isFirst=false;
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
			$tabs.='</ul>';
			$tabs.='<div class="zfclear"></div>';
			$js='<script type="text/javascript" language="javascript">';
			$js.='jQuery(document).ready(function() {';
			$js.="jQuery('#zfacestabs').tabs();";
			$js.="});";
			$js.="</script>";
		}
		$ret=$tabs.$ret;
		$ret='<div id="zfacestabs">'.$ret.'</div>'.$js;
		if ($display) echo $ret;
		return $ret;
	}

	function Verify($input,$id=0)
	{
		if ($id) { //get image of record before update
			$query="select * from `".DB_PREFIX.$this->entity."` where `ID`=".qs($id);
			$db=new aphpsDb();
			$db->select($query);
			$this->before=$db->next();
		}
		$success=true;
		$this->input=$this->sanitize($input);
		$parameters=array();
		foreach ($this->json as $key => $value)
		{
			$element=new element($value['type']);
			$element->id=$key;
			$element->is_required=isset($value['mandatory']) ? $value['mandatory'] : '';
			$element->is_searchable=isset($value['searchable']) ? $value['searchable'] : '';
			$element->readonly=isset($value['readonly']) ? $value['readonly'] : '';
			$element->hidden=isset($value['hidden']) ? $value['hidden'] : '';
			$element->unique=isset($value['unique']) ? $value['unique'] : '';
			$element->rules=isset($this->elements['rules'][$key]) ? $this->elements['rules'][$key] : '';
			$element->attributes=$value['attributes'];

			$c=$this->countSubelements($value['subelements'],$key);
			foreach ($value['subelements'] as $key2 => $sub)
			{
				if (isset($sub['parameters'])) {
					$parameters[$key][$key2]=$sub['parameters'];
				}

				if (isset($this->elements['cat'][$key][$key2]) && $this->elements['cat'][$key][$key2]=='parameter') {
					$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
				}
				elseif (isset($sub['populate']) && empty($this->input))
				{
					$populated_value['element_'.$key.'_'.$key2]=$sub['populate'];
				}
				elseif (!empty($this->input))
				{
					$populated_value['element_'.$key.'_'.$key2]=isset($this->input['element_'.$key.'_'.$key2]) ? $this->input['element_'.$key.'_'.$key2] : '';
				}
				if ($c > 1) {
					$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
				} else {
					$f=$this->column[$key];
				}
				$populated_column[$f]=isset($this->input['element_'.$key.'_'.$key2]) ? $this->input['element_'.$key.'_'.$key2] : '';
				$column_map['element_'.$key.'_'.$key2]=$f;
			}
			$element->entityName=$this->entity;
			$element->entityType=$this->type;
			$element->column_map=$column_map;
			$element->populated_value=$populated_value;
			$element->populated_column=$populated_column;
			$element->parameters=$parameters;
			$sv=$element->Verify($this->input,$this->output,$this->action,$this->before);
			$success=$success && $sv;
			$this->elements['name'][$key]=$element->name;
			$this->elements['is_error'][$key]=$element->is_error;
			$this->elements['error_message'][$key]=$element->error_message;
			$this->elements['format'][$key]=$element->format;
			$this->data=isset($this->populated_column) ? $this->populated_column : null;
			if (count($element->onSubmitActions) > 0) {
				foreach ($element->onSubmitActions as $onSubmitAction) {
					$this->onSubmitActions[]=$onSubmitAction;
				}
			}
		}
		return $success;
	}

	function alert($message)
	{
		if (!$this->noAlert) echo '<div class="zfalert">'.$message.'</div>';
	}

	function Delete($id)
	{
		//get image of record before update
		$query="select * from `".DB_PREFIX.$this->entity."` where `ID`=".qs($id);
		$db=new aphpsDb();
		$db->select($query);
		$this->before=$db->next();

		$success=true;
		if ($this->type=="DB") $success=$this->DeleteDB($id);
		$this->postDelete();
		$this->alert("Record delete successful!");
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
		$success=$this->postSave($success);
		if (($this->formAttributes->sendemail && $this->formAttributes->emailfield) || $this->formAttributes->adminemail) {
			$emailAddress=$this->output['element_'.$this->formAttributes->emailfield.'_1'];
			$this->mailTo($emailAddress);
		}
		if (count($this->onSubmitActions) > 0) {
			foreach ($this->onSubmitActions as $onSubmitAction) {
				if ($onSubmitAction['action']=='mailto') $this->mailTo($onSubmitAction['to'],true);
			}
		}
		$this->updateWorkflow();
		if (isset($this->formAttributes->confirmation) && $this->formAttributes->confirmation) $this->alert($this->formAttributes->confirmation);
		else $this->alert("Save successful!");
		return $success;
	}

	function updateWorkflow() {
		if (!isset($_SESSION['aphps']['workflow']['event']) || ($_SESSION['aphps']['workflow']['event']!='captureform')) return;
		$wf=new fwkevt_workflow();
		$wf->updateSession($wf->step+1,array_merge($this->rec,array('ID'=>$this->recid)),$this->form);
	}

	function mailTo($to,$legacy=false) {
		global $aphps_projects;

		if (!defined('APHPS_ADMIN_EMAIL')) return false;

		$email=new fwktec_email();
		$email->addFrom(APHPS_ADMIN_EMAIL);
		$email->setSubject($this->label.' submitted');
		if ($legacy) {
			$message=$this->printout();
		} else {
			$message='';
			if ($this->formAttributes->emailconfirmation) $message.=$this->formAttributes->emailconfirmation;
			if ($this->formAttributes->emailformtouser) {
				if ($message) $message.='<br /><br />';
				$message.=$this->printout();
			}
		}

		$email->setBody($message);

		//pdf
		if (APHPS_DEV) {
			echo 'Processing template Test1';
			if ($template=new devtpl_template('Test1','en_GB')) {
				foreach ($this->rec as $f => $v) {
					$template->replace($f,$v);
				}
				$template->replace();
				$output=$template->generatePdfData();
				$email->addData('form.pdf',$output);
			}
		}

		if ($to && $this->formAttributes->adminemail && !$legacy) { //to user and admin
			$email->addTo($to);
			$email->addBcc($this->formAttributes->adminemail);
		} elseif ($to) { //to user only
			$email->addTo($to);
		} elseif ($this->formAttributes->adminemail && !$legacy) { //to admin only
			$email->addTo($this->formAttributes->adminemail);
		}
		if ($to || ($this->formAttributes->adminemail && !$legacy)) { //to user or admin
			$email->send();
		}
		return true;
	}

	function postSave() {
		return true;
	}

	function makeRow($id) {
		$row=array();
		$grid=array();
		foreach ($this->json as $key => $value)
		{
			if ((!isset($value['attributes']['zfrepeatable']) || !$value['attributes']['zfrepeatable']) && (!isset($value['attributes']['zfmeta']) || !$value['attributes']['zfmeta'])) {
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

	function preSaveDB() {
		return true;
	}

	function SaveDB($id=0)
	{
		$db=new aphpsDb();

		$this->makeRow($id);
		$this->preSaveDB();
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
			$element->is_required=isset($value['mandatory']) ? $value['mandatory'] : '';
			$element->is_searchable=isset($value['searchable']) ? $value['searchable'] : '';
			$element->readonly=isset($value['readonly']) ? $value['readonly'] : '';
			$element->hidden=isset($value['hidden']) ? $value['hidden'] : '';
			$element->unique=isset($value['unique']) ? $value['unique'] : '';
			$element->rules=isset($this->elements['rules'][$key]) ? $this->elements['rules'][$key] : '';

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
					$populated_value['element_'.$key.'_'.$key2]=isset($this->input['element_'.$key.'_'.$key2]) ? $this->input['element_'.$key.'_'.$key2] : '';
				}
				if ($c > 1) {
					$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
				} else {
					$f=$this->column[$key];
				}
				$populated_column[$f]=isset($this->input['element_'.$key.'_'.$key2]) ? $this->input['element_'.$key.'_'.$key2] : '';
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
			if (!strstr($id,'_')) continue;
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
				if (is_array($value)) {
					$qwhere.=empty($qwhere) ? " where " :  " and ";
					if ($value[0]=='like') $qwhere.="`".$field."` LIKE '%".$value[1]."%'";
					else $qwhere.="`".$field."`=".qs($value[1]);
				} else {
					$v=false;
					if (function_exists($value)) $v=$value();
					elseif (isset($_GET[$value])) $v=$_GET[$value];
					elseif (isset($_POST[$value])) $v=$_POST[$value];
					else $v=$value;
					if ($v !== false) {
						$qwhere.=empty($qwhere) ? " where " :  " and ";
						$qwhere.="`".$field."`=".qs($v);
					}
				}
			}
			$this->query.=$qwhere;
		}
		$this->query.=" ORDER BY ".$this->orderKeys;
		$this->db=new aphpsDb();
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
			$input=array();
			foreach ($this->fields as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				if (($this->page=='list') && ($this->json[$key1]['type']=='submit')) continue;
				$input['element_'.$key1."_".$key2]=$r[str_replace('`','',$column)];
			}
			foreach ($this->json as $key1 => $sub) {
				if (($this->page=='list') && ($this->json[$key1]['type']=='submit')) continue;
				if (count($sub['subelements']) > 0) {
					foreach ($sub['subelements'] as $key2 => $data) {
						if (!isset($input['element_'.$key1."_".$key2])) $input['element_'.$key1."_".$key2]=isset($data['populate']) ? $data['populate'] : '';
					}
				}
			}
			$output=$this->output($input,"list");
			$o=array();
			foreach ($this->fields as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				if (($this->page=='list') && ($this->json[$key1]['type']=='submit')) continue;
				$o[str_replace('`','',$column)]=$output['element_'.$key1."_".$key2];
			}
			$rows[$id]=$o;

		}
		return $rows;
	}

	function export($format='csv') {
		global $aphpsData;

		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header('Content-Disposition: attachment; filename="'.preg_replace("/[^A-Za-z0-9_]/", '', $this->form).'_'.(date('YmdHis')).'.csv"');
		$this->SelectRows();
		$rows=array();

		ob_start();
		$outstream = fopen("php://output",'w');

		//header
		foreach ($this->fields as $key => $column)
		{
			zfKeys($key,$key1,$key2);
			if (($this->page=='list') && ($this->json[$key1]['type']=='submit')) continue;
			$row[]=str_replace('`','',$column);
		}
		if ($format=='csv') fputcsv($outstream, $row, ',', '"');

		//data
		while ($r=$this->db->next())
		{
			$row=array();
			$id=$r['ID'];
			$input=array();
			foreach ($this->fields as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				if (($this->page=='list') && ($this->json[$key1]['type']=='submit')) continue;
				$input['element_'.$key1."_".$key2]=$r[str_replace('`','',$column)];
			}
			foreach ($this->json as $key1 => $sub) {
				if (($this->page=='list') && ($this->json[$key1]['type']=='submit')) continue;
				if (count($sub['subelements']) > 0) {
					foreach ($sub['subelements'] as $key2 => $data) {
						if (!isset($input['element_'.$key1."_".$key2])) $input['element_'.$key1."_".$key2]=isset($data['populate']) ? $data['populate'] : '';
					}
				}
			}
			$output=$this->output($input,"list");
			$o=array();
			foreach ($this->fields as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				if (($this->page=='list') && ($this->json[$key1]['type']=='submit')) continue;
				$row[]= $output['element_'.$key1."_".$key2];
			}
			if ($format=='csv') fputcsv($outstream, $row, ',', '"');
		}
		fclose($outstream);
		$aphpsData=ob_get_clean();
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
			foreach ($value['subelements'] as $key2 => $sub) {
				if (isset($sub['parameters'])) {
					$element->parameters[$key][$key2]=$sub['parameters'];
				}
			}
			if (!$element->parameters && isset($value['parameters'])) $element->parameters=$value['parameters'];
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
				if (isset($_POST['element_'.$key1."_".$key2]) && $_POST['element_'.$key1."_".$key2]) {
					$input['element_'.$key1."_".$key2]=$_POST['element_'.$key1."_".$key2];
				} elseif (isset($_POST['element_'.$key1]) && $_POST['element_'.$key1]) {
					$input['element_'.$key1]=$_POST['element_'.$key1];
				} elseif ((isset($this->map['element_'.$key1."_".$key2]) && ($f=$this->map['element_'.$key1."_".$key2]) && ($value=isset($this->post[$f]) ? $this->post[$f] : '')) || (isset($this->map['element_'.$key1]) && ($f=$this->map['element_'.$key1]) && ($value=isset($this->post[$f]) ? $this->post[$f] : ''))) {
					if (function_exists($value)) $v=$value();
					elseif (isset($_GET[$value])) $v=$_GET[$value];
					elseif (isset($_POST[$value])) $v=$_POST[$value];
					else $v=$value;
					$input['element_'.$key1."_".$key2]=$v;
				} elseif (isset($_GET['element_'.$key1."_".$key2]) && $_GET['element_'.$key1."_".$key2]) {
					$input['element_'.$key1."_".$key2]=$_GET['element_'.$key1."_".$key2];
				} elseif (isset($_GET['element_'.$key1]) && $_GET['element_'.$key1]) {
					$input['element_'.$key1]=$_GET['element_'.$key1];
				}
			}
		} else {
			$this->query="select * from `".DB_PREFIX.$this->entity."` where `ID`=".qs($id);
			if (is_array($this->post) && count($this->post)) {
				foreach ($this->post as $f => $value) {
					if (function_exists($value)) $v=$value();
					elseif (isset($_GET[$value])) $v=$_GET[$value];
					elseif (isset($_POST[$value])) $v=$_POST[$value];
					else $v=$value;
					$this->query.=' AND '.$f.'='.qs($v);
				}
			}
			$this->db=new aphpsDb();
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
			$db=new aphpsDb();
			foreach ($this->allFieldAttributes as $key => $column)
			{
				zfKeys($key,$key1,$key2);
				$query="select * from `".DB_PREFIX.$this->entity."_attributes` where `PARENTID`=".qs($id)." AND `NAME`=".qs(str_replace('`','',$column))." ORDER BY `SET`";
				$db->select($query);
				while ($r=$db->next()) {
					foreach ($r as $field => $value) {
						$r[strtoupper($field)]=$value;
					}
					$input['element_'.$key1."_".$key2][]=$r['VALUE'];
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

	function allowAccess($action='') {
		if (!$action) $action=$this->action;
		$access=new zfAccess($this->id,$this->page,$action,$this->filter,$this->data);
		$allowed=$access->allowed();
		if ($allowed) return true;
		else {
			$this->errorMessage="Access denied";
			return false;
		}
	}

	function recValue($field) {
		$ret=$this->rec[strtoupper($field)] ? $this->rec[strtoupper($field)] : $this->rec[strtolower($field)];
		return $ret;
	}

}


