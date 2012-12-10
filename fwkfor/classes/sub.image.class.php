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
class imageZfSubElement extends zfSubElement {

	function verify()
	{
		parent::verify();

		$eln='file_element_'.$this->elementid.'_'.$this->subid;
		if (isset($_FILES[$eln]['name']) && $file = $_FILES[$eln]['name']) {
			$path_info = pathinfo($file);
			$allowedExtensions=array('jpg','bmp','png','gif');
			if (!in_array(strtolower($path_info['extension']),$allowedExtensions)) return $this->error("File extension not allowed!");
			$this->ext=$this->int=$file;
		}
		return true;
	}

	function output($mode="edit",$input="")
	{
		if ($this->int && defined('APHPS_DATA_URL') || !empty($input['element_'.$this->elementid.'_'.($this->subid+1)])) {
			$url=defined('APHPS_DATA_URL') ? APHPS_DATA_URL : constant($input['element_'.$this->elementid.'_'.($this->subid+1)].'URL');
			$image=$url.$this->int;
			$this->ext='<a href="'.$image.'"><img src="'.$image.'" height="48px" /></a>';
		} else $this->ext='';
		return $this->ext;
	}

	function postSave($id=0) 
	{
		$dir=defined('APHPS_DATA_DIR') ? APHPS_DATA_DIR : constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)].'DIR');
		$control=isset($_POST['control_element_'.$this->elementid.'_'.$this->subid]) ? $_POST['control_element_'.$this->elementid.'_'.$this->subid] : null;
		$eln='file_element_'.$this->elementid.'_'.$this->subid;
		$name=$this->element->populated_value['element_'.$this->elementid.'_'.$this->subid];

		if ($file = $_FILES[$eln]['name']) {
			$ext = strtolower(substr(strrchr($file, '.'), 1));
			$file=$this->createRandomCode(15).'__'.$file;
			$target_path = $dir.$file;
				
			if(move_uploaded_file($_FILES[$eln]['tmp_name'], $target_path)) {
				chmod($target_path,0644); // new uploaded file can sometimes have wrong permissions
				//update full image name
				$column=$this->element->elementToColumn['element_'.$this->elementid.'_'.$this->subid];
				$db=new aphpsDb();
				$db->updateRecord($this->element->entity,array('ID' => $id),array($column => $file));
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

		$url=defined('APHPS_DATA_URL') ? APHPS_DATA_URL : constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)].'URL');
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		if ($this->mode != 'view') $field_markup.="<input id=\"file_element_{$e->id}_{$i}\" name=\"file_element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" maxlength=\"{$this->maxlength}\" type=\"file\" {$e->readonly}/>";
		if ($e->populated_value['element_'.$e->id.'_'.$i]) {
			$image=$url.$e->populated_value['element_'.$e->id.'_'.$i];
			$image_markup="<img height=\"48px\" id=\"image_element_{$e->id}_{$i}\" name=\"image_element_{$e->id}_{$i}\" class=\"element\" src=\"".$image."\" />";
			if ($this->mode == 'view') {
				$field_markup.='<a href="'.$image.'">'.$image_markup.'</a>';
			} else {
				$field_markup.=$image_markup;
				$field_markup.="<input id=\"element_{$e->id}_{$i}\" type=\"hidden\" name=\"element_{$e->id}_{$i}\" class=\"element\" value=\"".$e->populated_value['element_'.$e->id.'_'.$i]."\" />";
				$field_markup.="<input id=\"control_element_{$e->id}_{$i}\" name=\"control_element_{$e->id}_{$i}\" type=\"hidden\" />";
				$js='void(0)';
				$js='jQuery(\'image_element_'.$e->id.'_'.$i.'\').hide();';
				$js.='jQuery(\'control_element_'.$e->id.'_'.$i.'\').attr(\'value\',\'del\');';
				$js.='jQuery(\'handle_element_'.$e->id.'_'.$i.'\').hide();';
				$field_markup.='<a id="handle_element_'.$e->id.'_'.$i.'" href="javascript:void(0);" onclick="'.$js.'"><img src="'.ZING_APPS_PLAYER_URL.'/images/delete.png" height="16px" /></a>';
			}
		}
		$subscript_markup.="<label class=\"subname\" class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}