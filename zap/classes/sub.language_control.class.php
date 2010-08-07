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
class language_controlZfSubElement extends zfSubElement {

	function display(&$field_markup,&$subscript_markup) {
		global $zingPrompts,$lang;

		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$editor=$e->populated_value['element_'.$e->id.'_'.($i+2)];

		$field_markup.='<script type="text/javascript" src="' . ZING_APPS_PLAYER_URL . 'js/sub.language_control.jquery.js"></script>';
		$field_markup.='<script type="text/javascript" language="javascript">';
		$field_markup.='jQuery(document).ready(function() { appsLanguageControl.init(\''.'element_'.$e->id.'_'.$i.'\',\''.'helper_'.$e->id.'_'.$i.'\',\''.'element_'.$e->id.'_'.($i+1).'\',\''.$editor.'\'); } );';
		$field_markup.='</script>';

		if ($editor) {
			$use_wysiwyg=1;
			if (defined("ZING_DIR")) require(ZING_DIR.'/addons/tinymce/tinymce.inc');
			else require(dirname(__FILE__).'/../../addons/tinymce/tinymce.inc');
		}
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<select id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" style=\"width: {$xmlf->fields->{'field'.$i}->width}\" {$e->readonly}/>";
		$option_markup="";
		foreach ($zingPrompts->langs as $language => $label) {
			if ($language==$lang) $option_markup.="<OPTION VALUE=\"".$language."\" SELECTED>".$label.'</OPTION>';
			else $option_markup.="<OPTION VALUE=\"".$language."\">".$label.'</OPTION>';
		}

		$field_markup.=$option_markup;
		$field_markup.="</select>";

		$field_markup.="<div id=\"helper_{$e->id}_{$i}\" name=\"helper_{$e->id}_{$i}\" style=\"display:none\" >{$e->populated_value['element_'.$e->id.'_'.($i+1)]}</div>";

		$subscript_markup.="<label for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";

	}
}
?>