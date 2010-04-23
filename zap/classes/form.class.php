<?php
/*  form.class.php
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

	function zfForm($form,$id=0,$post=null) {
		$this->form=$form;
		$table=new zfDB();
		if ($form) $query="select * from `".DB_PREFIX."faces` WHERE `NAME`=".zfqs($form);
		else $query="select * from `".DB_PREFIX."faces` WHERE `ID`=".zfqs($id);
		$table->select($query);
		if ($row=$table->next())
		{
			$linksin=new zfDB();
			$query="select * from ##flink where formin='*' and displayout='form' and formout='".$row['ID']."' and mapping <> ''";
			$linksin->select($query);
			while ($l=$linksin->next()) {
				$s=explode(",",$l['MAPPING']);
				foreach ($s as $m) {
					$f=explode(":",$m);
					$post[$f[0]]=$f[1];
				}
			}

			$this->form=$row['NAME'];
			$this->label=$row['LABEL'];
			$this->json=zf_json_decode($row['DATA'],true); //form data
			$this->elementcount=$row['ELEMENTCOUNT'];
			$this->type=$row['TYPE'];
			$this->entity=$row['ENTITY'];
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

			$this->id=$row['ID'];
			$this->post=$post;
		}
		else
		{
			$this->json=false;
			$this->error=true;
		}

	}

	function Headers($all=false)
	{
		$h=array(); //unsorted headers
		$c=array(); //map element to field name
		$f=array(); //unsorted fields
		$s=array(); //sort order for headers
		$g=array(); //sorted headers
		$e=array(); //sorted fields


		foreach ($this->json as $i => $value)
		{
			$key1=$value['id'];
			foreach ($value['subelements'] as $key2 => $value2)
			{
				if ($this->elements['format'][$key1][$key2] != 'none') {
					if ($all || !$value2['hide']) {
						if (!isset($value2['sortorder'])) $value2['sortorder']=1;
						$s[$key1*100+$key2]=$value2['sortorder'];
					}
				}
			}
		}
		asort($s);

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
									$h[$key1*100+$key2]=$tempfunc($value['label']).' '.$tempfunc($this->elements['label'][$key1][$key2]);
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

		if ($all) $this->map=$c;
		if ($all) $this->allfields=$e; else $this->fields=$e;

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
		$populated_value=array();
		$populated_column=array();
		$ret="";
		if ($a=$this->json)
		{
			foreach ($a as $i => $value)
			{
				$key=$value['id'];
				$element=new element($value['type']);
				$element->title=$value['label'];
				//$element->id=$key;
				$element->id=$value['id'];
				$element->is_error=$this->elements['is_error'][$key];
				$element->error_message=$this->elements['error_message'][$key];
				$element->is_required=$value['mandatory'];
				$element->is_searchable=$value['searchable'];
				if ($value['searchable']) $this->searchable=true;
				$element->readonly=$value['readonly'];
				$element->hidden=$value['hidden'];
				$element->unique=$value['unique'];
				$element->linksin=$value['links'];
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
					$populated_column[$f]=$populated_value['element_'.$key.'_'.$key2];
				}
				$element->populated_value=$populated_value;
				$element->populated_column=$populated_column;
				$ret.='<li class="zfli" style="background-image:none;">';
				//$ret.=display_bddress($element);
				$element->column=$this->column;
				$element->prepare();
				if ($prefix) $ret.=str_replace('element_',$prefix.'_element_',$element->display($mode));
				else $ret.=$element->display($mode);
				$ret.='</li>';

				$this->elements[$key]=$element->name;

			}
		}
		echo $ret;
		return $ret;
	}

	function Verify($input)
	{
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
				if ($c > 1) {
					$f=strtoupper($this->column[$key]."_".$element->xmlf->fields->{'field'.$key2}->name);
				} else {
					$f=$this->column[$key];
				}
				$populated_column[$f]=$this->input['element_'.$key.'_'.$key2];
			}
			$element->populated_column=$populated_column;

			$sv=$element->Verify($this->input,$this->output);
			$success=$success && $sv;
			$this->elements['name'][$key]=$element->name;
			$this->elements['is_error'][$key]=$element->is_error;
			$this->elements['error_message'][$key]=$element->error_message;
			$this->elements['format'][$key]=$element->format;
		}
		return $success;
	}

	function alert($message)
	{
		echo '<div class="zfalert">'.$message.'</div>';
	}

	function Delete($id)
	{
		$success=true;
		if ($this->type=="DB") $success=$this->DeleteDB($id);
		$this->alert("Record delete successfull!");
		return $success;
	}

	function DeleteDB($id)
	{
		$keys=array();
		$keys['ID']=$id;
		DeleteRecord($this->entity,$keys,"");
	}


	function Save($id=0)
	{
		$success=true;
		if ($this->type=="DB") $success=$this->SaveDB($id);
		$this->alert("Save successfull!");
		return $success;
	}

	function SaveDB($id=0)
	{
		foreach ($this->json as $key => $value)
		{

			foreach ($value['subelements'] as $key2 => $sub)
			{
				if ($this->elements['format'][$key][$key2] != "none") {
					if (isset($this->elements['name'][$key][$key2]) && !empty($this->elements['name'][$key][$key2])) $f=$value['column']."_".$this->elements['name'][$key][$key2];
					else $f=$value['column'];
					$v=$this->output['element_'.$key.'_'.$key2];
					$row[$f]=$v;
				}
			}
		}
		$keys=array();
		if ($id)
		{
			$keys['ID']=$id;
			$row['DATE_UPDATED']=date("Y-m-d H:i:s");
			UpdateRecord($this->entity,$keys,$row,"");

		} else {
			$row['DATE_CREATED']=date("Y-m-d H:i:s");
			InsertRecord($this->entity,$keys,$row,"");
		}
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
					$input_array[$key] = stripslashes($input_array[$key]);
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
			//echo '<br />';print_r($this->json[$key1]['mandatory']);
			if ($value!="" && $prefix=='element') {
				$field=str_replace('`','',$this->allfields[$key]);
				$map[$field]=array('like', $value);
				//$map[$id]=$value;
				$s.='&search['.$id.']='.urlencode($value);
			}
			//echo '<br />'.$id.'='.$value.'='.$this->allfields[$key];
		}
		//echo '<br />';print_r($map);echo '<br />';
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
		$this->query.=" ORDER BY `ID`";
		$this->db=new zfDB();
		$this->rowsCount=$this->db->select($this->query);

		$this->query.=' LIMIT '.$pos.','.ZING_APPS_MAX_ROWS;

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
			$this->db=new zfDB();
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
			} else { return false; }
		}
		$this->input=$this->sanitize($input);
		$this->output=$this->output($this->input);
		return true;
	}

	function DeleteMe()
	{
		$keys=array();
		$keys['NAME']=$this->form;
		DeleteRecord("faces",$keys,"");
		return true;
	}

}
?>