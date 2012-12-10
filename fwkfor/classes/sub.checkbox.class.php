<?php
/*  sub.-default.class.php
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
class checkboxZfSubElement extends zfSubElement {
	function output($mode="edit",$input="")
	{
		if ($this->int) $checked = 'checked="checked"'; else $checked='';
		$this->ext='<input class="element checkbox" type="checkbox" '.$checked.' disabled="disabled" />';
		return $this->ext;
	}

	function verify()
	{
		if ($this->ext == "on") { $this->int=1; }
		else { $this->int=0; }
		return true;
	}
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		if(!empty($e->populated_value['element_'.$e->id.'_'.$i])){
			$checked = 'checked="checked"';
		}elseif(!empty($e->populated_value['element_'.$e->id])){
			$checked = 'checked="checked"';
		}else{
			$checked = '';
		}
		$option_markup = "<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element checkbox\" type=\"checkbox\" {$checked} {$e->readonly}/>\n";
		$field_markup.=$option_markup;
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}
?>