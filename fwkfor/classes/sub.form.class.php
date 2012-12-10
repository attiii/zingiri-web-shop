<?php
class formZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$query=$this->xmlf->values->query;
		$query=str_replace("##",DB_PREFIX,$query);

		if (!empty($this->xmlf->values->where)) {
			//FIXME: check this
			$wherefields=explode(",",$this->xmlf->values->where);
			foreach ($wherefields as $wherefield) {
				$query=str_replace("$".$wherefield,0,$query);
			}
		}
		$result = do_query($query);
		while($row = mysql_fetch_array($result)){
			$key=$row[0];
			$option=$row[1];
			//FIXME: check this
			if ($key==$this->int) $this->ext=$option;
			//if ($key==$this->int) $this->ext=$key;
		}
		return $this->ext;
			
	}

	function display(&$field_markup,&$subscript_markup) {
		global $aphps_projects;
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		//echo '<pre>'.print_r($this->element,true).'</pre>';
		
		$hideSystem=isset($e->populated_value['element_'.$e->id.'_2']) ? $e->populated_value['element_'.$e->id.'_2'] : false;
		$hideWildcard=isset($e->populated_value['element_'.$e->id.'_3']) ? $e->populated_value['element_'.$e->id.'_3'] : false;
		$formType=isset($e->populated_value['element_'.$e->id.'_4']) ? $e->populated_value['element_'.$e->id.'_4'] : false;
		$query="select id,`label`,`project` from ##faces ";
		if ($formType) $query.=" where `type`=".qs($formType)." ";
		$query.="order by `project`,`label`";
		$query=str_replace("##",DB_PREFIX,$query);
		//echo $query;
		$field_markup.="<select id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly}>";
		$option_markup="";
		if (!$e->is_required) $option_markup .= "<option value=\"\"></option>";
		if (!$hideWildcard) $option_markup .= "<option value=\"0\">*</option>";
		$result = do_query($query);
		while($row = mysql_fetch_array($result)){
			if ($hideSystem && isset($aphps_projects[$row['project']]['system']) && $aphps_projects[$row['project']]['system']) continue;
			$key=$row[0];
			$option=$row[1];
			$selected="";
			if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == $key){
				$selected = 'selected="selected"';
			} elseif ($e->default_value == $key) {
				$selected = 'selected="selected"';
			}
			if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$key."\" {$selected}>".$option."</option>";
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";


		}
	}