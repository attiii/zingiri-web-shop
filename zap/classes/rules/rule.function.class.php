<?php
/*  rule_function.class.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Apps.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Zingiri Apps; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
class zfrule_function extends zfrule {
	
	function postcheck(&$e,$parameters) {
		$input=$e->input;
		$f=$parameters[0];
		$compare=$parameters[1];
		$reference=$parameters[2];
		$action=$parameters[3];
		if ($f == 'age') {
			$value=date('Y')-$input['element_'.$e->id.'_1'];
			if (date('md') < $input['element_'.$e->id.'_2'].$input['element_'.$e->id.'_3'] && $value != 0) $value--;
			$message='Age check failed';
		}
		$compareResult=$this->compare($value,$reference,$compare);
		if ((!$compareResult && $action=='required') || ($compareResult && $action=='notallowed')) {
			$this->result=false;
			$this->error_message=$message;
		}
		return $this->result;
	}
}
?>