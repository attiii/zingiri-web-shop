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

	function output($mode="edit",$input="")
	{
		$g=explode('-',$this->int);
		if (empty($g[0])) $this->ext=$g[0];
		elseif (isset($g[1])) {
			require(ZING_LOC."extensions/gateways/".$g[0]."/config/".$g[1].".php");
			$this->ext=$aSettings['GATEWAY_NAME'];
		} elseif (!empty($g[0])) {
			require(ZING_LOC."extensions/gateways/".$g[0]."/config.php");
			$this->ext=$aSettings['GATEWAY_NAME'];
		} else {
			$this->ext='';
		}
		return $this->ext;
	}
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$field_markup.="<select id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly}>";

		$option_markup = "<option value=\"\"></option>";

		$files=faces_directory(ZING_LOC."extensions/gateways","",true);
		foreach ($files as $file) {
			if (file_exists(ZING_LOC."extensions/gateways/".$file."/config")) {
				$option_markup.='<optgroup label="'.$file.'">';
				$files2=faces_directory(ZING_LOC."extensions/gateways/".$file."/config","",false);
				foreach ($files2 as $file2) {
					require(ZING_LOC."extensions/gateways/".$file."/config/".$file2);
					$value2=$file.'-'.str_replace('.php','',$file2);
					if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == $value2) $selected = 'selected="selected"';
					elseif ($e->default_value == $value2) $selected = 'selected="selected"';
					else $selected='';
					$option_markup .= '<option style="padding-left: 20px;" value="'.$value2.'" '.$selected.'>'.$aSettings['GATEWAY_NAME'].'</option>';
				}
				$option_markup.='</optgroup>';
			} else {	
				require(ZING_LOC."extensions/gateways/".$file."/config.php");
				if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == $file) $selected = 'selected="selected"';
				elseif ($e->default_value == $file) $selected = 'selected="selected"';
				else $selected='';
				if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$file."\" {$selected}>".$aSettings['GATEWAY_NAME']."</option>";
			}
		}
		if (get_option('zing_webshop_pro')) {
			$files=faces_directory(ZING_WS_PRO_DIR."../extensions/gateways","",true);
			foreach ($files as $file) {
				if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == $file){
					$selected = 'selected="selected"';
				} elseif ($e->default_value == $file) {
					$selected = 'selected="selected"';
				} else $selected='';
				if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$file."\" {$selected}>".$file."</option>";
			}
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label for=\"element_{$e->id}_{$i}\">{$xmlf->fields->{'field'.$i}->label}</label>";
		}
	}
	?>