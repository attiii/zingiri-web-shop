<?php
/*  sub.-default.class.php
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
class zfSubElement {
	var $int;
	var $xmlf;
	var $elementid;
	var $subid;
	var $error_message;
	var $is_error;
	var $element;
	var $populated_column=array();


	function zfSubElement($int,$ext="",$xmlf="",$element="",$subid="",$ai=0) {
		$this->int=$int;
		$this->xmlf=$xmlf;
		$this->elementid=$element->id;
		$this->element=$element;
		$this->subid=$subid;
		if (is_array($ext)) $this->ext=$ext;
		else $this->ext=trim($ext);
		$this->error_message="";
		$this->is_error=false;
		$this->size=$element->attributes['zfsize'] ? $element->attributes['zfsize'] : $xmlf->fields->{'field'.$this->subid}->size;
		$this->maxlength=$element->attributes['zfmaxlength'] ? $element->attributes['zfmaxlength'] : $xmlf->fields->{'field'.$this->subid}->maxlength;
		$this->ai=$ai;
		if ($element->isRepeatable) {
			if ($ai>0) $this->ail='_'.$this->ai; else $this->ail='';
			$element->values['element_'.$element->id.'_'.$subid]=$element->populated_value['element_'.$element->id.'_'.$subid];
		} else {
			$element->values['element_'.$element->id.'_'.$subid][0]=$element->populated_value['element_'.$element->id.'_'.$subid];
		}
	}

	function getLabel($label) {
		return $label;
	}
	
	function prepare() {
	}

	function output($mode="edit",$input="")
	{
		$this->ext=$this->int;
		return $this->ext;
	}

	function verifyall()
	{
		if ($this->element->is_required && trim($this->ext)=="")
		{
			return $this->error("Field is mandatory!");
		}
		return $this->verify();
	}

	function verify()
	{
		$this->ext=$this->int;
		return true;
	}

	function error($error_message) {
		$this->error_message=z_($error_message);
		$this->is_error=true;
		return false;
	}

	function postSave($id=0) {
		return true;
	}
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		
		if($e->values['element_'.$e->id.'_'.$i][$this->ai] == ""){
			$e->values['element_'.$e->id.'_'.$i][$this->ai] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->values['element_'.$e->id.'_'.$i][$this->ai]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$e->readonly}/>";
		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}

}
?>