<?php
/*  sub.select.class.php
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
class imageZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		if (!empty($input['element_'.$this->elementid.'_'.($this->subid+1)])) {
			$url=@constant($input['element_'.$this->elementid.'_'.($this->subid+1)].'URL');
			$image=$url.$input['element_'.$this->elementid.'_'.$this->subid];
			if ($this->int!='') $this->ext='<img src="'.$image.'" height="48px"/>';
			else $this->ext='';
		} else $this->ext='';
		return $this->ext;
	}

	function verify()
	{
		parent::verify();

		$dir=@constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)].'DIR');
		$control=$_POST['control_element_'.$this->elementid.'_'.$this->subid];
		$eln='file_element_'.$this->elementid.'_'.$this->subid;
		$name=$this->element->populated_value['element_'.$this->elementid.'_'.$this->subid];

		if ($file = $_FILES[$eln]['name']) {
			$ext = strtolower(substr(strrchr($file, '.'), 1));
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
		} elseif ($control=='del' && isset($name)) {
			unlink($dir.$name);
			$this->ext=$this->int='';
			return true;
		} else {
			$this->ext=$this->int=$name;
			return true;
		}

	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$url=@constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)].'URL');
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<input id=\"file_element_{$e->id}_{$i}\" name=\"file_element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" maxlength=\"{$this->maxlength}\" type=\"file\" {$e->readonly}/>";
		if ($e->populated_value['element_'.$e->id.'_'.$i]) {
			$image=$url.$e->populated_value['element_'.$e->id.'_'.$i];
			$field_markup.="<img id=\"image_element_{$e->id}_{$i}\" name=\"image_element_{$e->id}_{$i}\" class=\"element\" src=\"".$image."\" />";
			$field_markup.="<input id=\"element_{$e->id}_{$i}\" type=\"hidden\" name=\"element_{$e->id}_{$i}\" class=\"element\" value=\"".$e->populated_value['element_'.$e->id.'_'.$i]."\" />";
			$field_markup.="<input id=\"control_element_{$e->id}_{$i}\" name=\"control_element_{$e->id}_{$i}\" type=\"hidden\" />";
			$js='void(0)';
			if (ZING_PROTOTYPE) {
				$js='$(\'image_element_'.$e->id.'_'.$i.'\').hide();';
				$js.='$(\'control_element_'.$e->id.'_'.$i.'\').value=\'del\';';
				$js.='$(\'handle_element_'.$e->id.'_'.$i.'\').hide();';
			} elseif (ZING_JQUERY) {
				$js='jQuery(\'image_element_'.$e->id.'_'.$i.'\').hide();';
				$js.='jQuery(\'control_element_'.$e->id.'_'.$i.'\').attr(\'value\',\'del\');';
				$js.='jQuery(\'handle_element_'.$e->id.'_'.$i.'\').hide();';
			}
			$field_markup.='<a id="handle_element_'.$e->id.'_'.$i.'" href="javascript:void(0);" onclick="'.$js.'"><img src="'.ZING_APPS_PLAYER_URL.'/images/delete.png" height="16px" /></a>';
		}
		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}