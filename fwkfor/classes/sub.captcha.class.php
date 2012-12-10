<?php
/*  sub.-default.class.php
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
class captchaZfSubElement extends zfSubElement {
	var $int;
	var $xmlf;
	var $elementid;
	var $subid;
	var $error_message;
	var $is_error;
	var $element;
	var $populated_column=array();

	function verify()
	{
		$hash=$_REQUEST['hash_element_'.$this->elementid.'_'.$this->subid];
		$key=$this->ext;
				
		if (md5(strtoupper($key).APHPS_SECRET) != $hash) {
			return ($this->error("Code incorrect!"));
		}

		$this->ext=$this->int;
		return true;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		if ($this->mode == 'add') {
			list($hash,$url)=$this->captcha();
			$field_markup.='<img style="float:left" src="'.$url.'" />';
			$field_markup.="<input id=\"hash_element_{$e->id}_{$i}\" name=\"hash_element_{$e->id}_{$i}\" value=\"{$hash}\" type=\"hidden\"/>";
			$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$xmlf->fields->{'field'.$i}->size}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i]}\" maxlength=\"{$xmlf->fields->{'field'.$i}->maxlength}\" type=\"text\" {$e->readonly}/>";
		} elseif ($this->mode == 'build') {
			$field_markup.='<img style="float:left" src="'.ZING_APPS_PLAYER_URL.'images/captcha.jpg" />';
			$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$xmlf->fields->{'field'.$i}->size}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i]}\" maxlength=\"{$xmlf->fields->{'field'.$i}->maxlength}\" type=\"text\" {$e->readonly}/>";
		}
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}

	function captcha() {
		$dir=APHPS_DATA_DIR.'temp/';
		// make random string and paste it onto the image
		$file = 'key.'.md5(microtime().rand()).'.jpg';// md5 to generate the random string
		$key = substr(md5(microtime().rand()),0,5);
		$hash=md5(strtoupper($key).APHPS_SECRET);
		$NewImage =imagecreatefromjpeg(ZING_APPS_PLAYER_DIR."images/img.jpg");//image create by existing image and as back ground
		$LineColor = imagecolorallocate($NewImage,233,239,239);//line color
		$TextColor = imagecolorallocate($NewImage, 255, 255, 255);//text color-white
		imageline($NewImage,1,1,40,40,$LineColor);//create line 1 on image
		imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image
		imagestring($NewImage, 5, 20, 10, $key, $TextColor);// Draw a random string horizontally

		// now lets delete captcha files older than 15 minutes:
		if ($handle = @opendir($dir)) {
			while (($filename = readdir($handle)) !== false) {
				if(time() - filemtime($dir . $filename) > 15 * 60 && substr($filename, 0, 4) == 'key.') {
					@unlink($dir . $filename);
				}
			}
			closedir($handle);
		}

		// now save captcha image
		imagejpeg($NewImage,$dir.$file);
		
		return array($hash,APHPS_DATA_URL.'temp/'.$file);

	}
}
