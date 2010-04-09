<?php
class db {
	var $numrows;
	var $sql="";
	var $row;

	function select($query) {
		global $dbtablesprefix;
		$action="";
		$query=str_replace("##",$dbtablesprefix,$query);
		$this->sql = mysql_query($query) or die($this->error($query));
		$this->numrows=mysql_num_rows($this->sql);
		if ($this->numrows == 0) return false; else return $this->numrows;

	}

	function next() {
		if ($this->row=mysql_fetch_assoc($this->sql)) {
			return $this->row;
		} else { return false; }
	}

	function update($query) {
		global $dbtablesprefix;
		$query=str_replace("##",$dbtablesprefix,$query);
		$sql = mysql_query($query) or die($this->dbError(1,$query,"",$action));
		return $sql;
	}

	function error($query) {
		echo "Database Error:".$query."/".mysql_error();
	}

	function get($field) {
		if (!empty($this->row[$field])) return $this->row[$field];	
		$field=strtolower($field);
		if (!empty($this->row[$field])) return $this->row[$field];	
		$field=strtoupper($field);
		if (!empty($this->row[$field])) return $this->row[$field];	
		return false;
	}
	
	function readRecord($table,$keys,$action="")
	{

		Global $dbtablesprefix;

		$query="SELECT * FROM `".$dbtablesprefix.$table."` ";
		$first=TRUE;
		foreach ($keys as $field => $val)
		{

			if ($first)	{ $query.="WHERE "; } else { $query.=" AND "; }
			$first=FALSE;
			$query.="`".$field."`=".qs($val);

		}

		//	echo $query."<br />";
		$sql = mysql_query($query) or die($this->dbError(1,$query,"substax.inc.php",$action));
		$numrows=mysql_num_rows($sql);

		if ($numrows == 0) return false;

		$row=mysql_fetch_assoc($sql);
		return $row;
	}


	function updateRecord($table,$keys,$row,$action="")
	{

		Global $dbtablesprefix;

		$query="UPDATE `".$dbtablesprefix.$table."` ";
		$first=TRUE;
		foreach ($row as $field => $val)
		{
			$iskey=FALSE;
			foreach ($keys as $keyfield => $keyval)
			{
				if ($field == $keyfield) { $iskey=TRUE; }
			}
			if (!$iskey)
			{
				if ($first)	{ $query.="SET "; } else { $query.=","; }
				$first=FALSE;
				$query.="`".$field."`=".qs($val);
			}
		}
		$first=TRUE;
		foreach ($keys as $keyfield => $keyval)
		{
			if ($first){ $query.=" WHERE "; } else { $query.=" AND "; }
			$first=FALSE;
			$query.= "`".$keyfield."`=".qs($keyval);
		}


		//	echo $query."<br />";
		$sql_update = mysql_query($query) or die($this->dbError(1,$query,"substax.inc.php",$action));
	}

	function insertRecord($table,$keys="",$row,$action="")
	{
		global $dbtablesprefix;
		
		$query="INSERT INTO `".$dbtablesprefix.$table."` ";
		$first=TRUE;
		foreach ($row as $field => $val)
		{
			$iskey=FALSE;
			if (!empty($keys))
			{
				foreach ($keys as $keyfield => $keyval)
				{
					if ($field == $keyfield) { $iskey=TRUE; }
				}
			}
			if (!$iskey)
			{
				if ($first)	{ $query.="("; } else { $query.=","; }
				$first=FALSE;
				$query.="`".$field."`";
			}
		}
		$query.=") VALUES ";
		$first=TRUE;
		foreach ($row as $field => $val)
		{
			$iskey=FALSE;
			if (!empty($keys))
			{
				foreach ($keys as $keyfield => $keyval)
				{
					if ($field == $keyfield) { $iskey=TRUE; }
				}
			}
			if (!$iskey)
			{
				if ($first)	{ $query.="("; } else { $query.=","; }
				$first=FALSE;
				$query.=qs($val);
			}
		}
		$query.=")";
		//	echo $query."<br />";
		$sql_insert = mysql_query($query) or die($this->dbError(1,$query,"substax.inc.php",$action));
		$id = mysql_insert_id();

		return $id;
	}

	function deleteRecord($table,$keys,$action="")
	{
		Global $dbtablesprefix;

		$query="DELETE FROM `".$dbtablesprefix.$table."` ";
		$first=TRUE;
		foreach ($keys as $field => $val)
		{
			if ($first)	{ $query.="WHERE "; } else { $query.=" AND "; }
			$first=FALSE;
			$query.="`".$field."`=".qs($val);
		}

		//	echo $query."<br />";
		$sql = mysql_query($query) or die($this->dbError(1,$query,"",$action));
	}

	function txbegin()
	{
		Global $txglobal;

		$txglobal=TRUE;
		$query="START TRANSACTION";
		$sql=mysql_query($query) or die(mysql_error());
	}

	function txcommit()
	{
		Global $txglobal;

		if ($txglobal)
		{
			$query="COMMIT";
			$sql=mysql_query($query) or die(mysql_error());
		}
		$txglobal=FALSE;
	}

	function txrollback()
	{
		Global $txglobal;

		if ($txglobal)
		{
			$query="ROLLBACK";
			$sql=mysql_query($query) or die(mysql_error());
		}
		$txglobal=FALSE;
	}

	function dbError($severity, $query, $page, $action)
	{
		Global $gfx_dir;
		Global $txt;
		Global $dbError;
		Global $channel;
		Global $error;
		Global $errormsg;

		$dbError=1;
		$sql=mysql_error();

		$this->txrollback();

		echo "ERROR:".$severity."-".$query."-".$sql;

	}
}
?>