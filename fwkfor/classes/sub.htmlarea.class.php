<?php
/*  sub.htmlarea.class.php
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
class htmlareaZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		return $this->ext;
	}

	function verify()
	{
		$this->int=stripslashes($this->ext);
		return true;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		$use_wysiwyg=1;
		if (!defined("ZING_AJAX") || !ZING_AJAX) {
			if ($this->mode!='edit' && defined('ZING_CMS') && ZING_CMS=='wp') {} //do nothing
			elseif ($this->mode!='edit' && defined('APHPS_HTML_EDITOR') && APHPS_HTML_EDITOR=='wordpress_client') {
				/*
				$field_markup.='<script type="text/javascript">';
				$field_markup.='jQuery(document).ready(function() {';
				$field_markup.="edCanvas = document.getElementById('element_".$e->id."_".$i."');";
				$field_markup.='});';
				$field_markup.='</script>';
				*/
			} elseif ($this->mode!='edit' && defined("ZING_DIR")) require(ZING_DIR.'/addons/tinymce/tinymce.inc');
			elseif ($this->mode!='edit') require(dirname(__FILE__).'/../../addons/tinymce/tinymce.inc');
		}
		$size=$xmlf->fields->{'field'.$i}->size;
		$sizes=explode(",",$size);
		if (!is_numeric($sizes[0])) $sizes[0]=40;
		if (!isset($sizes[1]) || !is_numeric($sizes[1])) $sizes[1]=3;

		if (!defined("ZING_AJAX") || !ZING_AJAX) {
			if ($this->mode!='build' && defined('ZING_CMS') && ZING_CMS=='wp') {
				$field_markup.='<div id="poststuff">';
				ob_start();
				the_editor($e->populated_value['element_'.$e->id.'_'.$i],"element_".$e->id."_".$i,'title',true,2,true);
				$field_markup.=ob_get_clean();
				$field_markup.='</div>';
			} elseif ($this->mode!='build' && defined('APHPS_HTML_EDITOR') && APHPS_HTML_EDITOR=='wordpress_client') {
				$field_markup.='<div id="poststuff">';
				$field_markup.="<textarea id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"theEditor element text\" cols=\"{$sizes[0]}\" rows=\"{$sizes[1]}\" {$e->readonly}>{$e->populated_value['element_'.$e->id.'_'.$i]}</textarea>";
				$field_markup.='</div>';
				$field_markup.='<input id="title" type="hidden" size="40" value="(no subject)" name="msgsubject">';
			} else {
				$field_markup.="<textarea id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"tinyMce element text\" cols=\"{$sizes[0]}\" rows=\"{$sizes[1]}\" {$e->readonly}>{$e->populated_value['element_'.$e->id.'_'.$i]}</textarea>";
			}
		}
		$subscript_markup.="<label id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
}
?>