<?php
class shipping_calculatorZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		foreach ($this->shippingOptions() as $key => $option) {
			if ($key==$this->int) $this->ext=$option;
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
		if ($this->mode != 'build') $onChange='onChange="this.form.action=\''.zurl('?page=shippingadmin&action=show_shipping').'\';this.form.submit();"';
		else $onChange='';
		$field_markup.="<select $onChange id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" style=\"width: {$xmlf->fields->{'field'.$i}->width}\" {$e->readonly}/>";
		$option_markup="";
		foreach ($this->shippingOptions() as $key => $option) {
			$selected="";
			if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == trim($key)) {
				$selected = 'selected="selected"';
			}
			if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$key."\" {$selected}>".$option."</option>";
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";

	}

	function shippingOptions() {
		$options=array('FR'=>z_('Flat rate'),'WR'=>z_('Weight based rate'),'TR'=>z_('Price based rate'));
		$options['QR']=z_('Quantity based rate');
		if (class_exists('wsShippingUSPS')) $options['USPS']='USPS';
		if (class_exists('wsShippingUPS')) $options['UPS']='UPS';
		return $options;
	}
}