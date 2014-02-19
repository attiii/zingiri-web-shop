<?php
class selectZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		foreach ($this->xmlf->values->children() as $child) {
			$t=(array)$child;
			$option=isset($t[0]) ? $t[0] : $child->__toString();
			if (isset($child->attributes()->value)) $value=$child->attributes()->value;
			else $value=$option;
			if ($value==$this->int) $this->ext=$option;
		}
		return $this->ext;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		if (in_array($this->mode,array('edit','add','build','search'))) {
			$field_markup.="<select id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" style=\"width: {$xmlf->fields->{'field'.$i}->width}\" {$e->readonly}>";
			$option_markup="";
			if ($xmlf->fields->{'field'.$i}->values->attributes()->type=='multi') {
				foreach ($xmlf->fields->{'field'.$i}->values->children() as $child) {
					$t=(array)$child;

					$option=isset($t[0]) ? $t[0] : $child->__toString();
					if (isset($child->attributes()->value)) $value=$child->attributes()->value;
					else $value=$option;
					$selected="";
					if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == trim($option)) {
						$selected = 'selected="selected"';
					} elseif ($e->populated_value['element_'.$e->id.'_'.$i] == $value) {
						$selected = 'selected="selected"';
					}
					if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$value."\" {$selected}>".$option."</option>";
				}
			} elseif ($xmlf->fields->{'field'.$i}->values->attributes()->type=='range') {
				$start=(int)$xmlf->fields->{'field'.$i}->values->attributes()->start;
				$end=(int)$xmlf->fields->{'field'.$i}->values->attributes()->end;
				for ($option=$start; $option <= $end; $option++) {
					$selected="";
					if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == $option) {
						$selected = 'selected="selected"';
					}
					if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$option."\" {$selected}>".$option."</option>";
				}
			}
			$field_markup.=$option_markup;
			$field_markup.="</select>";
		} else {
			$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text $this->classes\" size=\"{$this->size}\" value=\"{$e->values['element_'.$e->id.'_'.$i][$this->ai]}\" maxlength=\"{$this->maxlength}\" type=\"text\" {$readonly}/>";
		}
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";

		}
	}