<?php
/*  sub.date.class.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.aphps.com

 This file is part of APhPS.

 APhPS is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 APhPS is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with APhPS; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
class dateZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		if ($this->int!='' && $this->int!='0000-00-00') $this->ext=date("d-m-Y",strtotime($this->int));
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
				$this->int=date("Y-m-d",strtotime($this->ext));
				$this->ext=date("d-m-Y",strtotime($this->ext));
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
		
		$element='element_'.$e->id.'_'.$i;
		
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = trim($xmlf->fields->{'field'.$i}->default);
		}
		if ($e->populated_value['element_'.$e->id.'_'.$i]=='0000-00-00') $e->populated_value['element_'.$e->id.'_'.$i]='';
		elseif ($e->populated_value['element_'.$e->id.'_'.$i]) $e->populated_value['element_'.$e->id.'_'.$i]=date('d-m-Y',strtotime($e->populated_value['element_'.$e->id.'_'.$i]));
		$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		$field_markup.='<script type="text/javascript" language="javascript">';
		$field_markup.='jQuery(document).ready(function() {';
	    $field_markup.='jQuery("#'.$element.'").datepicker({dateFormat:\'dd-mm-yy\',buttonImage:\''.ZING_APPS_PLAYER_URL.'images/calendar.gif\',buttonImageOnly:true,showOn:\'button\'});';
	    $field_markup.='})';
		$field_markup.='</script>';
		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}