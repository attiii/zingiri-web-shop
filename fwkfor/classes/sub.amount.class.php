<?php
class amountZfSubElement extends zfSubElement {

	function output($mode="edit",$input="") {
		if (function_exists('myNumberFormat')) $this->ext=myNumberFormat($this->int*1);
		else $this->ext=number_format($this->int*1,2);
		return $this->ext;
	}

	function verify()
	{
		if (empty($this->ext)) { $this->ext=0; }
		if (function_exists('myNumberFormat')) {
			if (myNumberFormat($this->ext)===false) {
				return ($this->error("Amount format incorrect1!"));
			}
			if (function_exists('myStringToNumber')) $this->int=myStringToNumber($this->ext);
			else $this->int=myNumberFormat($this->ext);
		} else {
			if (!is_numeric($this->ext)) {
				return ($this->error("Amount format incorrect!"));
			}
			$this->int=$this->ext;
		}
		return true;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		if($e->values['element_'.$e->id.'_'.$i][$this->ai] == ""){
			$e->values['element_'.$e->id.'_'.$i][$this->ai] = $xmlf->fields->{'field'.$i}->default;
		}
		$readonly=isset($e->readonly) ? $e->readonly : '';

		if (is_array($e->values['element_'.$e->id.'_'.$i])) {
			if (function_exists('myNumberFormat')) $value=myNumberFormat($e->values['element_'.$e->id.'_'.$i][$this->ai]*1);
			else $value=number_format($e->values['element_'.$e->id.'_'.$i][$this->ai]*1,2);
		} else {
			if (function_exists('myNumberFormat')) $value=myNumberFormat($e->values['element_'.$e->id.'_'.$i]*1);
			else $value=number_format($e->values['element_'.$e->id.'_'.$i]*1,2);
		}
		$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element amount\" size=\"{$this->size}\" value=\"$value\" maxlength=\"{$this->maxlength}\" type=\"text\" {$readonly}/>";
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}

}
?>