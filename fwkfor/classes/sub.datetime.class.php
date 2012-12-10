<?php
class datetimeZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		if ($this->int!='' && $this->int!='0000-00-00') $this->ext=date("d-m-Y H:i:s",strtotime($this->int));
		else $this->ext='';
		return $this->ext;
	}

	function verify()
	{
		$success=true;
		if ($this->ext!='' && !strtotime($this->ext))
		{
			$success=false;
			$this->error_message=z_("Wrong date format!");
			$this->is_error=true;
		} else {
			if ($this->ext!='') {
				$this->int=date("Y-m-d H:i:s",strtotime($this->ext));
				$this->ext=date("d-m-Y H:i:s",strtotime($this->ext));
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
		
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		if ($e->populated_value['element_'.$e->id.'_'.$i]=='0000-00-00') $e->populated_value['element_'.$e->id.'_'.$i]='';
		else $e->populated_value['element_'.$e->id.'_'.$i]=date('d-m-Y H:i:s',strtotime($e->populated_value['element_'.$e->id.'_'.$i]));
		
		$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		$field_markup.='&nbsp<img id="cal_img_6" class="datepicker" src="'.ZING_APPS_PLAYER_URL.'images/calendar.gif" alt="Pick a date." />';
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}