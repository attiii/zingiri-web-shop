<?php
class date2ZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$e=$this->element;
		$dateFormat=isset($e->populated_value['element_'.$e->id.'_2']) ? $e->populated_value['element_'.$e->id.'_2'] : 'mm/dd/yy';
		//print_r($e);
		if ($this->int!='' && $this->int!='0000-00-00') $this->ext=date($dateFormat=='mm/dd/yy' ? 'm/d/Y' : 'd-m-Y',strtotime($this->int));
		else $this->ext='';
		
		return $this->ext;
	}

	function verify()
	{
		$e=$this->element;
		$dateFormat='dd-mm-yy';

		$success=true;
		if ($this->ext!='' && !strtotime($this->ext))
		{
			$success=false;
			$this->error_message=z_("Wrong date format!");
			$this->is_error=true;
		} else {
			if ($this->ext!='') {
				$this->int=date("Y-m-d",strtotime($this->ext));
				$this->ext=date($dateFormat=='mm/dd/yy' ? 'm/d/Y' : 'd-m-Y',strtotime($this->ext));
			} else {
				$this->int='';
			}
		}
		return $success;
	}

	function display(&$field_markup,&$subscript_markup) {
		global $aphps;
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$dateFormat=$aphps->jsDateFormat;
		$element='element_'.$e->id.'_'.$i;

		$pop=is_array($e->populated_value['element_'.$e->id.'_'.$i]) ? $e->populated_value['element_'.$e->id.'_'.$i][$this->ai] : $e->populated_value['element_'.$e->id.'_'.$i];
		trigger_error($pop);
		if (empty($e->values['element_'.$e->id.'_'.$i][$this->ai])) {
			if ($pop == "") $e->values['element_'.$e->id.'_'.$i][$this->ai] = trim($xmlf->fields->{'field'.$i}->default);
			elseif ($pop=='0000-00-00') $e->values['element_'.$e->id.'_'.$i][$this->ai]='';
			elseif ($pop) $e->values['element_'.$e->id.'_'.$i][$this->ai]=date($dateFormat=='mm/dd/yy' ? 'm/d/Y' : 'd-m-Y',strtotime($e->populated_value['element_'.$e->id.'_'.$i]));
		} else $e->values['element_'.$e->id.'_'.$i][$this->ai]=date($dateFormat=='mm/dd/yy' ? 'm/d/Y' : 'd-m-Y',strtotime($e->values['element_'.$e->id.'_'.$i][$this->ai]));
		$js='jQuery(\'.datepicker\').datepicker({dateFormat:\''.$dateFormat.'\',showOn:\'focus\'});';
		$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" data-js=\"$js\" class=\"element text datepicker\" size=\"{$this->size}\" value=\"{$e->values['element_'.$e->id.'_'.$i][$this->ai]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		if ($this->mode != 'view') {
			$field_markup.='<script type="text/javascript" language="javascript">';
			$field_markup.='jQuery(document).ready(function() {';
			$field_markup.='jQuery(".datepicker").datepicker({dateFormat:\''.$dateFormat.'\',showOn:\'focus\'});';
			$field_markup.='})';
			$field_markup.='</script>';
		}
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}