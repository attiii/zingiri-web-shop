<?php
class zfSubElement {
	var $int;
	var $xmlf;
	var $elementid;
	var $subid;
	var $error_message;
	var $is_error;
	var $element;
	var $populated_column=array();
	var $submit=null;
	var $size=12;
	var $name;
	var $isDataSet=false;
	
	function zfSubElement($int,$ext="",$xmlf="",$element="",$subid="",$ai=0) {
		$this->int=$int;
		$this->xmlf=$xmlf;
		$this->elementid=$element->id;
		$this->element=$element;
		if (!isset($this->element->readonly)) $this->element->readonly='';
		$this->subid=$subid;
		if (is_array($ext)) $this->ext=$ext;
		else $this->ext=trim($ext);
		$this->error_message="";
		$this->is_error=false;
		if (isset($element->attributes['zfsize']) && !empty($element->attributes['zfsize'])) $this->size=$element->attributes['zfsize'];
		elseif (!empty($xmlf->fields->{'field'.$this->subid}->size)) $this->size=$xmlf->fields->{'field'.$this->subid}->size;
		
		if (isset($element->attributes['zfmaxlength'])) $this->maxlength=$element->attributes['zfmaxlength'];
		elseif (isset($xmlf->fields->{'field'.$this->subid}->maxlength)) $this->maxlength=$xmlf->fields->{'field'.$this->subid}->maxlength;
		else $this->maxlength=null;
		
		$this->ai=$ai;
		$this->ail='';
		$this->name='element_'.$this->element->id.'_'.$this->subid;
		if (!isset($element->populated_value['element_'.$element->id.'_'.$subid])) $element->populated_value['element_'.$element->id.'_'.$subid]=null;		
		if (isset($element->isRepeatable) && $element->isRepeatable) {
			if ($ai>0) $this->ail='_'.$this->ai;
			$element->values['element_'.$element->id.'_'.$subid]=$element->populated_value['element_'.$element->id.'_'.$subid];
		} else {
			$element->values['element_'.$element->id.'_'.$subid][0]=$element->populated_value['element_'.$element->id.'_'.$subid];
		}
		
	}

	function getLabel($label) {
		return $label;
	}
	
	function prepare() {
	}

	function printout(&$field_markup,&$subscript_markup) {
		$this->display($field_markup,$subscript_markup);
	}
	
	function output($mode="edit",$input="")
	{
		$this->ext=$this->int;
		return $this->ext;
	}

	function verifyall($mode='',$before='')
	{
		$this->mode=$mode;
		$this->before=$before;
		
		if ($this->element->is_required && trim($this->ext)=="") {
			return $this->error("Field is mandatory!");
		} elseif ($this->element->unique && $mode=='add') {
			if ($this->element->entityType == 'DB') {
				$key='element_'.$this->elementid.'_'.$this->subid;
				$field=$this->element->column_map[$key];
				$db=new aphpsDb();
				if ($db->select('select id from ##'.$this->element->entityName.' where '.$field."=".qs($this->int))) {
					return $this->error("Value not allowed!");
				}
			}
		}
		if (isset($this->element->attributes['zfregex']) && $this->element->attributes['zfregex']) {
			$p=preg_match('/'.trim($this->element->attributes['zfregex']).'/',$this->ext,$matches);
			if ($p===false) return $this->error("There is an error in your regular expression");
			elseif ($matches[0]!=$this->ext)return $this->error("Value not allowed!");
		}
		return $this->verify();
	}

	function verify()
	{
		$this->ext=$this->int;
		return true;
	}

	function onSubmitActions() {
		return null;
	}
	
	function error($error_message) {
		$this->error_message=z_($error_message);
		$this->is_error=true;
		return false;
	}

	function postSave($id=0) {
		return true;
	}
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		
		if($e->values['element_'.$e->id.'_'.$i][$this->ai] == ""){
			$e->values['element_'.$e->id.'_'.$i][$this->ai] = $xmlf->fields->{'field'.$i}->default;
		}
		$readonly=isset($e->readonly) ? $e->readonly : '';

		if (in_array($this->mode,array('view'))) {
			$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->values['element_'.$e->id.'_'.$i][$this->ai]}\" maxlength=\"{$this->maxlength}\" type=\"text\" disabled=\"DISABLED\"}/>";
		} elseif (in_array($this->mode,array('print'))) {
			$field_markup.="<div class=\"element text\" >{$e->values['element_'.$e->id.'_'.$i][$this->ai]}</div>";
		} else {
			$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"{$e->values['element_'.$e->id.'_'.$i][$this->ai]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$readonly}/>";
		}
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}

	function createRandomCode($len=16) {
		$chars = "abcdefghijkmnpqrstuvwxyz23456789";
		srand((double)microtime()*1000000);
		$pass = '' ;
		$len++;

		for ($i=0;$i<=$len; $i++) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
		}
	return $pass;
}
	
}
?>