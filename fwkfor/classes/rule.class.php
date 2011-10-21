<?php
/*  rule_parameter.class.php
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
class zfrule {
	var $result=true; //true-false result of rule check
	var $error_message;

	function zfrule() {
	}

	function precheck(&$e,$parameters) {
		return;
	} 
	
	function postcheck(&$e,$parameters) {
		return $result;
	}
	
	function action(&$e,$action) {
		switch ($action) {
			case 'disable':
				$e->disabled=true;
				break;
			case 'unique':
				$e->unique=true;
				break;
		}
	}

	function compare($a,$b,$compare) {
		$a=trim($a);
		$b=trim($b);
		switch ($compare) {
			case 'EQ':
				if ($a == $b) return true;
				break;
			case 'LT':
				if ($a < $b) return true;
				break;
			case 'GT':
				if ($a > $b) return true;
			case 'NE':
				if ($a != $b) return true;
		}
		return false;
	}
}
?>