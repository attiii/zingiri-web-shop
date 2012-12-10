<?php
class dividerZfSubElement extends zfSubElement {
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		
		$this->divider=$e->values['element_'.$e->id.'_'.$i][$this->ai];
		
		if($e->values['element_'.$e->id.'_'.$i][$this->ai] == ""){
			$e->values['element_'.$e->id.'_'.$i][$this->ai] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->values['element_'.$e->id.'_'.$i][$this->ai]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
		
	}

}
