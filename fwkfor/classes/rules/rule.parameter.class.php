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
class zfrule_parameter extends zfrule {

	function precheck(&$e,$parameters) {
		$id=$parameters[0];
		$compare=$parameters[1];
		$reference=$parameters[2];
		$action=$parameters[3];
		$db=new aphpsDb();
		$db->select("select value from ##fparam where id='".$id."'");
		if ($row=$db->next()) $value=$row['value'];
		else $value=null;
		if ($this->compare($value,$reference,$compare)) {
			$this->action($e,$action);
		}

	}
}
?>