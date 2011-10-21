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
class action_buttonZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$e=$this->element;
		$i=$this->subid;
		$this->ext='<form action="'.$this->int.'" method="post">';
		$this->ext.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" value=\"".z_('Go')."\" type=\"submit\"/>";
		$this->ext.='</form>';
		//=$this->int;
		return $this->ext;
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