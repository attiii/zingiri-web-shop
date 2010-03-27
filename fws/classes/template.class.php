<?php
class template {
	var $content;

	function __construct($templateid=0,$templatename="") {
		$db= new db();
		if ($templateid) $db->select("select * from ##templates where id=".qs($templateid));
		else $db->select("select * from ##templates where name=".qs($templatename));
		$db->next();
		$this->content=$db->get('content');
		$this->title=$db->get('title');
	}
	
	function replace($field,$fill) {
		$this->content=str_replace('['.$field.']',$fill,$this->content);
	}

	function replaceTitle($field,$fill) {
		$this->title=str_replace('['.$field.']',$fill,$this->title);
	}
	
}
?>