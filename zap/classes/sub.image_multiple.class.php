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
class image_multipleZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		if ($mode=='list') {
			$url=@constant($input['element_'.$this->elementid.'_'.($this->subid+2)]);
			$image=$url.'/'.$input['element_'.$this->elementid.'_'.$this->subid];
			if ($this->int!='') $this->ext='<img src="'.$image.'" height="48px"/>';
			else $this->ext='';
		} else $this->ext='';
		return $this->ext;
	}

	function postSave($id=0)
	{
		$product_dir=@constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)]);
		$product_url=@constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+2)]);

		$picid=$id;

		//set default image
		if (isset($this->element->input['image_default'])) $defaultImage=$this->element->input['image_default'];

		// move the multiple uploaded images to the correct folder
		if ($this->element->input['upload_key']!='') {
			$key=$this->element->input['upload_key'];
			$imgs=$this->element->input['new_images'];
			if (count($imgs) > 0) {
				foreach ($imgs as $img) {
					foreach (array("","tn_") as $tn) {
						$ext = strtolower(substr(strrchr($img, '.'), 1));
						if (isset($this->element->input['lastimg'])) $i=$this->element->input['lastimg'];
						else $i=1;
						$newimg=$tn.$picid.'__'.sprintf('%03d',$i).'.'.$ext;
						while (file_exists($product_dir.'/'.$newimg)) {
							$i++;
							$newimg=$tn.$picid.'__'.sprintf('%03d',$i).'.'.$ext;
						}
						copy($product_dir.'/'.$tn.$img,$product_dir.'/'.$newimg);
						unlink($product_dir.'/'.$tn.$img);
						if ($tn.$img==$defaultImage) $defaultImage=$newimg;
					}
				}
				if (empty($defaultImage)) $defaultImage=$newimg;
			}
		}

		//delete images if required
		if (count($this->element->input['delimage'])>0) {
			foreach ($this->element->input['delimage'] as $imageid) {
				unlink($product_dir.'/'.$imageid);
				unlink($product_dir.'/'.str_replace('tn_','',$imageid));
			}
		}

		//set default image
		$column=$this->element->elementToColumn['element_'.$this->elementid.'_'.$this->subid];
		$db=new db();
		$db->updateRecord($this->element->entity,array('ID' => $id),array($column => $defaultImage));
		$this->ext=$this->int=$defaultImage;

		return true;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$product_dir=@constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)]);
		$product_url=@constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+2)]);
		$defaultImage=$this->element->populated_value['element_'.$this->elementid.'_'.$this->subid];

		$picid=is_numeric($_GET['id']) ? $_GET['id'] : 0;

		$field_markup.='<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/ajaxupload.js"></script>';
		$field_markup.='<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/imageupload.jquery.js"></script>';

		$field_markup.='<input type="button" id="upload_button" value="'.zurl('Upload a picture').'" />';
		$field_markup.='<input type="hidden" name="upload_key" id="upload_key" value="'.create_sessionid(16,1,36).'">';

		$imgs=array();
		$field_markup.='<div id="uploaded_images">';
		if ($handle=opendir($product_dir)) {
			while (($img = readdir($handle))!==false) {
				if (strstr($img,'tn_'.$picid.'.') || strstr($img,'tn_'.$picid.'__')) {
					$imgs[]=$img;
				}
			}
			closedir($handle);
		}
		if (count($imgs) > 0) {
			asort($imgs);
			foreach ($imgs as $img) {
				$field_markup.='<div id="'.$img.'" style="position:relative;float:left">';
				$field_markup.="<img src=\"".$product_url."/".$img."\" class=\"borderimg\" /><br />";
				$field_markup.='<a href="javascript:wsDeleteImage(\''.$img.'\');">';
				$field_markup.='<img style="position:absolute;right:0px;top:0px;" src="'.ZING_APPS_PLAYER_URL.'images/delete.png" height="16px" width="16px" />';
				$field_markup.="</a>";
				if ($img == $defaultImage) $checked='checked'; else $checked='';
				$field_markup.='<input type="radio" name="image_default" value="'.$img.'" '.$checked.' />';
				$field_markup.='</div>';
				preg_match('/tn_(.*)__(.*)\./',$img,$matches);
				if (count($matches) == 3) $lastimg=$matches[2]+1;
				else $lastimg=1;
			}
			$field_markup.='<input type="hidden" name="lastimg" id="lastimg" value="'.$lastimg.'">';
		}
		$field_markup.='</div><div style="clear:both"></div>';

		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}