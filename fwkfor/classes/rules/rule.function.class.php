<?php
class zfrule_function extends zfrule {
	
	function precheck(&$e,$parameters) {
		$f=explode(",",$parameters[0],2);
		$this->compare=$parameters[1];
		$this->reference=$parameters[2];
		$this->action=$parameters[3];
		if (function_exists($f[0])) {
			$this->v=isset($f[1]) ? $f[0]($f[1]) : $f[0]();
		}
		if ($this->compare($this->v,$this->reference,$this->compare)) {
			$this->action($e,$this->action);
		}
	}

	
	function postcheck(&$e,$parameters) {
		$input=$e->input;
		$f=explode(",",$parameters[0],2);
		$compare=$parameters[1];
		$reference=$parameters[2];
		$action=$parameters[3];
		if ($f == 'age') {
			$value=date('Y')-$input['element_'.$e->id.'_1'];
			if (date('md') < $input['element_'.$e->id.'_2'].$input['element_'.$e->id.'_3'] && $value != 0) $value--;
			$message='Age check failed';
		} elseif (function_exists($f[0])) {
			$value=$f[0]($f[1]);
		}
		
		$compareResult=$this->compare($value,$reference,$compare);
		if ((!$compareResult && $action=='required') || ($compareResult && $action=='notallowed')) {
			$this->result=false;
			$this->error_message=$message;
		} elseif (($compareResult && $action=='disable')) {
			$this->action($e,$action);
		} elseif (($compareResult && $action=='unique')) {
			$this->action($e,$action);
		}
		
		return $this->result;
	}
}
?>