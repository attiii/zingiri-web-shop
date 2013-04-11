<?php
class product_categoryZfSubElement extends zfSubElement {

	function output($mode="edit",$input="")
	{
		$db=new db();
		$query="select * from ##category where id=".qs($this->int);
		if ($db->select($query) && $db->next()) $this->ext=$db->get('desc');
		else $this->ext=$this->int;
		return $this->ext;
			
	}

	function display(&$field_markup,&$subscript_markup) {
		$db=new aphpsDb();
		$e=$this->element;
		$i=$this->subid;
		$xmlf=$this->xmlf;
		$field_markup.="<select id=\"element_{$e->id}_{$i}\" name=\"element_{$e->id}_{$i}\" class=\"element text\" {$e->readonly}>";
		$option_markup="";

		$query="select * from ##group order by sortorder,name";
		$db->select($query);
		while($db->next()) {
			$option_markup.='<optgroup label="'.$db->get('name').'">';
			$option_markup.=$this->displayCategories('group',$db->get('id'),trim($e->populated_value['element_'.$e->id.'_'.$i]));
			$option_markup.='</optgroup>';
		}
		$field_markup.=$option_markup;
		$field_markup.="</select>";
		$subscript_markup.="<label for=\"element_{$e->id}_{$i}\">".z_($xmlf->fields->{'field'.$i}->label)."</label>";
	}
	
	function displayCategories($type,$parentid,$key,$level=0) {
		$output='';
		$db=new aphpsDb();
		if ($type=='group') $query=sprintf("select * from ##category where groupid=%s and (parentid=0 or parentid is null) order by sortorder,`desc`",$parentid);
		else $query=sprintf("select * from ##category where parentid=%s order by sortorder,`desc`",$parentid);
		if ($db->select($query)) {
			while($category=$db->next()) {
				if ($category['ID']==$key) $selected = 'selected="selected"';
				else $selected='';
				$output .= "<option value=\"".$category['ID']."\" {$selected}>".str_repeat('&nbsp',$level*3).$category['DESC']."</option>";
				$output.=$this->displayCategories('category',$category['ID'],$key,$level+1);
				
			}
		}
		return $output;
	}
}