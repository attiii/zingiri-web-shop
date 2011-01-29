<?php
class file_multipleZfSubElement extends zfSubElement {

	function postSave($id=0)
	{
		$prefix=$this->element->input['upload_file_key'];
		$product_dir=@constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)]);
		$totalFiles=count($this->element->input['loaded_files'])+count($this->element->input['new_images'])-count($this->element->input['delimage']);
		$picid=$id;

		//delete images if required
		if (count($this->element->input['delimage'])>0) {
			foreach ($this->element->input['delimage'] as $imageid) {
				unlink($product_dir.'/'.$prefix.'__'.$imageid);
			}
		}

		//set default image
		if ($totalFiles > 0) $link=$prefix;
		else $link='';
		$column=$this->element->elementToColumn['element_'.$this->elementid.'_'.$this->subid];
		$db=new db();
		$db->updateRecord($this->element->entity,array('ID' => $id),array($column => $link));
		$this->ext=$this->int=$link;
		
		return true;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$constant_dir=$this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)];
		$product_dir=@constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)]);
		list($filePrefix,$fileName)=explode('__',$this->element->populated_value['element_'.$this->elementid.'_'.$this->subid]);
		if (empty($filePrefix)) $filePrefix=create_sessionid(16,1,36);

		$picid=is_numeric($_GET['id']) ? $_GET['id'] : 0;

		$field_markup.=loadJavascript(ZING_APPS_PLAYER_URL . 'js/ajaxupload.js');
		$field_markup.=loadJavascript(ZING_APPS_PLAYER_URL . 'js/fileupload.jquery.js');

		$field_markup.='<input type="button" id="upload_file_button" value="'.zurl('Upload').'" />';
		$field_markup.='<input type="hidden" id="upload_file_dir" value="'.$constant_dir.'" />';
		$field_markup.='<input type="hidden" name="upload_file_key" id="upload_file_key" value="'.$filePrefix.'">';

		$imgs=array();
		$field_markup.='<ul id="uploaded_files">';
		if ($handle=opendir($product_dir)) {
			while (($img = readdir($handle))!==false) {
				if (strstr($img,$filePrefix.'__')) {
					$f=explode('__',$img);
					$imgs[]=$f[1];
				}
			}
			closedir($handle);
		}
		if (count($imgs) > 0) {
			asort($imgs);
			foreach ($imgs as $img) {
				$field_markup.='<li id="'.$img.'" style="position:relative;clear:both">';
				$field_markup.='<p>'.$img.'</p>';
				$field_markup.='<a onclick="wsDeleteFile(\''.$img.'\');">';
				$field_markup.='<img style="position:absolute;right:-16px;top:0px;" src="'.ZING_APPS_PLAYER_URL.'images/delete.png" height="16px" width="16px" />';
				$field_markup.="</a>";
				$field_markup.='<input name="loaded_files[]" type="hidden" value="'.$img.'" />';
				$field_markup.='</li>';
			}
		}
		$field_markup.='</ul><div style="clear:both"></div>';

		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}