<?php
/*  db.inc.php
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
function zfReadRecord($table,$keys,$action="")
{

	Global $dbtablesprefix;

	$query="SELECT * FROM `".$dbtablesprefix.$table."` ";
	$first=TRUE;
	foreach ($keys as $field => $val)
	{

		if ($first)	{ $query.="WHERE "; } else { $query.=" AND "; }
		$first=FALSE;
		$query.="`".$field."`=".zfqs($val);

	}

	$sql = mysql_query($query) or die(dbError(1,$query,"substax.inc.php",$action));
	$numrows=mysql_num_rows($sql);
	
	if ($numrows == 0) return false;
	
	$row=mysql_fetch_assoc($sql);
	return $row;
}


function UpdateRecord($table,$keys,$row,$action="")
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
			$query.="`".$field."`=".zfqs($val);
		}
	}
	$first=TRUE;
	foreach ($keys as $keyfield => $keyval)
	{
		if ($first){ $query.=" WHERE "; } else { $query.=" AND "; }
		$first=FALSE;
		$query.= "`".$keyfield."`=".zfqs($keyval);
	}

	if (function_exists('zfDumpQuery')) zfDumpQuery($query,$table);
	
	$sql_update = mysql_query($query) or die(dbError(1,$query,"substax.inc.php",$action));
}

function InsertRecord($table,$keys,$row,$action)
{
	Global $dbtablesprefix;

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
			$query.=zfqs($val);
		}
	}
	$query.=")";
	//	echo $query."<br />";
	$sql_insert = mysql_query($query) or die(dbError(1,$query,"substax.inc.php",$action));
	$id = mysql_insert_id();

	if (function_exists('zfDumpQuery')) zfDumpQuery($query,$table);
	
	return $id;
}

function DeleteRecord($table,$keys,$action="")
{
	Global $dbtablesprefix;

	$query="DELETE FROM `".$dbtablesprefix.$table."` ";
	$first=TRUE;
	foreach ($keys as $field => $val)
	{
		if ($first)	{ $query.="WHERE "; } else { $query.=" AND "; }
		$first=FALSE;
		$query.="`".$field."`=".zfqs($val);
	}

	//	echo $query."<br />";
	$sql = mysql_query($query) or die(dbError(1,$query,"",$action));
	
	if (function_exists('zfDumpQuery')) zfDumpQuery($query,$table);
	
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

	txrollback();

	//user_error_handler("db".$severity, $query."<br />".$sql, $page."-".$action, 0);

	echo "ERROR:".$severity."-".$query."-".$sql;

}


?>