<?php
class sqlidZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		if ($mode!='list') {
			$this->ext=$this->int;
			return $this->ext;
		}
		$e=$this->element;
		$key=strtoupper($input['element_'.$e->id.'_2']);
		$value=strtoupper($input['element_'.$e->id.'_3']);
		$table=$input['element_'.$e->id.'_4'];

		$query="select `".$key."`,`".$value."` FROM `##".$table."`"; //` ORDER BY `".$value."`";
		$query.=" where `".$key."`='".$this->int."'";
		$query=str_replace("##",DB_PREFIX,$query);
		$result = do_query($query);
		if ($row = mysql_fetch_array($result)){
			$this->ext=$row[1];
		} else {
			$this->ext="";
		}
		return $this->ext;
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		$key=isset($e->populated_value['element_'.$e->id.'_2']) ? strtoupper($e->populated_value['element_'.$e->id.'_2']) : null;
		$value=isset($e->populated_value['element_'.$e->id.'_3']) ? strtoupper($e->populated_value['element_'.$e->id.'_3']) : null;
		$table=isset($e->populated_value['element_'.$e->id.'_4']) ? $e->populated_value['element_'.$e->id.'_4'] : null;
		
		$field_markup.="<select id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly}>";
		$option_markup="";
		$option_markup='<option value="0"></option>';
		if (!empty($key) && !empty($value)) {
			$query="select `".$key."`,`".$value."` FROM `##".$table."` ORDER BY `".$value."`";
			$query=str_replace("##",DB_PREFIX,$query);

			if (!empty($xmlf->fields->{'field'.$i}->values->where)) {
				$wherefields=explode(",",$xmlf->fields->{'field'.$i}->values->where);
				foreach ($wherefields as $wherefield) {
					$wherevalue="";
					//check if link
					foreach ($e->links as $id => $value) {
						if ($value['id'] == $wherefield) {
							$linkfield=$e->linksin['zflink'.$id];
							$wherevalue=$e->populated_value['element_'.$linkfield.'_1'];
						}
					}
					//check if function
					if (empty($wherevalue) && function_exists($wherefield)) {
						$wherevalue=qs($wherefield());
					}
					if (empty($wherevalue)) { $wherevalue=0; }
					$query=str_replace("$".$wherefield,$wherevalue,$query);
				}
			}
			if ($result = do_query($query)) {
				while($row = mysql_fetch_array($result)){
					$key=$row[0];
					$option=$row[1];
					$selected="";
					if(is_array($e->populated_value['element_'.$e->id.'_'.$i]) && trim($e->populated_value['element_'.$e->id.'_'.$i][$this->ai]) == $key) {
						$selected = 'selected="selected"';
					} elseif(!is_array($e->populated_value['element_'.$e->id.'_'.$i]) && trim($e->populated_value['element_'.$e->id.'_'.$i]) == $key) {
						$selected = 'selected="selected"';
					} elseif ($e->default_value == $key) {
						$selected = 'selected="selected"';
					}
					if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$key."\" {$selected}>".$option."</option>";
				}
			}
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
		}
	}
	?>