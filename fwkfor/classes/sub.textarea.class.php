<?php
/*  sub.htmlarea.class.php
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
class textareaZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		return htmlentities($this->ext);
			
	}

	function verify()
	{
		$stripped=stripslashes($this->int);
		$this->ext=$stripped;
		$this->int=$stripped;
		return true;
	}
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		$size=$xmlf->fields->{'field'.$i}->size;
		$sizes=explode(",",$size);
		if (!isset($sizes[0]) || !is_numeric($sizes[0])) $sizes[0]=40;
		if (!isset($sizes[1]) || !is_numeric($sizes[1])) $sizes[1]=3;
		$field_markup.="<textarea id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"mceNoEditor element text\" cols=\"{$sizes[0]}\" rows=\"{$sizes[1]}\" {$e->readonly}>".$e->populated_value['element_'.$e->id.'_'.$i]."</textarea>";
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}
?>