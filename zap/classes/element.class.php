<?php
/*  element.class.php
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
class element {
	var $constraint;
	var $is_error;
	var $error_message;
	var $is_required;
	var $id;
	var $guidelines;
	var $default_value;
	var $populated_value=array();
	var $hidden;
	var $title;
	var $name=array();
	var $format=array();
	var $cat=array();
	var $xmlf;
	var $label;
	var $sublabel=array();
	var $links=array();
	var $linksin=array();
	var $input=array();
	var $output=array();
	var $is_searchable;

	function element($constraint) {
		$this->constraint=$constraint;
		$xmlf=faces_get_xml($this->constraint);
		$this->xmlf=$xmlf;
		$this->fields=$xmlf->fields->attributes()->count;
		//$this->name=(string)$xmlf->name;
		$this->name=array();
		$this->sublabel=array();
		for ($i=1; $i<=$this->fields; $i++) {
			if ($this->fields > 1)
			{
				$this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
				$this->sublabel[$i]=(string)$this->xmlf->fields->{'field'.$i}->label;
			}
			$this->format[$i]=(string)$this->xmlf->fields->{'field'.$i}->format;
			if (isset($this->xmlf->fields->{'field'.$i}->cat)) $this->cat[$i]=(string)$this->xmlf->fields->{'field'.$i}->cat;
			if (isset($this->xmlf->fields->{'field'.$i}->links)) {
				foreach ($this->xmlf->fields->{'field'.$i}->links->children() as $link) {
					$this->links[$i]['label']=(string)$link;
					$this->links[$i]['type']=(string)$link->attributes()->type;
					$this->links[$i]['id']=(string)$link->attributes()->id;
				}
			}

		}


	}

	function verify($input,&$output) {
		$success=true;
		$this->is_error=false;
		$this->input=$input;
		
		//check rule conditions
		if (!$this->postRules()) $success=false;
		if ($this->disabled) return true;
		
		for ($i=1; $i<=$this->fields; $i++) {
			$int=$ext=$this->input['element_'.$this->id.'_'.$i];

			$type=$this->xmlf->fields->{'field'.$i}->type;
			if ($this->fields > 1)
			$this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
			$zfclass="zf".$type;

			if (class_exists($type."ZfSubElement"))	{ $c=$type."ZfSubElement"; }
			else { $c="zfSubElement"; }

			$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i);
			if (!$subelement->verifyall())
			{
				$success=false;
				$this->error_message=$subelement->error_message;
				$this->is_error=$subelement->is_error;
			}

			$output['element_'.$this->id.'_'.$i]=$subelement->int;
		}
		
		return $success;

	}
	
	function postRules() {
		$success=true;
		if (!is_array($this->rules) || count($this->rules) == 0) return $success;	
		foreach ($this->rules as $rule) {
			$r='zf'.$rule['type'];
			$n=new $r();
			$n->postcheck($this,$rule['parameters']);
			if (!$n->result) {
				$success=false;
				$this->error_message=$n->error_message;
				$this->is_error=true;
			}
		}
		return $success;
	}
	

	function output($input,&$output,$mode="edit") {
		$success=true;
		$this->is_error=false;

		for ($i=1; $i<=$this->fields; $i++) {

			$int=$ext=$input['element_'.$this->id.'_'.$i];

			$type=$this->xmlf->fields->{'field'.$i}->type;
			if ($this->fields > 1)
			$this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
			if (class_exists($type."ZfSubElement"))	{ $c=$type."ZfSubElement"; }
			else { $c="zfSubElement"; }
			$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i);
			$ext=$subelement->output($mode,$input);

			$output['element_'.$this->id.'_'.$i]=$ext;

		}
		return $success;

	}

	function prepare() {
		$success=true;
		for ($i=1; $i<=$this->fields; $i++) {

			$int=$ext=$input['element_'.$this->id.'_'.$i];

			$type=$this->xmlf->fields->{'field'.$i}->type;
			if ($this->fields > 1)
			$this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
			if (class_exists($type."ZfSubElement"))	{ $c=$type."ZfSubElement"; }
			else { $c="zfSubElement"; }
			$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i);
			$ext=$subelement->prepare();
		}
		return $success;
	}

	function preRules() {
		if (!is_array($this->rules) || count($this->rules) == 0) return;	
		foreach ($this->rules as $rule) {
			$r='zf'.$rule['type'];
			$n=new $r();
			$n->precheck($this,$rule['parameters']);
		}
	}
	
	function display($mode="edit") {

		Global $facesdefaultvalues;

		if ($mode=="search" && !$this->is_searchable) return;
		
		$this->preRules();
		
		if ($this->disabled) return "";
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;

		$xmlf=$this->xmlf;

		$fields=$xmlf->fields->attributes()->count;

		if(!empty($this->is_error)){
			//$error_class = 'class="error"';
			$error_class = 'zferror';
			//$error_message = "<p class=\"error\">{$this->error_message}</p>";
			$error_message = "<p>{$this->error_message}</p>";
		}

		//check for required
		if($this->is_required){
			$span_required = "<span id=\"required_{$this->id}\" class=\"zfrequired\">*</span>";
		}

		//check for guidelines
		if(!empty($this->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$this->id}\"><small>{$this->guidelines}</small></p>";
		}

		if ($this->readonly) $this->readonly="READONLY";
		if ($mode!="edit" && $mode!="add" && $mode!="search") $this->readonly="READONLY";

		if (is_numeric($xmlf->width)) { $width=$xmlf->width; } else { $width="100%"; }
		$width="width:".$width.";";

		if (isset($this->x) && isset($this->y))
		$position="position:absolute;left:".$this->x."px;top:".$this->y."px;";
		else
		$position="";

		$element_markup = $style_markup;

		if (!empty($_POST['zf_label'])) $label=$_POST['zf_label'];
		elseif (!empty($this->title)) $label=$this->title;
		else $label=$xmlf->name;
		if (defined("ZING_APPS_TRANSLATE")) {
			$tempfunc=ZING_APPS_TRANSLATE;
			$label=$tempfunc($label);
		}
		
		if ($this->hidden) $hidden='display:none;'; else $hidden="";
		//$error_class='class="zfelement"';
		$element_markup.= <<<EOT
		<div id="zf_{$this->id}" class="zfelement {$error_class}" style="{$position}{$hidden}">
EOT;
		if ($xmlf->attributes()->header == "none") { $label=""; }
		$element_markup.= <<<EOT
		<label id="zf_{$this->id}_name" class="zfelabel">{$label} {$span_required}</label>
EOT;


		$element_markup.='<div class="zfsubelements" id="zf_'.$this->id.'_sf">';
		for ($i=1; $i<=$fields; $i++) {

			$fn=$xmlf->fields->{'field'.$i}->name;
			if ($fields>1) $this->name[$i]=(string)$fn;
			$size=$xmlf->fields->{'field'.$i}->size;
			$type=$xmlf->fields->{'field'.$i}->type;

			$subscript_markup = '';
			$field_markup ="<div id=\"zf_{$this->id}_{$fn}\" style=\"width: {$xmlf->fields->{'field'.$i}->width}\" class=\"zfsub\">";
			if (isset($xmlf->fields->{'field'.$i}->values) && ($values=$xmlf->fields->{'field'.$i}->values->attributes()->type == "sql")) {
					//sql
					$e=new sqlZfSubElement("","",$xmlf,$this,$i);
					$e->display($field_markup,$subscript_markup);

				} elseif ($values=$xmlf->fields->{'field'.$i}->type == "checkbox") {
					//checkbox
					if(!empty($this->populated_value['element_'.$this->id.'_'.$i])){
						$checked = 'checked="checked"';
					}elseif(!empty($this->populated_value['element_'.$this->id])){
						$checked = 'checked="checked"';
					}else{
						$checked = '';
					}
					$option_markup .= "<input id=\"element_{$this->id}_{$i}\" name=\"element_{$this->id}_{$i}\" class=\"element checkbox\" type=\"checkbox\" value=\"1\" {$checked} {$this->readonly}/>\n";
					//$option_markup .= "<label class=\"choice\" for=\"element_{$this->id}_{$i}\">{$this->title}</label>\n";
					$field_markup.=$option_markup;
					if (!empty($xmlf->fields->{'field'.$i}->label)) $subscript_markup.="<label for=\"element_{$this->id}_{$i}\">{$xmlf->fields->{'field'.$i}->label}</label>";
			} elseif ($values=$xmlf->fields->{'field'.$i}->type == "password") {
						//password
						//$field_markup.="<input id=\"element_{$this->id}_{$i}\" name=\"element_{$this->id}_{$i}\" class=\"element text\" style=\"width: {$xmlf->fields->{'field'.$i}->width}\" value=\"{$this->populated_value['element_'.$this->id.'_'.$i]}\" maxlength=\"{$xmlf->fields->{'field'.$i}->maxlength}\" type=\"password\" {$this->readonly}/>";
						$field_markup.="<input id=\"element_{$this->id}_{$i}\" name=\"element_{$this->id}_{$i}\" class=\"element text\" value=\"{$this->populated_value['element_'.$this->id.'_'.$i]}\" maxlength=\"{$xmlf->fields->{'field'.$i}->maxlength}\" type=\"password\" {$this->readonly}/>";
						//$field_markup.="<input id=\"element_{$this->id}_{$i}_repeat\" name=\"element_{$this->id}_{$i}_repeat\" class=\"element text\" value=\"{$this->populated_value['element_'.$this->id.'_'.$i]}\" maxlength=\"{$xmlf->fields->{'field'.$i}->maxlength}\" type=\"password\" {$this->readonly}/>";
						$subscript_markup.="<label for=\"element_{$this->id}_{$i}\">{$xmlf->fields->{'field'.$i}->label}</label>";

			} elseif ($values=$xmlf->fields->{'field'.$i}->type == "radio") {
							//radio
							$default=trim($this->populated_value['element_'.$this->id.'_'.$i]);
							//		echo "def=".$default."=<br />";
							foreach ($xmlf->fields->{'field'.$i}->values->children() as $key => $option) {
								//this->populated_value['element_'.$this->id.'_'.$i]

								$value=$xmlf->fields->{'field'.$i}->values->{$key}->attributes()->value;

								if ($value==$default) { $checked="checked"; } else { $checked=""; }

								//				echo "--".$key."=".$option."=".$value."=".$checked."<br />";
								$field_markup.="<input id=\"element_{$this->id}_{$i}\" name=\"element_{$this->id}_{$i}\" class=\"element text\" value=\"{$value}\" type=\"radio\" {$this->readonly} {$checked}/>{$option}<br />";
							}
							$subscript_markup.="<label for=\"element_{$this->id}_{$i}\">{$xmlf->fields->{'field'.$i}->label}</label>";


			} elseif ($values=$xmlf->fields->{'field'.$i}->type == "textarea") {
								//textarea
								$size=$xmlf->fields->{'field'.$i}->size;
								$sizes=explode(",",$size);
								if (!is_numeric($sizes[0])) $sizes[0]=40;
								if (!is_numeric($sizes[1])) $sizes[1]=3;
								$field_markup.="<textarea id=\"element_{$this->id}_{$i}\" name=\"element_{$this->id}_{$i}\" class=\"element text\" cols=\"{$sizes[0]}\" rows=\"{$sizes[1]}\" {$this->readonly}>{$this->populated_value['element_'.$this->id.'_'.$i]}</textarea>";
								$subscript_markup.="<label id=\"label_{$this->id}_{$i}\"for=\"element_{$this->id}_{$i}\">".$xmlf->fields->{'field'.$i}->label."</label>";

			} elseif ($values=$xmlf->fields->{'field'.$i}->type == "submit") {
								//submit
								if($this->populated_value['element_'.$this->id.'_'.$i] == ""){
									$this->populated_value['element_'.$this->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
								}
								$field_markup.="<input id=\"element_{$this->id}_{$i}\" name=\"element_{$this->id}_{$i}\" class=\"element text\" value=\"".$xmlf->fields->{'field'.$i}->name."\" type=\"submit\" onclick=\"form.action=location.href;\" {$this->readonly}/>";
								$subscript_markup.="";

			} elseif ($values=$xmlf->fields->{'field'.$i}->type == "domain") {
								//domain
								$e=new domainZfSubElement("","",$xmlf,$this,$i);
								$e->display($field_markup,$subscript_markup);
			} else {
				//default
				if (class_exists($type."ZfSubElement")) {
					$c=$type."ZfSubElement";
					$e=new $c("","",$xmlf,$this,$i);
					if (method_exists($e,"display")) {
						$e->display($field_markup,$subscript_markup);
					}
				} else {
					if($this->populated_value['element_'.$this->id.'_'.$i] == ""){
						$this->populated_value['element_'.$this->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
					}
					$field_markup.="<input id=\"element_{$this->id}_{$i}\" name=\"element_{$this->id}_{$i}\" class=\"element text\" size=\"{$xmlf->fields->{'field'.$i}->size}\" value=\"{$this->populated_value['element_'.$this->id.'_'.$i]}\" maxlength=\"{$xmlf->fields->{'field'.$i}->maxlength}\" type=\"text\" {$this->readonly}/>";
					$subscript_markup.="<label id=\"label_{$this->id}_{$i}\"for=\"element_{$this->id}_{$i}\">".$xmlf->fields->{'field'.$i}->label."</label>";
				}
			}
			if ($xmlf->fields->{'field'.$i}->subscript != "none" && $xmlf->fields->{'field'.$i}->label != "") {
				$field_markup.=$subscript_markup;
			}
			$field_markup.="</div>";
			if ($xmlf->fields->{'field'.$i}->cat != 'parameter' || $mode=='editor' || isset($_POST['zf_type'])) { 
				$element_markup.=$field_markup;
			}
		}
		$element_markup.='<div class="zfclear"></div>';
		$element_markup.='</div>'.$error_message.'<div class="zfclear">';

		$element_markup.= <<<EOT
	&nbsp;{$guidelines} 
		</div>
EOT;


								return $element_markup;
							}



						}
						?>