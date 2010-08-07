<?php
/*  sub.radio.class.php
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
class radioZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$this->ext="";
		if ($mode!='list') {
			$this->ext=$this->int;
			return $this->ext;
		}
		$e=$this->element;
		$pairs=explode(",",$input['element_'.$e->id.'_2']);
		foreach ($pairs as $pair) {
			list($value,$option)=explode('=',$pair);
			if ($this->int == $value) $this->ext=$option;
		}
		return $this->ext;
	}
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		$pairs=explode(",",$e->populated_value['element_'.$e->id.'_2']);
		$k=0;
		$default=trim($e->populated_value['element_'.$e->id.'_'.$i]);
		foreach ($pairs as $pair) {
			$k++;
			list($value,$option)=explode('=',$pair);
			if ($value==$default) { $checked="checked"; } else { $checked=""; }
			$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" value=\"{$value}\" type=\"radio\" {$e->readonly} {$checked}/> {$option}<br />";
		}
		$subscript_markup.="<label for=\"element_{$e->id}_{$i}\">{$xmlf->fields->{'field'.$i}->label}</label>";
	}
}
?>