<?php
class country_isoZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$db=new db();
		$query="select * from ##country where iso=".qs($this->int);
		if ($db->select($query) && $db->next()) $this->ext=$db->get('name_en');
		else $this->ext=$this->int;
		return $this->ext;
			
	}

	function display(&$field_markup,&$subscript_markup) {
		$db=new db();
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		$field_markup.="<select id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly}>";
		$option_markup="";

		if ($country=trim($e->populated_value['element_'.$e->id.'_'.$i])) {
			$query="select * from ##country where iso=".qs($country);
			if (!$db->select($query) || !$db->next()) {
				$option_markup .= "<option value=\"".$country."\" selected=\"selected\">".$country."</option>";	
			}
		}
		
		$query='select * from ##country';
		
		$db->select($query);
		while($db->next()) {
			$key=$db->get('iso');
			$option=$db->get('name_en');
			$selected="";
			if(trim($e->populated_value['element_'.$e->id.'_'.$i]) == $key){
				$selected = 'selected="selected"';
			} elseif ($e->default_value == $key) {
				$selected = 'selected="selected"';
			}
			if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$key."\" {$selected}>".$option."</option>";
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";


		}
	}