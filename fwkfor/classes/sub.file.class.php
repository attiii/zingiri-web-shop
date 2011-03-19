<?php
/*  sub.select.class.php
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
class fileZfSubElement extends zfSubElement {

	function verify()
	{
		parent::verify();
		
		$secret=$this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)];
		$dir=constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+2)]);
		$eln='file_element_'.$this->elementid.'_'.$this->subid;

		if ($file = $_FILES[$eln]['name']) {
			$ext = strtolower(substr(strrchr($file, '.'), 1));
				
			if ($secret) 
			$file=CreateRandomCode(15).'__'.$file;

			$target_path = $dir.$file;

			if(move_uploaded_file($_FILES[$eln]['tmp_name'], $target_path)) {
				chmod($target_path,0644); // new uploaded file can sometimes have wrong permissions
				$this->ext=$this->int=$file;
				return true;
			}
			else {
				return false;
			}
		} else {
			$this->ext=$this->int; return true; 
		}

	}
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<input id=\"file_element_{$e->id}_{$i}\" name=\"file_element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" maxlength=\"{$this->maxlength}\" type=\"file\" {$e->readonly}/>";
		if ($e->populated_value['element_'.$e->id.'_'.$i]) {
			$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{".strlen($e->populated_value['element_'.$e->id.'_'.$i])."}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i]}\" type=\"text\" readonly/>";
			$js='void(0)';
			if (ZING_PROTOTYPE) $js='$(\'element_'.$e->id.'_'.$i.'\').value=\'\';';
			elseif (ZING_JQUERY) $js='jQuery(\'#element_'.$e->id.'_'.$i.'\').attr(\'value\',\'\');';
			$field_markup.='<a href="javascript:void(0);" onclick="'.$js.'"><img src="'.ZING_APPS_PLAYER_URL.'/images/delete.png" height="16px" /></a>';
		}
		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}