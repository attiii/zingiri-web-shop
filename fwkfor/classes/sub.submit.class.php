<?php
class submitZfSubElement extends zfSubElement {
	function onSubmitActions() {
		$e=$this->element;
		$mailTo=$e->populated_value['element_'.$e->id.'_3'];
		if ($mailTo) {
			return array('action' => 'mailto','to' => $mailTo);
		}
	}

	function display(&$field_markup,&$subscript_markup) {
		if (($this->mode == 'view') || ($this->mode == 'delete')) return;
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;

		$submit=isset($e->populated_value['element_'.$e->id.'_2']) ? $e->populated_value['element_'.$e->id.'_2'] : null;
		if (!$submit) $submit='Submit';
		$field_markup.="<input id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element submit\" size=\"{$this->size}\" value=\"$submit\" type=\"submit\" />";
		$subscript_markup.="";
	}
}
?>