<?php
class multicheckboxZfSubElement extends zfSubElement {
	var $isDataSet=true;

	function output($mode="edit",$input="")
	{
		$e=$this->element;
		$i=$this->subid;
		$data=unserialize($this->int);
		$this->ext=implode(',',$data);
		/*
		 if (isset($e->parameters[$e->id][$i]) && is_array($e->parameters[$e->id][$i])) {
			foreach ($e->parameters[$e->id][$i] as $pair) {
			if(trim($this->int) == trim($pair['value'])) {
			$this->ext=$pair['label'];
			}
			}
			} else $this->ext='';
			*/
		return $this->ext;
	}

	function verify()
	{
		$e=$this->element;
		$i=$this->subid;
		//print_r($e->parameters[$e->id][$i]);
		$this->int=serialize($this->ext);
		//		$this->ext=$this->int;
		return true;
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
		if (in_array($this->mode,array('build','edit','add'))) {
			$first=true;
			$field_markup.="<div id=\"element_{$e->id}_{$i}\" >";
			if (is_array($e->parameters[$e->id][$i])) {
				foreach ($e->parameters[$e->id][$i] as $j => $option) {
					$k=$j+1;
					if ($e->populated_value['element_'.$e->id.'_'.$i] && ($e->populated_value['element_'.$e->id.'_'.$i]==$option['value'])) $checked='checked="checked"';
					//elseif (!$e->populated_value['element_'.$e->id.'_'.$i] && $first) $checked='checked="checked"';
					else $checked='';
					$first=false;
					$field_markup.="<div class=\"zfsuboption\" id=\"option_{$e->id}_{$i}_{$k}\" >";
					$field_markup.="<input value=\"".$option['value']."\" type=\"checkbox\" id=\"element_{$e->id}_{$i}_{$k}\" name=\"element_{$e->id}_{$i}[]\" class=\"element text\" {$e->readonly} {$checked}/>";
					$field_markup.="&nbsp;<label id=\"label_{$e->id}_{$i}_{$k}\" for=\"element_{$e->id}_{$i}_{$k}\">".$option['label']."</label>";
					$field_markup.="</div>";
				}
			} else {
				$k=1;
				$field_markup.="<div class=\"zfsuboption\" id=\"option_{$e->id}_{$i}_{$k}\" >";
				$field_markup.="<input value=\"\" type=\"checkbox\" id=\"element_{$e->id}_{$i}_{$k}\" name=\"element_{$e->id}_{$i}[]\" class=\"element text\" {$e->readonly} {$checked}/>";
				$field_markup.="<label id=\"label_{$e->id}_{$i}_{$k}\" for=\"element_{$e->id}_{$i}_{$k}\"></label>";
				$field_markup.="</div>";
			}
			$field_markup.="</div>";
		} elseif (in_array($this->mode,array('view','delete'))) {
			if (isset($e->parameters[$e->id][$i]) && is_array($e->parameters[$e->id][$i])) {
				foreach ($e->parameters[$e->id][$i] as $pair) {
					if ($e->populated_value['element_'.$e->id.'_'.$i] == $pair['value']) $field_markup.=$pair['label'];
				}
			}
		}
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}