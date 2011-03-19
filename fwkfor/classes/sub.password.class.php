<?php
/*  sub.password.class.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

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
class passwordZfSubElement extends zfSubElement {
	
	function output($mode="edit",$input="")
	{
		$this->ext="";
		return $this->ext;
	}
	
	function verify() {
		$pass1=$this->element->populated_value['element_'.$this->element->id.'_1'];
		$pass2=$this->element->populated_value['element_'.$this->element->id.'_2'];
		if (strlen($pass1) > 40) {
			return ($this->error("Password is too long!"));
		}
		if ($pass1 != $pass2) {
			return ($this->error("Passwords are not matching!"));
		}
		$this->int=md5($pass1);
		return true;
	}
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"\" maxlength=\"{$this->maxlength}\" type=\"password\" {$e->readonly}/>";
		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
	
}