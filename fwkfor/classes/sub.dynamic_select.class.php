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
class dynamic_selectZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$e=$this->element;
		$i=$this->subid;
		
		if (isset($input['element_'.$e->id.'_2'])) {
			$keypairs=explode(",",trim($input['element_'.$e->id.'_2']));
			if (count($keypairs) > 0) {
				foreach ($keypairs as $keypair) {
					list($value,$option)=explode("=",$keypair);
					if(trim($this->int) == trim($value)) {
						$this->ext=$option;
					}
				}
			} 
		} else $this->ext='';
		return $this->ext;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		if (isset($e->populated_value['element_'.$e->id.'_2'])) $keypairs=explode(",",trim($e->populated_value['element_'.$e->id.'_2']));
		else $keypairs=array();
		//$size=intval($e->populated_value['element_'.$e->id.'_3']);

		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}

	
		if ($this->mode=='view') {
			if (count($keypairs) > 0) {
				foreach ($keypairs as $keypair) {
					list($value,$option)=explode("=",$keypair);
					if ($e->populated_value['element_'.$e->id.'_'.$i] == $value) $field_markup.=$option;
				}
			}
		} else {
			$field_markup.="<select id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" style=\"width: {$xmlf->fields->{'field'.$i}->width}\" {$e->readonly}>";
			$option_markup="";
			if (count($keypairs) > 0) {
				foreach ($keypairs as $keypair) {
					list($value,$option)=explode("=",$keypair);
					$selected="";
					if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == trim($option)) {
						$selected = 'selected="selected"';
					} elseif ($e->populated_value['element_'.$e->id.'_'.$i] == $value) {
						$selected = 'selected="selected"';
					}
					if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$value."\" {$selected}>".$option."</option>";
				}
			}
		}

		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";

		}
		}