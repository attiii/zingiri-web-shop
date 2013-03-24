<?php
if (!class_exists('aphpsDb')) {
	class aphpsDb {
		var $numrows;
		var $sql="";
		var $row;
		var $table;
		var $onError; //1=die, 2=return false

		function aphpsDb($onError='') {
			if ($onError) $this->onError=$onError;
			elseif (defined('APHPS_DB_ON_ERROR')) $this->onError=APHPS_DB_ON_ERROR;
			else $this->onError=1;
		} 
		
		function select($query) {
			global $dbtablesprefix;
			$action="";
			$query=str_replace("##",$dbtablesprefix,$query);
			if ($this->sql = mysql_query($query)) {
				$this->numrows=mysql_num_rows($this->sql);
				if ($this->numrows == 0) return false; else return $this->numrows;
			} else $this->error($query);
		}

		function next() {
			if ($this->row=mysql_fetch_assoc($this->sql)) {
				return $this->row;
			} else { return false; }
		}

		function exists($table) {
			global $dbtablesprefix;
			$query="SELECT * FROM `".$dbtablesprefix.$table."`";
			if (mysql_query($query)) return true;
			else return false;
		}

		function fieldExists($table,$field) {
			global $dbtablesprefix;
			$query = "SHOW COLUMNS FROM `".$dbtablesprefix.$table."` ";
			if ($result=mysql_query($query)) {
				while ($row = mysql_fetch_row($result)) {
					if ($row[0]==$field || $row[0]==strtoupper($field) || $row[0]==strtolower($field)) return true;
				}
			}
			return false;
		}
			
		function allFields($table) {
			global $dbtablesprefix;
			$fields=array();
			$query = "SHOW COLUMNS FROM `".$dbtablesprefix.$table."` ";
			if ($result=mysql_query($query)) {
				while ($row = mysql_fetch_row($result)) {
					$fields[]=strtolower($row[0]);
				}
			}
			return $fields;
		}

		function update($query) {
			global $dbtablesprefix;
			$query=str_replace("##",$dbtablesprefix,$query);
			if ($sql = mysql_query($query)) {
				//continue
			} else {
				if ($this->onError == 2) return $this->error(); 
				else die($this->error($query));
			}
			return $sql;
		}

		function error($query='') {
			$msg="Database Error:".$query."/".mysql_error();
			echo $msg;
			if (function_exists("zing_ws_error_handler")) zing_ws_error_handler(0,$msg);
			elseif (function_exists("zing_apps_error_handler")) zing_apps_error_handler(0,$msg);
			if ($this->onError == 2) return false;
			else die();
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
			$sql = mysql_query($query) or die($this->error($query));
			$numrows=mysql_num_rows($sql);

			if ($numrows == 0) return false;

			$row=mysql_fetch_assoc($sql);
			return $row;
		}


		function updateRecord($table,$keys,$row,$action="")
		{

			Global $dbtablesprefix;

			if (!isset($row['DATE_UPDATED'])) $row['DATE_UPDATED']=date('Y-m-d H:i:s');
			
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


			//zing_ws_error_handler(0,$query);

			if ($sql_update = mysql_query($query)) {
				//continue
			} else {
				die($this->error($query));
			}

		}

		function insertRecord($table,$keys="",$row,$action="")
		{
			global $dbtablesprefix;

			if (!isset($row['DATE_CREATED'])) $row['DATE_CREATED']=date('Y-m-d H:i:s');
			if (!isset($row['DATE_UPDATED'])) $row['DATE_UPDATED']=date('Y-m-d H:i:s');
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
			//echo $query."<br />";
			if (($table=='flink') && defined('APHPS_DISPLAY_LINKS_ON_SAVE') && APHPS_DISPLAY_LINKS_ON_SAVE) echo $query.chr(10);
			$sql_insert = mysql_query($query) or die($this->error($query));
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
			$sql=mysql_query($query) or die($this->error($query));
		}

		function txcommit()
		{
			Global $txglobal;

			if ($txglobal)
			{
				$query="COMMIT";
				$sql=mysql_query($query) or die($this->error($query));
			}
			$txglobal=FALSE;
		}

		function txrollback()
		{
			Global $txglobal;

			if ($txglobal)
			{
				$query="ROLLBACK";
				$sql=mysql_query($query) or die($this->error($query));
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

		function export($tables)
		{
			global $dbtablesprefix;

			$this->exported='';
			foreach($tables as $t)
			{
				$this->table = $t['name'];
				$this->fields='';
				$query="SHOW FULL COLUMNS FROM `".$dbtablesprefix.$this->table."`";
				$sql = mysql_query($query) or die($this->error($query));
				while ($row=mysql_fetch_assoc($sql)) {
					$this->fields[$row['Field']]=$row;
				}
				$this->exported .= "--\n";
				if ($t['definition']) {
					$header = $this->create_header($t['auto']);
					$this->exported .= "-- Table structure for table {$this->table}\n--\n\n" . $header . "\n\n";
				}
				if ($t['data']) {
					$data = $this->get_data($t['filter'],$t['fields']);
					$this->exported .= "--\n-- Dumping data for table {$this->table}\n--\n\n" . $data . "\n\n";
				}
			}

			return($this->exported);
		}

		function create_header($auto)
		{
			global $dbname,$dbtablesprefix;

			$query="SHOW CREATE TABLE `".$dbtablesprefix.$this->table."`";
			$sql = mysql_query($query) or die($this->error($query));
			$row=mysql_fetch_array($sql);
			$h=str_replace("`".$row[0]."`","`##".$this->table."`",$row[1]);

			if ($auto === false) $h=preg_replace('/AUTO_INCREMENT\=[0-9]* / ','',$h);

			$h=$h.';';
			return($h);
		}

		function get_data($filter='',$afields='*')
		{
			global $dbname,$dbtablesprefix;

			if (is_array($afields)) {
				$a=$this->fields;
				foreach ($a as $field => $data) {
					if (!in_array($field,$afields)) {
						unset($this->fields[$field]);
					}
				}
			}

			$fields='';
			foreach ($this->fields as $field) {
				$name = $field['Field'];
				if ($fields) $fields.=',';
				$fields .= "`$name`";
			}

			$d = null;
			if ($filter) $sql="SELECT " . $fields . " FROM `" . $dbtablesprefix.$this->table . "` WHERE ".$filter;
			else $sql = "SELECT " . $fields . " FROM `" . $dbtablesprefix.$this->table . "` WHERE 1";
			$data = mysql_query($sql) or $this->error();
			while($cr = mysql_fetch_array($data, MYSQL_NUM))
			{
				$d .= "INSERT INTO `##" . $this->table . "` (".$fields.") VALUES (";

				for($i=0; $i<sizeof($cr); $i++)
				{
					if($cr[$i] == '') {
						$d .= 'NULL,';
					} else {
						$d .= '"'.mysql_real_escape_string($cr[$i]).'",';
					}
				}

				$d = substr($d, 0, strlen($d) - 1);
				$d .= ");\n";
			}

			return($d);
		}

		function executeScript($file_content,$create=true) {
			global $dbtablesprefix;
			$query='';
			foreach($file_content as $sql_line) {
				$tsl = trim($sql_line);
				if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
					if (str_replace("##", $dbtablesprefix, $sql_line) == $sql_line) {
						$sql_line = str_replace("CREATE TABLE `", "CREATE TABLE `".$dbtablesprefix, $sql_line);
						$sql_line = str_replace("CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS`".$dbtablesprefix, $sql_line);
						$sql_line = str_replace("INSERT INTO `", "INSERT INTO `".$dbtablesprefix, $sql_line);
						$sql_line = str_replace("ALTER TABLE `", "ALTER TABLE `".$dbtablesprefix, $sql_line);
						$sql_line = str_replace("UPDATE `", "UPDATE `".$dbtablesprefix, $sql_line);
						$sql_line = str_replace("TRUNCATE TABLE `", "TRUNCATE TABLE `".$dbtablesprefix, $sql_line);
					} else {
						$sql_line = str_replace("##", $dbtablesprefix, $sql_line);
					}
					$query .= $sql_line;
					if(preg_match("/;\s*$/", $sql_line)) {
						if (($create || !stristr($query,'CREATE TABLE')) && !mysql_query($query)) {
							$this->display($query);
							$this->display('Error loading:'.mysql_error());
							return false;
						}						
						$query = "";
					}
				}
			}
			return true;
		}
		//end executeScript

		function display($message) {
			if (is_array($message)) print_r($message,true);
			else echo $message;
			echo EOL;
		} 
		//end display
		
		function getInsertId() {
			return mysql_insert_id();
		}
		
	}
}

if (!class_exists('db')) {
	class db extends aphpsDb {
		
	}
} 
?>