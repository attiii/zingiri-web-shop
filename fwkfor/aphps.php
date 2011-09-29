<?php
if (!class_exists('aphps')) {
	class aphps {
		var $actions=array();
		
		function addAction($action,$f) {
			$this->actions[$action]=$f;			
		}
		
		function doAction($action,&$p1='',&$p2='',&$p3='') {
			if (isset($this->actions[$action]) && ($f=$this->actions[$action])) {
				$f($p1,$p2,$p3);
			}
		}

	}
	global $aphps;
	$aphps=new aphps();
}
