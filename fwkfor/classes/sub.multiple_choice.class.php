<?php
class multiple_choiceZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$e=$this->element;
		$i=$this->subid;
		$this->ext=$this->int;

		if (isset($e->parameters[$e->id][1]) && is_array($e->parameters[$e->id][1])) {
			foreach ($e->parameters[$e->id][1] as $pair) {
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

		if (isset($e->populated_value['element_'.$e->id.'_2'])) $keypairs=explode(",",trim($e->populated_value['element_'.$e->id.'_2']));
		else $keypairs=array();

		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}

		if (in_array($this->mode,array('build','edit'))) {
			$field_markup.="<div id=\"element_{$e->id}_{$i}\" >";
			if (is_array($e->parameters[$e->id][1])) {
				foreach ($e->parameters[$e->id][1] as $j => $option) {
					$k=$j+1;
					if ($e->populated_value['element_'.$e->id.'_'.$i]==$option['value']) $checked='checked="checked"';
					else $checked='';
					$field_markup.="<div class=\"zfsuboption\" id=\"option_{$e->id}_{$i}_{$k}\" >";
					$field_markup.="<input value=\"".$option['value']."\" type=\"radio\" id=\"element_{$e->id}_{$i}_{$k}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly} {$checked}/>";
					$field_markup.="<label id=\"label_{$e->id}_{$i}_{$jk}\" for=\"element_{$e->id}_{$i}_{$jk}\">".$option['label']."</label>";
					$field_markup.="</div>";
				}
			}
			$field_markup.="</div>";
		} elseif (in_array($this->mode,array('view','delete'))) {
			if (isset($e->parameters[$e->id][1]) && is_array($e->parameters[$e->id][1])) {
				foreach ($e->parameters[$e->id][1] as $pair) {
					if ($e->populated_value['element_'.$e->id.'_'.$i] == $pair['value']) $field_markup.=$pair['label'];
				}
			}
		}
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}