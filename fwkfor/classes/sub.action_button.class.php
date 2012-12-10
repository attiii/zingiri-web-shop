<?php
class action_buttonZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$e=$this->element;
		$i=$this->subid;
		$this->ext='<form action="'.$this->int.'" method="post">';
		$this->ext.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" value=\"".z_('Go')."\" type=\"submit\"/>";
		$this->ext.='</form>';
		return $this->ext;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		
		if($e->values['element_'.$e->id.'_'.$i][$this->ai] == ""){
			$e->values['element_'.$e->id.'_'.$i][$this->ai] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->values['element_'.$e->id.'_'.$i][$this->ai]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}

}
