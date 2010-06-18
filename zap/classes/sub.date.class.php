<?php
/*  sub.date.class.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Apps.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Zingiri Apps; if not, write to the Free Software
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
			$this->error_message="Wrong date format!";
			$this->is_error=true;
		} else {
			if ($this->ext!='') {
				$this->int=date("Ymd",strtotime($this->ext));
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
		
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		if ($e->populated_value['element_'.$e->id.'_'.$i]=='0000-00-00') $e->populated_value['element_'.$e->id.'_'.$i]='';
		$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".$xmlf->fields->{'field'.$i}->label."</label>";
	}
}