<?php
class projectZfSubElement extends zfSubElement {
	function output($mode="edit",$input="")
	{
		global $aphps_projects;
		if (isset($aphps_projects[$this->int])) $this->ext=$aphps_projects[$this->int]['label'];
		else $this->ext=$this->int;
		return $this->ext;
	}
	
	function display(&$field_markup,&$subscript_markup) {
		global $aphps_projects;
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		$field_markup.="<select id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly}>";
		$option_markup="";
		foreach ($aphps_projects as $id => $project) {
			if ($project['label']) {
				$key=$id;
				$option=$project['label'];
				$selected="";
				if(trim($e->values['element_'.$e->id.'_'.$i][$this->ai]) == $key){
					$selected = 'selected="selected"';
				}
				if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$key."\" {$selected}>".$option."</option>";
			}
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}