<?php
/*  rule_function.class.php
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
class zfrule_forcevalue extends zfrule {
	var $input=array();
	var $ouput=array();
	
	function precheck(&$e,$parameters) {
		$this->input=$e->populated_value;
		$this->doit($e,$parameters);
		$e->populated_value=$this->input;
	}
	
	function postcheck(&$e,$parameters) {
		$this->input=$e->input;
		$this->doit($e,$parameters);
		$e->input=$this->input;
	}

	function doit($e,$parameters) {
		$type=$parameters[0];
		$value=$parameters[1];
		if ($type == 'function') {
			$this->input['element_'.$e->id.'_1']=$value($e);
		} elseif ($type == 'variable') {
			global $$value;
			$this->input['element_'.$e->id.'_1']=$$value;
		}
		
	}
	
}
?>