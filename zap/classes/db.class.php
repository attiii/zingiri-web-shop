<?php
/*  db.class.php
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
class zfDB {
	var $numrows;
	var $sql="";
	var $row=array();
	
	function __construct() {
	}
	
	function select($query) {
		
		global $dbtablesprefix;
		
		$action="";
		$query=str_replace("##",$dbtablesprefix,$query);
		$this->sql = mysql_query($query) or die(dbError(1,$query,"substax.inc.php",$action));
		$this->numrows=mysql_num_rows($this->sql);
		if ($this->numrows == 0) return false; else return $this->numrows;
	
	}

	function exists($table) {
		
		global $dbtablesprefix;
		$query="SELECT * FROM `".$dbtablesprefix.$table."`";
		if (mysql_query($query)) return true;
		else return false;
	}
	
	function next() {
		$this->row=array();
		if ($row=mysql_fetch_assoc($this->sql)) {
			$this->row=$row;
			return $row;
		} else { return false; }
	}

	function update($query) {
		$this->sql = mysql_query($query) or die(dbError(1,$query,"",$action));
		return $this->sql;		
	} 
	
	function get($field) {
		if (!empty($this->row[$field])) return $this->row[$field];	
		$field=strtolower($field);
		if (!empty($this->row[$field])) return $this->row[$field];	
		$field=strtoupper($field);
		if (!empty($this->row[$field])) return $this->row[$field];	
		return false;
	}	
}
?>