<?php
/*  sub.sql.class.php
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
class payment_gatewayZfSubElement extends zfSubElement {

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$field_markup.="<select id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly}>";

		$option_markup = "<option value=\"\"></option>";
		
		$files=faces_directory(ZING_LOC."extensions/gateways","",true);
		foreach ($files as $file) {
			if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == $file){
				$selected = 'selected="selected"';
			} elseif ($e->default_value == $file) {
				$selected = 'selected="selected"';
			} else $selected='';
			if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$file."\" {$selected}>".$file."</option>";
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label for=\"element_{$e->id}_{$i}\">{$xmlf->fields->{'field'.$i}->label}</label>";
	}
}
?>