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
class image_multipleZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		if ($mode=='list') {
			if (isset($input['element_'.$this->elementid.'_'.($this->subid+2)]) && ($c=constant($input['element_'.$this->elementid.'_'.($this->subid+2)]))) $url=$c;
			else $url=APHPS_DATA_URL; 
			$image=$url.'/'.$input['element_'.$this->elementid.'_'.$this->subid];
			if ($this->int!='') $this->ext='<img src="'.$image.'" height="48px"/>';
			else $this->ext='';
		} else $this->ext='';
		return $this->ext;
	}

	function postSave($id=0)
	{
		if (isset($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)]) && ($c=constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)]))) $product_dir=$c;
		else $product_dir=APHPS_DATA_DIR;
		if (isset($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+2)]) && ($c=constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+2)]))) $product_url=$c;
		else $product_dir=APHPS_DATA_URL;
		
		$picid=$id;

		//set default image
		if (isset($this->element->input['image_default'])) $defaultImage=$this->element->input['image_default'];
		else $defaultImage=null;

		// move the multiple uploaded images to the correct folder
		if ($this->element->input['upload_key']!='') {
			$key=$this->element->input['upload_key'];
			$imgs=isset($this->element->input['new_images']) ? $this->element->input['new_images'] : null;
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
						copy(APHPS_DATA_DIR.'/'.$tn.$img,$product_dir.'/'.$newimg);
						unlink(APHPS_DATA_DIR.'/'.$tn.$img);
						if ($tn.$img==$defaultImage) $defaultImage=$newimg;
					}
				}
				if (empty($defaultImage)) $defaultImage=$newimg;
			}
		}

		//delete images if required
		if (isset($this->element->input['delimage']) && count($this->element->input['delimage'])>0) {
			foreach ($this->element->input['delimage'] as $imageid) {
				unlink($product_dir.'/'.$imageid);
				unlink($product_dir.'/'.str_replace('tn_','',$imageid));
			}
		}

		//set default image
		if ($defaultImage) {
			$column=$this->element->elementToColumn['element_'.$this->elementid.'_'.$this->subid];
			$db=new aphpsDb();
			$db->updateRecord($this->element->entity,array('ID' => $id),array($column => $defaultImage));
			$this->ext=$this->int=$defaultImage;
		}

		return true;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		if (isset($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)]) && ($c=constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+1)]))) $product_dir=$c;
		else $product_dir=APHPS_DATA_DIR;
		if (isset($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+2)]) && ($c=constant($this->element->populated_value['element_'.$this->elementid.'_'.($this->subid+2)]))) $product_url=$c;
		else $product_dir=APHPS_DATA_URL;
		$defaultImage=$this->element->populated_value['element_'.$this->elementid.'_'.$this->subid];

		$picid=(isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 0;

		if ($this->mode != 'view') {
			$field_markup.='<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/' . APHPS_JSDIR . '/ajaxupload.js"></script>';
			$field_markup.='<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/' . APHPS_JSDIR . '/imageupload.jquery.js"></script>';
			$field_markup.='<input type="button" id="upload_button" value="'.zurl('Upload a picture').'" />';
			$field_markup.='<input type="hidden" name="upload_key" id="upload_key" value="'.$this->createRandomCode(16).'">';
		}

		$imgs=array();
		$field_markup.='<div id="uploaded_images">';
		
		if ($handle=opendir($product_dir)) {
			while (($img = readdir($handle))!==false) {
				if (strstr($img,'tn_'.$picid.'.') || strstr($img,'tn_'.$picid.'__') || (!function_exists('createthumb') && strpos($img,$picid)===0)) {
					$imgs[]=$img;
				}
			}
			closedir($handle);
		}
		if (count($imgs) > 0) {
			asort($imgs);
			foreach ($imgs as $img) {
				$field_markup.='<div id="'.$img.'" style="position:relative;float:left">';
				if (strpos($img,'tn_')===0) $image_markup="<img src=\"".$product_url."/".$img."\" class=\"borderimg\" /><br />";
				else $image_markup.="<img height=\"48px\" src=\"".$product_url."/".$img."\" class=\"borderimg\" /><br />";
				if ($this->mode == 'view') {
					$field_markup.='<a href="'.$product_url.'/'.$img.'">'.$image_markup.'</a>';
				} else {
					$field_markup.=$image_markup;
					$field_markup.='<a onclick="wsDeleteImage(\''.$img.'\');">';
					$field_markup.='<img style="position:absolute;right:0px;top:0px;" src="'.ZING_APPS_PLAYER_URL.'images/delete.png" height="16px" width="16px" />';
					$field_markup.="</a>";
					if ($img == $defaultImage) $checked='checked'; else $checked='';
					$field_markup.='<input type="radio" name="image_default" value="'.$img.'" '.$checked.' />';
					$field_markup.='</div>';
					preg_match('/tn_(.*)__(.*)\./',$img,$matches);
					if (count($matches) == 3) $lastimg=$matches[2]+1;
					else $lastimg=1;
				}
			}
			$field_markup.='<input type="hidden" name="lastimg" id="lastimg" value="'.$lastimg.'">';
		}
		$field_markup.='</div><div style="clear:both"></div>';

		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}