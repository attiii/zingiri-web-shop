<?php
/*  element.class.php
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
	var $attributes=array();
	var $mode;
	var $disabled=false;
	var $onSubmitActions=array();
	var $showLabels=true;
	var $settings;
	var $parameters;
	var $formAttributes;
	var $isRepeatable;
	var $displayGuidelines=true;

	function element($constraint) {
		$this->constraint=$constraint;
		$xmlf=faces_get_xml($this->constraint);
		$this->xmlf=$xmlf;
		$this->settings=$xmlf->settings;
		$this->fields=isset($xmlf->fields->attributes()->count) ? $xmlf->fields->attributes()->count : $xmlf->fields->count();

		$this->name=array();
		$this->sublabel=array();
		$this->help=z_((string)$xmlf->help);

		for ($i=1; $i<=$this->fields; $i++) {
			if ($this->fields > 1)
			{
				$this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
				$this->sublabel[$i]=(string)$this->xmlf->fields->{'field'.$i}->label;
				if ($this->sublabel[$i]=='%') {
					$type=$this->xmlf->fields->{'field'.$i}->type;
					if (class_exists($type."ZfSubElement"))	{ $c=$type."ZfSubElement"; }
					else { $c="zfSubElement"; }
					$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i);
					$this->sublabel[$i]=$subelement->getlabel($this->sublabel[$i]);
				}
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

	function verify($input,&$output,$mode,$before) {

		$success=true;
		$this->is_error=false;
		$this->input=$input;
		$this->mode=$mode;
		//check rule conditions
		if (!$this->postRules()) $success=false;
		if ($this->disabled) return true;

		for ($i=1; $i<=$this->fields; $i++) {
			if ($this->xmlf->fields->{'field'.$i}->cat=='parameter') continue;

			$int=$ext=isset($this->input['element_'.$this->id.'_'.$i]) ? $this->input['element_'.$this->id.'_'.$i] : '';
			$type=$this->xmlf->fields->{'field'.$i}->type;
			if ($this->fields > 1)
			$this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
			$zfclass="zf".$type;

			if (class_exists($type."ZfSubElement"))	{ $c=$type."ZfSubElement"; }
			else { $c="zfSubElement"; }

			if (is_array($int)) {
				$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i);
				$subelement->parameters=$this->parameters;
				
				if ($subelement->isDataSet) {
					//					if (!$this->attributes['zfrepeatable']) $output['element_'.$this->id.'_'.$i]=serialize($output['element_'.$this->id.'_'.$i]);
					if (!$subelement->verifyall($this->mode,$before))
					{
						$success=false;
						$this->error_message=$subelement->error_message;
						$this->is_error=$subelement->is_error;
					}
					$output['element_'.$this->id.'_'.$i]=$subelement->int;
					if ($success) {
						$onSubmitActions=$subelement->onSubmitActions();
						if ($onSubmitActions) $this->onSubmitActions[]=$onSubmitActions;
					}
				} else {
					foreach ($int as $s => $v) {
						$subelement=new $c($int[$s],$ext[$s],$this->xmlf->fields->{'field'.$i},$this,$i);
						if (!$subelement->verifyall($this->mode,$before))
						{
							$success=false;
							$this->error_message=$subelement->error_message;
							$this->is_error=$subelement->is_error;
						}
						$output['element_'.$this->id.'_'.$i][$s]=$subelement->int;
						if ($success) {
							$onSubmitActions=$subelement->onSubmitActions();
							if ($onSubmitActions) $this->onSubmitActions[]=$onSubmitActions;
						}
					}
				}
			} else {
				$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i);
				if (!$subelement->verifyall($this->mode,$before))
				{
					$success=false;
					$this->error_message=$subelement->error_message;
					$this->is_error=$subelement->is_error;
				}
				$output['element_'.$this->id.'_'.$i]=$subelement->int;
				if ($success) {
					$onSubmitActions=$subelement->onSubmitActions();
					if ($onSubmitActions) $this->onSubmitActions[]=$onSubmitActions;
				}
			}

		}
		return $success;
	}

	function postSave($input,$output,$recid) {

		$success=true;
		$this->is_error=false;
		$this->input=$input;

		for ($i=1; $i<=$this->fields; $i++) {
			$int=$ext=isset($this->input['element_'.$this->id.'_'.$i]) ? $this->input['element_'.$this->id.'_'.$i] : '';

			$type=$this->xmlf->fields->{'field'.$i}->type;
			if ($this->fields > 1)
			$this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
			$zfclass="zf".$type;

			if (class_exists($type."ZfSubElement"))	{ $c=$type."ZfSubElement"; }
			else { $c="zfSubElement"; }

			$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i);
			if (!$subelement->postSave($recid))
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

		$this->mode=$mode;
		$success=true;
		$this->is_error=false;

		for ($i=1; $i<=$this->fields; $i++) {

			if (isset($input['element_'.$this->id.'_'.$i]) && is_array($input['element_'.$this->id.'_'.$i])) {
				$ac=count($input['element_'.$this->id.'_'.$i]);
				for ($ai=0; $ai < $ac; $ai++) {
					$int=$ext=isset($input['element_'.$this->id.'_'.$i][$ai]) ? $input['element_'.$this->id.'_'.$i][$ai] : '';
					$type=$this->xmlf->fields->{'field'.$i}->type;
					if ($this->fields > 1) $this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
					if (class_exists($type."ZfSubElement"))	{ $c=$type."ZfSubElement"; }
					else { $c="zfSubElement"; }
					$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i,$ai);
					$subelement->parameters=$this->parameters;
					$ext=$subelement->output($mode,$input);
					$output['element_'.$this->id.'_'.$i][$ai]=$ext;
				}
			} else {
				$int=$ext=isset($input['element_'.$this->id.'_'.$i]) ? $input['element_'.$this->id.'_'.$i] : '';
				$type=$this->xmlf->fields->{'field'.$i}->type;
				if ($this->fields > 1) $this->name[$i]=(string)$this->xmlf->fields->{'field'.$i}->name;
				if (class_exists($type."ZfSubElement"))	{ $c=$type."ZfSubElement"; }
				else { $c="zfSubElement"; }
				$subelement=new $c($int,$ext,$this->xmlf->fields->{'field'.$i},$this,$i);
				$subelement->parameters=$this->parameters;
				$ext=$subelement->output($mode,$input);
				$output['element_'.$this->id.'_'.$i]=$ext;
			}

		}
		return $success;

	}

	function prepare() {
		$success=true;
		for ($i=1; $i<=$this->fields; $i++) {

			$int=$ext=isset($input['element_'.$this->id.'_'.$i]) ? $input['element_'.$this->id.'_'.$i] : '';

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
		if (!isset($this->rules) || !is_array($this->rules) || count($this->rules) == 0) return;
		foreach ($this->rules as $rule) {
			$r='zf'.$rule['type'];
			$n=new $r();
			$n->precheck($this,$rule['parameters']);
		}
	}

	function display($mode="edit") {
		global $facesdefaultvalues;
		$this->mode=$mode;

		if ($mode=="search" && !$this->is_searchable) return;

		$this->preRules();

		if (isset($this->disabled) && $this->disabled) return "";
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;

		$xmlf=$this->xmlf;

		$fields=$this->fields; //$xmlf->fields->attributes()->count;

		if(!empty($this->is_error)){
			$error_class = 'zferror';
			$error_message = "<p>{$this->error_message}</p>";
		}

		//check for required
		if($this->is_required){
			$span_required = "<span id=\"required_{$this->id}\" class=\"zfrequired\">*</span>";
		}

		if (isset($this->readonly) && $this->readonly) $this->readonly="READONLY";
		if ($mode!="edit" && $mode!="add" && $mode!="search" && $mode!="build") $this->readonly="READONLY";

		if (is_numeric($xmlf->width)) { $width=$xmlf->width; } else { $width="100%"; }
		$width="width:".$width.";";

		if (isset($this->x) && isset($this->y))
		$position="position:absolute;left:".$this->x."px;top:".$this->y."px;";
		else
		$position="";

		$element_markup='';

		if (!empty($_POST['zf_label'])) $label=$_POST['zf_label'];
		elseif (!empty($this->title)) $label=$this->title;
		else $label=$xmlf->name;

		//check for guidelines
		if(empty($this->attributes['zfguidelines']) && $label && function_exists('h_')){
			$this->guidelines=h_($label);
		}
		if (!empty($this->attributes['zfguidelines'])){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$this->id}\"><small>{$this->attributes['zfguidelines']}</small></p>";
		}

		$jsRule=array();
		if (isset($this->rules) && is_array($this->rules) && count($this->rules) > 0) {
			foreach ($this->rules as $rule) {
				if ($rule['type']=='rule_formfield') {
					$ruleFields=explode(',',$rule['parameters'][0]);
					if (!isset($ruleFields[1])) $ruleFields[1]=1;
					$ruleParameters['action']=$rule['parameters'][5];
					$ruleParameters['field']=$ruleFields[0];
					$ruleParameters['subField']=$ruleFields[1];
					$aParams=explode(",",$rule['parameters'][2]);
					if (count($aParams)==2) {
						$ruleParameters['fnct']=$aParams[0];
						$ruleParameters['params']=$aParams[1];
					} elseif (count($aParams)==3) {
						$ruleParameters['class']=$aParams[0];
						$ruleParameters['fnct']=$aParams[1];
						$ruleParameters['params']=$aParams[2];
					}
					$ruleParameters['condition']=$rule['parameters'][3];
					$ruleParameters['compare']=$rule['parameters'][4];

					$jsRule[]=array($ruleParameters,$this->id);
				}
			}
		}

		if ($this->hidden) $hidden='display:none;'; else $hidden="";
		$element_markup.= '<div data-field="'.(isset($this->column[$this->id]) ? $this->column[$this->id] : '').'" id="zf_'.$this->id.'" class="zfelement '.$error_class.'" style="'.$position.$hidden.'">';
		if ($xmlf->attributes()->header == "none") { $label=""; }
		if ($this->showLabels) $element_markup.= '<label id="zf_'.$this->id.'_name" class="zfelabel '.(isset($this->formAttributes->labelposition) ? $this->formAttributes->labelposition : 'left').'">'.z_($label).' '.$span_required.'</label>';
		$element_markup.='<div class="zfsubelements" id="zf_'.$this->id.'_sf">';
		$ac=1;
		for ($i=1; $i<=$fields; $i++) {
			if (isset($this->isRepeatable) && $this->isRepeatable) $ac=max($ac,count($this->populated_value['element_'.$this->id.'_'.$i]));
		}
		for ($i=1; $i<=$fields; $i++) {
			$type=$xmlf->fields->{'field'.$i}->type;
			$fn=$xmlf->fields->{'field'.$i}->name ? $xmlf->fields->{'field'.$i}->name : $type;
			if ($fields>1) $this->name[$i]=(string)$fn;

			$subscript_markup = '';
			$field_markup ="<div id=\"zf_{$this->id}_{$fn}\" style=\"width: {$xmlf->fields->{'field'.$i}->width}\" class=\"zfsub $type".($this->isRepeatable ? ' zfrepeatable' : '')."\">";
			//			if (isset($this->isRepeatable) && $this->isRepeatable) $ac=max($ac,count($this->populated_value['element_'.$this->id.'_'.$i]));
			//default
			for ($ai=0; $ai < $ac; $ai++) {
				if (!empty($type) && class_exists($type."ZfSubElement")) $c=$type."ZfSubElement";
				else $c="zfSubElement";
				$e=new $c("","",$xmlf,$this,$i,$ai);
				$e->parameters=$this->parameters;
				if (method_exists($e,"display")) {
					$e->mode=$this->mode;
					if ($xmlf->fields->{'field'.$i}->order) $e->order=$xmlf->fields->{'field'.$i}->order;
					if ($mode=='print') $e->printout($field_markup,$subscript_markup);
					else $e->display($field_markup,$subscript_markup);
					$this->divider=isset($e->divider) ? $e->divider : null;
				}
				if ($ac > 1 && $ai!=$ac-1) $subscript_markup='';
			}
			if ($xmlf->fields->{'field'.$i}->subscript != "none" && $xmlf->fields->{'field'.$i}->label != "") {
				$field_markup.=$subscript_markup;
			}
			$field_markup.="</div>";
			if ($xmlf->fields->{'field'.$i}->cat != 'parameter' || $mode=='editor' || isset($_POST['zf_type'])) {
				$element_markup.=$field_markup;
			}
			}
			if (isset($this->attributes['zfrepeatable']) && $this->attributes['zfrepeatable']) {
				$element_markup.='<div id="zfc_'.$this->id.'_del" class="zftablecontrol" style="float:left;position:relative;">';
				for ($ai=1; $ai <= $ac; $ai++) {
					$element_markup.='<input type="button" data-pos="'.$ai.'" id="zfci_'.$this->id.'_del_'.$ai.'" class="zfrepeatable_del" onclick="appsRepeatable.del(\''.$this->id.'\','.$ai.')" value="-" height="16px"/>';
					//if ($ac > 1 && $ai < $ac) $element_markup.='<br />';
				}
				$element_markup.='</div>';
				$element_markup.='<div id="zfc_'.$this->id.'_add" class="zftablecontrol" style="float:left;position:relative;">';
				for ($ai=1; $ai <= $ac; $ai++) {
					$element_markup.='<input type="button" data-pos="'.$ai.'" id="zfci_'.$this->id.'_add_'.$ai.'" class="zfrepeatable_add" onclick="appsRepeatable.add(\''.$this->id.'\','.$ai.')" value="+" height="16px"/>';
					//if ($ac > 1 && $ai < $ac) $element_markup.='<br />';
				}
				$element_markup.='</div>';
				$this->includeJavascript($this->id);
			}
			$element_markup.='<div class="zfclear"></div>';
			$element_markup.='</div>'.$error_message;
			$element_markup.='<div class="zfclear"></div>';

			if ($this->displayGuidelines) $element_markup.=$guidelines;
			$element_markup.='</div>';
			return array('markup' => $element_markup,'jsrule' => $jsRule);
		}

		function includeJavaScript($id) {
			?>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function() {
	    appsRepeatable.init('<?php echo $id;?>');
	});
</script>
			<?php
		}

	}

	?>