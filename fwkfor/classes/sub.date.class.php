<?php
class dateZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$e=$this->element;
		$dateFormat=isset($e->populated_value['element_'.$e->id.'_2']) ? $e->populated_value['element_'.$e->id.'_2'] : 'dd-mm-yy';
		if ($this->int!='' && $this->int!='0000-00-00') $this->ext=date($dateFormat=='mm/dd/yy' ? 'm/d/Y' : 'd-m-Y',strtotime($this->int));
		else $this->ext='';
		
		return $this->ext;
	}

	function verify()
	{
		$e=$this->element;
		$dateFormat=isset($e->populated_value['element_'.$e->id.'_2']) ? $e->populated_value['element_'.$e->id.'_2'] : 'mm/dd/yy';

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
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$dateFormat=isset($e->populated_value['element_'.$e->id.'_2']) ? $e->populated_value['element_'.$e->id.'_2'] : 'mm/dd/yy';
		$element='element_'.$e->id.'_'.$i;

		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = trim($xmlf->fields->{'field'.$i}->default);
		}
		if (($this->mode == 'add') && ($e->populated_value['element_'.$e->id.'_3']=='today')) $e->populated_value['element_'.$e->id.'_'.$i]=date($dateFormat=='mm/dd/yy' ? 'm/d/Y' : 'd-m-Y');
		elseif ($e->populated_value['element_'.$e->id.'_'.$i]=='0000-00-00') $e->populated_value['element_'.$e->id.'_'.$i]='';
		elseif ($e->populated_value['element_'.$e->id.'_'.$i]) $e->populated_value['element_'.$e->id.'_'.$i]=date($dateFormat=='mm/dd/yy' ? 'm/d/Y' : 'd-m-Y',strtotime($e->populated_value['element_'.$e->id.'_'.$i]));
		if ($this->mode=='print') $field_markup.="<div class=\"element text\" >{$e->values['element_'.$e->id.'_'.$i][$this->ai]}</div>";
		else $field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		if (in_array($this->mode,array('edit','add'))) {
			$field_markup.='<script type="text/javascript" language="javascript">';
			$field_markup.='jQuery(document).ready(function() {';
			$field_markup.='jQuery("#'.$element.'").datepicker({dateFormat:\''.$dateFormat.'\',buttonImage:\''.ZING_APPS_PLAYER_URL.'images/calendar.gif\',buttonImageOnly:true,showOn:\'button\'});';
			$field_markup.='})';
			$field_markup.='</script>';
		}
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}