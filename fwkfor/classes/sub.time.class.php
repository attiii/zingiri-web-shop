<?php
class timeZfSubElement extends zfSubElement {

	function display(&$field_markup,&$subscript_markup) {
		global $timepickerLoaded;
		
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$element='element_'.$e->id.'_'.$i;

		if($e->populated_value['element_'.$e->id.'_'.$i][$this->ai] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i][$this->ai] = trim($xmlf->fields->{'field'.$i}->default);
		}
		if ($e->populated_value['element_'.$e->id.'_'.$i][$this->ai]=='0000-00-00') $e->populated_value['element_'.$e->id.'_'.$i]='';
		$js="jQuery('.timepicker').timepicker();";
		$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" data-js=\"$js\" class=\"element text timepicker\" size=\"{$this->size}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i][$this->ai]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		if ($this->mode=='add' || $this->mode=='edit') {
			if (!$timepickerLoaded) { 
				$field_markup.='<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/' . APHPS_JSDIR . '/jquery.ui.timepicker.js"></script>';
				$field_markup.='<link rel="stylesheet" type="text/css" media="all" href="' . ZING_APPS_PLAYER_URL . 'css/jquery.ui.timepicker.css" />';
			}
			$timepickerLoaded=true;
			$field_markup.='<script type="text/javascript" language="javascript">';
			$field_markup.='jQuery(document).ready(function() {';
			$field_markup.='jQuery(".timepicker").timepicker();';
			$field_markup.='})';
			$field_markup.='</script>';
		}
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}