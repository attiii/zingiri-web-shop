<?php
/*  sub.-default.class.php
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
if (!defined("ZING_APPS_CAPTCHA")) define("ZING_APPS_CAPTCHA",dirname(__FILE__).'/../fields/captcha/');

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
		$number=$this->ext;
		if(!file_exists(ZING_APPS_CAPTCHA.$number.".key") || $number == "0"){
			return ($this->error("Code incorrect!"));
		}
		else { unlink (ZING_APPS_CAPTCHA.$number.".key"); }

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
		$img='<img style="float:left" src="'.ZING_URL.'fws/addons/captcha/php_captcha.php" />&nbsp';
		$field_markup.=$img;
		$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$xmlf->fields->{'field'.$i}->size}\" value=\"{$e->populated_value['element_'.$e->id.'_'.$i]}\" maxlength=\"{$xmlf->fields->{'field'.$i}->maxlength}\" type=\"text\" {$e->readonly}/>";
		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".$xmlf->fields->{'field'.$i}->label."</label>";
	}

}
?>