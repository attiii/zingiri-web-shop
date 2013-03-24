<?php
class dropdownZfSubElement extends zfSubElement {


	function output($mode="edit",$input="")
	{
		$e=$this->element;
		$i=$this->subid;
		$this->ext=$this->int;

		if (isset($e->parameters[$e->id][$i]) && is_array($e->parameters[$e->id][$i])) {
			foreach ($e->parameters[$e->id][$i] as $pair) {
				if(trim($this->int) == trim($pair['value'])) {
					$this->ext=$pair['label'];
				}
			}
		} else $this->ext='';
		return $this->ext;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		if (in_array($this->mode,array('build','edit','add'))) {
			$field_markup.="<select id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly} >";
			if (is_array($e->parameters[$e->id][$i])) {
				foreach ($e->parameters[$e->id][$i] as $j => $option) {
					$k=$j+1;
					if (trim($e->populated_value['element_'.$e->id.'_'.$i])==trim($option['value'])) $selected='selected="selected"';
					else $selected='';
					$field_markup.="<option value=\"".$option['value']."\" id=\"option_{$e->id}_{$i}_{$k}\" class=\"zfsuboption\" {$selected}>";
					$field_markup.=$option['label'];
					$field_markup.="</option>";
				}
			} else {
				$k=1;
				$field_markup.="<option value=\"\" id=\"option_{$e->id}_{$i}_{$k}\" class=\"zfsuboption\" {$e->readonly}></option>";
			}
			$field_markup.='</select>';
		} elseif (in_array($this->mode,array('view','delete','print'))) {
			if (isset($e->parameters[$e->id][$i]) && is_array($e->parameters[$e->id][$i])) {
				foreach ($e->parameters[$e->id][$i] as $pair) {
					if ($e->populated_value['element_'.$e->id.'_'.$i] == $pair['value']) $field_markup.=$pair['label'];
				}
			}
		}
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}