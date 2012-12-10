<?php
/*  sub.select.class.php
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
class selectZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		foreach ($this->xmlf->values->children() as $child) {
			$t=(array)$child;
			$option=isset($t[0]) ? $t[0] : '';
			if (isset($child->attributes()->value)) $value=$child->attributes()->value;
			else $value=$option;
			if ($value==$this->int) $this->ext=$option;
		}
		return $this->ext;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<select id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" style=\"width: {$xmlf->fields->{'field'.$i}->width}\" {$e->readonly}>";
		$option_markup="";
		if ($xmlf->fields->{'field'.$i}->values->attributes()->type=='multi') {
			foreach ($xmlf->fields->{'field'.$i}->values->children() as $child) {
				$t=(array)$child;

				$option=isset($t[0]) ? $t[0] : '';
				if (isset($child->attributes()->value)) $value=$child->attributes()->value;
				else $value=$option;
				$selected="";
				if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == trim($option)) {
					$selected = 'selected="selected"';
				} elseif ($e->populated_value['element_'.$e->id.'_'.$i] == $value) {
					$selected = 'selected="selected"';
				}
				if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$value."\" {$selected}>".$option."</option>";
			}
		} elseif ($xmlf->fields->{'field'.$i}->values->attributes()->type=='range') {
			$start=(int)$xmlf->fields->{'field'.$i}->values->attributes()->start;
			$end=(int)$xmlf->fields->{'field'.$i}->values->attributes()->end;
			for ($option=$start; $option <= $end; $option++) {
				$selected="";
				if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == $option) {
					$selected = 'selected="selected"';
				}
				if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$option."\" {$selected}>".$option."</option>";
			}
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";

		}
	}