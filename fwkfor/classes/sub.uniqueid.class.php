<?php
class uniqueidZfSubElement extends zfSubElement {
	
	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		
		if ($this->mode=='add') $e->values['element_'.$e->id.'_'.$i][$this->ai]=uniqid();
	
		parent::display($field_markup,$subscript_markup);
	}
}
