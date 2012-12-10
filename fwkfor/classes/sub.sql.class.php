<?php
/*  sub.sql.class.php
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
class sqlZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$query=$this->xmlf->values->query;
		$query=str_replace("##",DB_PREFIX,$query);

		if (!empty($this->xmlf->values->where)) {
			//FIXME: check this
			$wherefields=explode(",",$this->xmlf->values->where);
			foreach ($wherefields as $wherefield) {
				$query=str_replace("$".$wherefield,0,$query);
			}
		}
		$result = do_query($query);
		while($row = mysql_fetch_array($result)){
			$key=$row[0];
			$option=$row[1];
			//FIXME: check this
			if ($key==$this->int) $this->ext=$option;
			//if ($key==$this->int) $this->ext=$key;
		}
		return $this->ext;
			
	}

	function display(&$field_markup,&$subscript_markup) {
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		$field_markup.="<select id=\"element_{$e->id}_{$i}{$this->ail}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly}>";
		$option_markup="";
		$query=$xmlf->fields->{'field'.$i}->values->query;
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
			
		$result = do_query($query);
			
		while($row = mysql_fetch_array($result)){
			$key=$row[0];
			$option=$row[1];
			$selected="";
			//if ($fields > 1) { $fieldsuffix='_'.$i; } else { $fieldsuffix=''; }
			if(trim($e->populated_value['element_'.$e->id.'_'.$i][$this->ai]) == $key){
				$selected = 'selected="selected"';
			} elseif ($e->default_value == $key) {
				$selected = 'selected="selected"';
			}
			if (!$e->readonly || ($e->readonly && $selected)) $option_markup .= "<option value=\"".$key."\" {$selected}>".$option."</option>";
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label class=\"subname\" for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";


		}
	}