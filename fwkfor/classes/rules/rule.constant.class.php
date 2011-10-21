<?php
/*  rule_constant.class.php
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
class zfrule_constant extends zfrule {

	function precheck(&$e,$parameters) {
		if (defined($parameters[0])) $this->v=constant($parameters[0]);
		else $this->v=0;
		$this->compare=$parameters[1];
		$this->reference=$parameters[2];
		$this->action=$parameters[3];
		global ${$this->v};
		if ($this->compare($this->v,$this->reference,$this->compare)) {
			$this->action($e,$this->action);
		}
	}

	function postcheck(&$e,$parameters) {
		$this->precheck($e,$parameters);
	}
}
?>