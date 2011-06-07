<?php if ($index_refer <> 1) { exit(); } ?>
<?php

//class to parse uploaded files
class parse_upload {
	var $file;
	var $success=true;
	var $messages=array();

	//function to parse uploaded file
	function parse_upload($file) {
		global $dbtablesprefix;

		//		echo $file;
		$this->file=$file;
		$this->unzip();

		return;
	}

	function unzip() {
		$tmpdir=ZING_UPLOADS_DIR.'temp/';

		//clear temp directory
		$zip = new ZipArchive;
		if ($zip->open($this->file) === TRUE) {
			$zip->extractTo($tmpdir);
			$zip->close();
		} else {
			return false;
		}

		if ($handle = opendir($tmpdir)) {
			while (false !== ($file = readdir($handle))) {
				if (substr($file,-3) == "zip") {
					//					echo '<br />'.$file;
					if ($zip->open($tmpdir.$file) === TRUE) {
						$zip->extractTo($tmpdir);
						$zip->close();
						unlink($tmpdir.$file);
					}
				}
			}
			closedir($handle);
		}

		if ($configText=$this->readFile($tmpdir.'config.ini')) {
			$this->config=json_decode($configText,true);
			echo '<br />';print_r($config);
			unlink($tmpdir.'config.ini');
		} else {
			$this->success=false;
			$this->messages['Failed to load config.ini'];
			return;
		}

		$this->copyDir('category image',$tmpdir.'cats/',ZING_UPLOADS_DIR.'cats/',array('jpg','png','bmp','gif','jpeg'));
		$this->copyDir('order file',$tmpdir.'orders/',ZING_UPLOADS_DIR.'orders/',array('pdf'));
		$this->copyDir('product image',$tmpdir.'prodgfx/',ZING_UPLOADS_DIR.'prodgfx/',array('jpg','png','bmp','gif','jpeg'));
		$this->copyDir('digital file',$tmpdir.'digital-'.$this->config['digital'].'/',ZING_UPLOADS_DIR.'digital-'.get_option('zing_webshop_dig').'/',array('jpg','png','bmp','gif','jpeg','pdf','zip'));

		$this->sql($tmpdir.'db.sql');
		unlink($tmpdir.'db.sql');
		rmdir($tmpdir);
		unlink($this->file);
	}

	function copyDir($cat,$src,$dst,$types) {
		//		echo '<br />copy '.$src.' to '.$dst;
		if ($handle = opendir($src)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..') {
					$ext=substr($file,-3);
					if (in_array($ext,$types)) {
						//						echo '<br />'.$file;
						copy($src.$file,$dst.$file);
						unlink($src.$file);
					} else {
						if ($ext != 'php') $this->messages[]='Unauthorized '.$cat.': '.$file;
						unlink($src.$file);
					}
				}
			}
			closedir($handle);
			//			echo '<br />delete '.$src;
			rmdir($src);
		}

	}

	function sql($file) {
		global $dbtablesprefix;

		$db=new db();

		$error=false;
		$file_content = file($file);
		$query = "";
		$load=false;
		$create=false;
		$line=false;
		$data='';
		mysql_query("START TRANSACTION");

		if ($this->dropTables()) {
			update_option('zing_webshop_version',$this->config['version']);
			update_option('zing_apps_player_version',$this->config['apps_version']);
			mysql_query('SET storage_engine=InnoDB');
			foreach($file_content as $sql_line) {
				$tsl = trim($sql_line);
				if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
					if (!$line && strstr($sql_line,"CREATE TABLE `##")) {
						$create=true;
						$query = "";
						$sql_line = str_replace("CREATE TABLE `##", "CREATE TABLE `".$dbtablesprefix, $sql_line);
					}
					if ($create) {
						$sql_line = str_replace("ENGINE=MyISAM", "ENGINE=InnoDB", $sql_line);
						$query .= $sql_line;
					}
					if ($create && preg_match("/;\s*$/", $sql_line)) {
						//echo '<br />'.$query;
						if (mysql_query($query)) {
						} else {
							$this->messages[]='Failed to create table';
							$error=true;
						}
						$create=false;
						$query = "";
					}
					
					if (!$create && substr($sql_line,0,2)=='++') {
						//echo '<br />start line:'.$sql_line;
						$line=true;
						$data=substr($sql_line,2);
						$added=true;
					}
					if ($line && substr($sql_line,0,2)!='++' && substr($sql_line,-3,2)!='++') {
						//echo '<br />add to line:'.$sql_line;
						$data.=$sql_line;
					}
					if ($line && substr($sql_line,-3,2)=='++') {
						//echo '<br />end line:'.$sql_line;
						if (!$added) $data.=substr($sql_line,0,-2);
						$row=unserialize($data);
						//echo '<br />'.$data;
						//echo '<br />';print_r($row);
						$table=$row['TABLE'];
						if ($table) {
							unset($row['TABLE']);
							$db->insertRecord($table,"",$row);
						}
						$line=false;
					} 
					$added=false;
						
				}
			}
			zing_install();
		} else {
			$error=true;
		}
		if ($error) {
			mysql_query("ROLLBACK");
		} else {
			mysql_query("COMMIT");
		}
	}

	function dropTables() {
		global $dbtablesprefix;
		$query="show tables like '".$dbtablesprefix."%'";
		if ($sql = mysql_query($query)) {
			while ($row = mysql_fetch_row($sql)) {
				$query="drop table ".$row[0];
				if (!mysql_query($query)) return false;
			}
			return true;
		} else {
			return false;
		}
	}

	function readFile($fullfilename) {
		if ($fp = fopen($fullfilename, "rb")) {
			if (filesize($fullfilename) > 0) { $text2edit = fread($fp, filesize($fullfilename)); }
			fclose($fp);
			return $text2edit;
		} else {
			return false;
		}

	}
}

// admin check
if (IsAdmin() == false) {
	PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
	// calculate max size
	$max=preg_replace('/([0-9].*)[a-zA-Z].*/','$1',ini_get('upload_max_filesize'));
	$unit=strtoupper(preg_replace('/[0-9].*([a-zA-Z].*)/','$1',ini_get('upload_max_filesize')));
	if (strstr($unit,'M')) $max=1024*1024*$max;
	if (strstr($unit,'K')) $max=1024*$max;

	// upload the file
	if ($action == "upload") {
		$target_path = ZING_UPLOADS_DIR.$_FILES['uploadedfile']['name'];
		// delete previous pricelist if it exists
		if (file_exists($target_path)) {
			unlink($target_path);
		}

		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			// now read the temp file and put it's values into the database
			if (strtoupper(substr($_FILES['uploadedfile']['name'], -3)) == "ZIP") {
				$parse=new parse_upload($target_path);
				$success=$parse->success;
				if ($parse->messages) {
					foreach ($parse->messages as $message) {
						$messages.='<br />'.$message;
					}
				}
			} else {
				PutWindow($gfx_dir, $txt['general12'], $txt['migrate3'], "warning.gif", "50");
			}
			//			unlink($target_path);
		}
		else{
			$success=false;
			$messages.='<br /><a href="http://www.php.net/manual/en/features.file-upload.errors.php">Err:'.$_FILES['uploadedfile']['error'].'</a>';
		}
		if (!$success || $messages)	PutWindow($gfx_dir, $txt['general12'], $txt['uploadadmin2'].$messages, "warning.gif", "50");
		else PutWindow($gfx_dir, $txt['general13'], $txt['uploadadmin7'], "notify.gif", "50");
	}
	?>
<table width="80%" class="datatable">
	<tr>
		<td>
		<form enctype="multipart/form-data" action="<?php zurl('index.php?page=migrate',true);?>"
			method="POST"
		><input type="hidden" name="action" value="upload"> <input type="hidden" name="MAX_FILE_SIZE"
			value="<?php echo $max;?>"
		> <input name="uploadedfile" type="file" size="50" maxlength="256"><br />
		<br />
		<div style="text-align: center;"><input type="submit" value="<?php echo $txt['uploadadmin6']; ?>"></div>
		</form>
		</td>
	</tr>
</table>
	<?php } ?>