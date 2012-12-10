<?php
class passwordZfSubElement extends zfSubElement {
	
	function output($mode="edit",$input="")
	{
		$this->ext="";
		return $this->ext;
	}
	
	function verify() {
		$method=defined("APHPS_FWKUSR_PASSWORD_METHOD") ? APHPS_FWKUSR_PASSWORD_METHOD : 'md5';

		$loginFieldId=isset($this->element->populated_value['element_'.$this->element->id.'_3']) ? $this->element->populated_value['element_'.$this->element->id.'_3'] : null;
		if ($loginFieldId) {
			$login=isset($this->element->populated_value['element_'.$loginFieldId.'_1']) ? $this->element->populated_value['element_'.$loginFieldId.'_1'] : null;
		}
		$pass1=$this->element->populated_value['element_'.$this->element->id.'_1'];
		if (isset($this->element->populated_value['element_'.$this->element->id.'_2'])) $pass2=$this->element->populated_value['element_'.$this->element->id.'_2'];
		else $pass2=$pass1;
		if (strlen($pass1) > 40) {
			return ($this->error("Password is too long!"));
		} elseif (strstr($pass1,' ')) {
			return ($this->error("Passwords may not contain spaces!"));
		} elseif ($pass1 != $pass2) {
			return ($this->error("Passwords are not matching!"));
		} elseif (function_exists('verifyPasswordStrength') && !verifyPasswordStrength($this,$pass1)) {
			return ($this->error("Password is not strong enough!"));
		} elseif (($method=='digest') && !$login) {
			return $this->error("Login is required!");
		}
		
		if ($method=='digest') $this->int=md5($login.':'.(defined('APHPS_FWKUSR_REALM') ? APHPS_FWKUSR_REALM : 'APHPS').':'.$pass1);
		else $this->int=md5($pass1);
		return true;
	}
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		
		if($e->populated_value['element_'.$e->id.'_'.$i] == ""){
			$e->populated_value['element_'.$e->id.'_'.$i] = $xmlf->fields->{'field'.$i}->default;
		}
		$field_markup.="<input id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" size=\"{$this->size}\" value=\"\" maxlength=\"{$this->maxlength}\" type=\"password\" {$e->readonly}/>";
		$subscript_markup.="<label class=\"subname\" id=\"label_{$e->id}_{$i}\"for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
	
	function verifyStrength($password) {
		return true;
	}
	
}