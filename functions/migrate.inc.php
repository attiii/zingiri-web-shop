<?php
class Zipper extends ZipArchive {
	var $zipFilesCounter=0;

	function addDir($path) {
		//print '<br />adding ' . $path . ' => '.str_replace(ZING_UPLOADS_DIR,'',$path);
		$this->addEmptyDir(str_replace(ZING_UPLOADS_DIR,'',$path));
		$nodes = glob($path . '/*');
		foreach ($nodes as $node) {
			//print '<br>'.$node . ' => '.str_replace(ZING_UPLOADS_DIR,'',$node);
			if (is_dir($node)) {
				$this->addDir($node);
			} else if (is_file($node))  {
				$this->zipFilesCounter++;
				$this->addFile($node,str_replace(ZING_UPLOADS_DIR,'',$node));
			}
			if ($this->zipFilesCounter % 10 == 0) {
				$this->close();
				$this->open(ZING_UPLOADS_DIR.'migrate'.$this->zipFilesCounter.'.zip',ZIPARCHIVE::OVERWRITE);
				//echo '<br />create new dir:'.ZING_UPLOADS_DIR.'migrate'.$this->zipFiles.'.zip';
				//print '<br />adding ' . $path . ' => '.str_replace(ZING_UPLOADS_DIR,'',$path);
				$this->addEmptyDir(str_replace(ZING_UPLOADS_DIR,'',$path));
			}
		}
	}

} // class Zipper

function zing_ws_migrate() {
	$zip = new Zipper;
	$files=array();
	$success=true;

	if (($error=$zip->open(ZING_UPLOADS_DIR.'migrate.zip',ZIPARCHIVE::OVERWRITE)) === TRUE) {
		if ($handle = opendir(ZING_UPLOADS_DIR)) {
			while (false !== ($file = readdir($handle))) {
				$dir=ZING_UPLOADS_DIR.$file;
				if ($file != 'cache' && $file != '.' && $file != '..' && is_dir($dir)) {
					$zip->addDir($dir);
				}
			}
		}
		$zip->addFromString('db.sql',zing_ws_database_dump());
		
		$zip->close();
	} else {
		$success=false;
		echo '<br />Failed zip file creation of migrate.zip with error '.$error;
	}

	if (($error=$zip->open(ZING_UPLOADS_DIR.'zingiri-web-shop.zip',ZIPARCHIVE::OVERWRITE)) === TRUE) {
		if ($handle = opendir(ZING_UPLOADS_DIR)) {
			while (false !== ($file = readdir($handle))) {
				$dir=ZING_UPLOADS_DIR.$file;
				if (strstr($file,'migrate')) {
					//echo '<br />zip:'.$dir;
					$zip->addFile($dir,$file);
					$files[]=$dir;
				}
			}
		}
		$zip->close();
	} else {
		$success=false;
	}

	if (count($files) > 0) {
		foreach ($files as $file) {
			unlink($file);
		}
	}

	return $success;
}

function zing_ws_database_dump() {
	$tables=array();

	//definitions only
	$tables[]=array('name' => 'accesslog', 'definition' => true, 'data' => false, 'auto' => false);
	$tables[]=array('name' => 'errorlog', 'definition' => true, 'data' => false, 'auto' => false);

	//definitions and data
	$tables[]=array('name' => 'address', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'bannedip', 'definition' => true, 'data' => false, 'auto' => false);
	$tables[]=array('name' => 'basket', 'definition' => true, 'data' => false, 'auto' => false);
	$tables[]=array('name' => 'category', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'customer', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'discount', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'faccess', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'faces', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'flink', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'frole', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'group', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'order', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'payment', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'paypal_cart_info', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'paypal_payment_info', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'paypal_subscription', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'product', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'prompt', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'settings', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'shipping', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'shipping_payment', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'shipping_weight', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'task', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'taxcategory', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'taxes', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'taxrates', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'template', 'definition' => true, 'data' => true);
	$tables[]=array('name' => 'transactions', 'definition' => true, 'data' => true);
	
	//definitions and selected data
	//$tables[]=array('name' => 'user', 'definition' => true, 'data' => true, 'filter' => 'id=1', 'fields' => array('ID','LOGINNAME','PASSWORD','LASTNAME','EMAIL','GROUP'));

	$db=new db();
	$dump=$db->export($tables);

	return $dump;
	/*
	$dbfile=ZING_UPLOADS_DIR."db-".get_option("zing_webshop_version").".sql";

	$fhandle=fopen($dbfile, "w") or Print("Could not open the file");
	fwrite($fhandle, $dump);
	fclose ($fhandle);
	echo 'Created database dump file '.$dbfile;
	*/
}

function zing_ws_active_install() {
	global $dbtablesprefix;
	if ($dbtablesprefix) {
		$sql = mysql_query("show tables like '".$dbtablesprefix."%'");
		if (mysql_num_rows($sql)) return true;
	}
	return false;
}