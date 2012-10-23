<?php if ($index_refer <> 1) { exit(); } ?>
<?php
// function to read mysql dump
function parse_mysql_dump($url, $ignoreerrors = false, $prefix) {
	$file_content = file($url);
	$query = "";
	foreach($file_content as $sql_line) {
		$tsl = trim($sql_line);
		if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
			$sql_line = str_replace("CREATE TABLE `", "CREATE TABLE `".$prefix, $sql_line);
			$sql_line = str_replace("INSERT INTO `", "INSERT INTO `".$prefix, $sql_line);
			$sql_line = str_replace("ALTER TABLE `", "ALTER TABLE `".$prefix, $sql_line);
			$sql_line = str_replace("UPDATE ", "UPDATE ".$prefix, $sql_line);
			$sql_line = str_replace("TRUNCATE TABLE `", "TRUNCATE TABLE `".$prefix, $sql_line);
			$query .= $sql_line;
			if(preg_match("/;\s*$/", $sql_line)) {
				$result = mysql_query($query);
				if (!$result && !$ignoreerrors) die(mysql_error());
				$query = "";
			}
		}
	}
}

//class to parse XML files
class parse_upload {
	var $headers=array();
	var	$values=array();
	var	$fields=array();
	var	$pairs=array();
	var	$error=false;
	var $prefix;
	var $productid;
	var $catid;
	var $xml;
	var $image;
	var $thumbnail;
	var $digital;
	var $success=true;
	var $messages=array();
	var $line=1;
	var $oldDigitalFile;
	var $productFields=array();

	//function to parse Excel XML file
	function parse_upload($url) {
		global $dbtablesprefix;
		$db=new db();
		$this->productFields=$db->allFields('product');
		$this->prefix=$dbtablesprefix;
		if ($this->xml=simplexml_load_file($url)) {
			$parsed=false;
			foreach ($this->xml->getDocNamespaces() as $ns) {
				switch ($ns) {
					case 'urn:schemas-microsoft-com:office:excel':
						$this->parse_excelxml($url);
						$parsed=true;
						break;
					case 'http://www.filemaker.com/fmpxmlresult':
						$this->parse_filemakerxml($url);
						$parsed=true;
						break;
				}
				if ($parsed) break;
			}
		} else {
			return false;
		}
	}

	function parse_filemakerxml($url) {
		if ($this->xml) {
			$i=0;
			foreach ($this->xml->METADATA->children() as $field) {
				$i++;
				$this->headers[$i]=strtolower((string)$field->attributes()->NAME);
			}
			foreach ($this->xml->RESULTSET->children() as $rowxml) {
				$this->line++;
				$this->values=array();
				$this->fields=array();
				$this->pairs=array();
				$this->error=false;
				$row=array();
				$i=0;
				$this->productid="";
				$this->groupid=0;
				$this->image=false;
				$this->thumbnail=false;
				$this->digital=false;
				foreach ($rowxml->children() as $col) {
					$i++;
					$data=(string)$col->DATA;
					$this->parse_data($data,$i);
				}
				$this->save_product();
				if ($this->error) $this->success=false;
			}
		} else {
			return false;
		}
	}

	function parse_excelxml($url) {
		$firstrow=true;
		if ($this->xml) {
			foreach ($this->xml->Worksheet->Table->children() as $row) {
				if (count($row->children()) > 0) {
					$this->values=array();
					$this->fields=array();
					$this->pairs=array();
					$this->error=false;
					if ($firstrow) $this->parse_header_excel($row);
					else {
						$this->line++;
						$this->parse_data_excel($row);
					}
					if (!$firstrow) {
						$this->save_product();
					} else {
						$firstrow=false;
					}
					if ($this->error) $this->success=false;
				}
			}
		} else {
			return false;
		}
	}


	function parse_header_excel($row) {
		$i=0;
		foreach ($row->children() as $cell) {
			$i++;
			$data=$cell->Data;
			$this->headers[$i]=strtolower($data);
		}
	}

	function parse_data_excel($row) {
		$i=0;
		$this->productid="";
		$this->groupid=0;
		$this->image=false;
		$this->thumbnail=false;
		$this->digital=false;
		foreach ($row->children() as $cell) {
			$i++;
			$data=$cell->Data;
			if (!isset($cell->Data)) continue;
			$index=(int)$cell->attributes('ss',true)->Index;
			if ($index) $i=$index;
			$this->parse_data($data,$i);
		}
	}


	function save_product() {
		if (!$this->error) {
			$this->oldDigitalFile="";
			$query="SELECT `ID`,`LINK` FROM `".$this->prefix."product` WHERE `productid` = '".$this->productid."' AND `catid` = '".$this->catid."'";
			$sql = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($sql) == 0) {
				$query="INSERT INTO `".$this->prefix."product` (".implode(",",$this->fields).") VALUES (".implode(",",$this->values).")";
				$sql = zing_query_db($query);
				$id=mysql_insert_id();
			} else {
				$row=mysql_fetch_array($sql);
				$id=$row[0];
				$this->oldDigitalFile=$row['LINK'];
				$query="UPDATE `".$this->prefix."product` SET ".implode(",",$this->pairs)." WHERE `id`='".$id."'";
				$sql = zing_query_db($query);
			}
			if ($this->digital) {
				$this->upload_digital($this->digital);
			}
			if ($this->image) {
				$this->upload_image($id,$this->image,false);
			}
			if ($this->thumbnail) {
				$this->upload_image($id,$this->thumbnail,true);
			}
		}

	}

	function upload_image($picid,$file,$thumbnail) {
		global $product_dir, $make_thumbs,$pricelist_thumb_width,$pricelist_thumb_height;
		if ($thumbnail) $tn='tn_'; else $tn='';
		$ext = explode(".", $file);
		$ext = strtolower(array_pop($ext));
		if ($ext == "jpg" || $ext == "gif" || $ext == "png") {
			$target_path = $product_dir."/".$tn.$picid.".".$ext;
			if($this->copy($file, $target_path)) {
				// lets try to create a thumbnail of this new image shall we
				if (!$this->thumbnail && $make_thumbs == 1) {
					createthumb($target_path,$product_dir.'/tn_'.$picid.".".$ext,$pricelist_thumb_width,$pricelist_thumb_height);
				}
			}
			else{
				$this->messages[]=$txt['uploadadmin100'].' '.$file.' -> '.$target_path;
				$this->error=true;
			}
		}
	}

	function upload_digital($files) {
		global $txt;

		if (!empty($this->oldDigitalFile)) unlink(ZING_DIG.$this->oldDigitalFile);

		list($file,$link) = $files;
		$target_path = ZING_DIG.$link;
			
		if(!$this->copy($file, $target_path,true)) {
			$this->messages[]=$txt['uploadadmin100'].' '.$file.' -> '.$target_path;
			$this->error=true;
		}
	}

	function copy($file,$target,$symlink=false) {
		$success=0;
		if (!file_exists($file)) {
			$this->messages[]="File doesn't exist: ".$file;
		} else {
			if ($symlink && $_POST['symlink']) {
				if (symlink($file,$target)) {
					$success=1;
				}
			} else {
				if (dirname($file) == dirname($target)) {
					if (rename($file,$target)) {
						$success=1;
					}
				} else {
					if (copy($file,$target)) {
						$success=1;
					}
				}
				//if ($success) chmod($target,0644); // new uploaded file can sometimes have wrong permissions
			}
		}
		return $success;
	}

	function parse_data($data,$i) {
		global $txt;
		$data=str_replace(chr(13),'<br />',$data);
		$data=mysql_real_escape_string($data);
		//load data
		$field=$this->headers[$i];
		switch ($field) {
			case 'group':
				$query="SELECT `ID` FROM `".$this->prefix."group` WHERE `name` = '".$data."'";
				$sql = mysql_query($query) or die(mysql_error());
				if ($r=mysql_fetch_array($sql)) {
					$this->groupid=$r['ID'];
				} else  {
					$query="INSERT INTO `".$this->prefix."group` (`name`) VALUES ('".$data."')";
					$sql = mysql_query($query) or die(mysql_error());
					$this->groupid=mysql_insert_id();
				}
				break;
			case 'category':
				$query="SELECT `ID` FROM `".$this->prefix."category` WHERE `desc` = '".$data."'";
				$sql = mysql_query($query) or die(mysql_error());
				if ($r=mysql_fetch_array($sql)) {
					$catid=$r['ID'];
				} else  {
					if ($this->groupid) {
						$query="INSERT INTO `".$this->prefix."category` (`groupid`,`desc`) VALUES ('".$this->groupid."','".$data."')";
						$sql = mysql_query($query) or die(mysql_error());
						$catid=mysql_insert_id();
					} else {
						$this->messages[]=$txt['uploadadmin101'].' group '.$txt['uploadadmin102'].' '.$this->line;
						$this->error=true;
					}
				}
				$this->fields[]="`catid`";
				$this->values[]=$catid;
				$this->pairs[]="`catid`='".$catid."'";
				$this->catid=$catid;
				break;
			case 'productid':
				$this->productid=$data;
				$this->fields[]="`".$field."`";
				$this->values[]="'".$data."'";
				$this->pairs[]="`".$field."`='".$data."'";
				break;
			case 'image':
				$this->image=$data;
				break;
			case 'thumbnail':
				$this->thumbnail=$data;
				break;
			case 'digital':
				$file = basename($data);
				$random = CreateRandomCode(15);
				$link=$random.'__'.$file;
				$this->digital=array($data,$link);
				$this->fields[]="`link`";
				$this->values[]="'".$link."'";
				$this->pairs[]="`link`='".$link."'";
				break;
			default:
				if (in_array($field,$this->productFields)) {
					$this->fields[]="`".$field."`";
					$this->values[]="'".$data."'";
					$this->pairs[]="`".$field."`='".$data."'";
				} else {				
					$this->error=true;
					$this->messages[]=$txt['uploadadmin103'].' '.$field.' '.$txt['uploadadmin102'].' '.$this->line;
				}
				break;
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

	// upload the SQL file
	if ($action == "upload_pricelist") {
		$target_path = $brands_dir."/";
		$target_path = $target_path."productupload";
		// delete previous pricelist if it exists
		if (file_exists($target_path)) {
			unlink($target_path);
		}

		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			// now read the temp file and put it's values into the database
			if (strtoupper(substr($_FILES['uploadedfile']['name'], -3)) == "SQL") {
				$success=parse_mysql_dump($target_path, TRUE, $dbtablesprefix);
			} elseif (strtoupper(substr($_FILES['uploadedfile']['name'], -3)) == "XML") {
				$parse=new parse_upload($target_path);
				$success=$parse->success;
				if ($parse->messages) {
					foreach ($parse->messages as $message) {
						$messages.='<br />'.$message;
					}
				}
			} else {
				PutWindow($gfx_dir, $txt['general12'], $txt['uploadadmin3'], "warning.gif", "50");
			}
			unlink($target_path);
		}
		else{
			$success=false;
			$messages.='<br /><a href="http://www.php.net/manual/en/features.file-upload.errors.php">Err:'.$_FILES['uploadedfile']['error'].'</a>';
		}
		if (!$success)	PutWindow($gfx_dir, $txt['general12'], $txt['uploadadmin2'].$messages, "warning.gif", "50");
		else PutWindow($gfx_dir, $txt['general13'], $txt['uploadadmin7'], "notify.gif", "50");
	}
	?>
<table width="80%" class="datatable">
	<tr>
		<td>
		<form enctype="multipart/form-data" action="<?php zurl('index.php?page=uploadadmin',true);?>"
			method="POST"
		><input type="hidden" name="action" value="upload_pricelist"> <input type="hidden"
			name="MAX_FILE_SIZE" value="<?php echo $max;?>"
		> <input name="uploadedfile" type="file" size="50" maxlength="256"><br />
		<?php echo $txt['uploadadmin105']?><input type="checkbox" name="symlink"> <br />
		<div style="text-align: center;"><input type="submit" value="<?php echo $txt['uploadadmin6']; ?>"></div>
		</form>
		<?php echo $txt['uploadadmin104'].' '.$max;?></td>
	</tr>
</table>
		<?php } ?>