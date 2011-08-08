<?php
class filesHash {
	function filesHash() {

	}

	function makeFilesHash($dir=ZING_LOC,$files="") {
		if (substr($dir,-1)!='/') $dir.='/';
		$exclude=array('dbc.sql','build.xml','build.exclude.txt','deploy-live.xml','deploy-live.exclude.txt','migrate.php','pack.wordpress.xml','.externalToolBuilders','source.inc.php','dompdf_font_family_cache','.ufm','.afm','.ttf','.TTF','gc_counter.ajax.php','gc_log.ajax.php','log.txt','pack.php','apps.db.sql','.svn','.DS_Store','.buildpath','.project','.settings','.jsdtscope','org.eclipse','.key','fileshash.txt');
		if (empty($files)) $files=array();
		//echo '<br />'.$dir;
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				$excluded=false;
				foreach ($exclude as $item) {
					if (strstr($file,$item)!==false) $excluded=true;
					//echo '<br >check:'.$item.' in '.$file;
				}
				if (!$excluded && $file != '.' && $file != '..') {
					//echo '<br />'.$file;
					if (is_dir($dir.$file)) {
						$files=$this->makeFilesHash($dir.$file,$files);
					} else {
						$sdir=str_replace(WP_PLUGIN_DIR.'/','',$dir);
						$files[$sdir.$file]=md5_file($dir.$file);
					}
				}
			}
			closedir($handle);
		}
		return $files;
	}


	function saveFilesHash($files) {
		if ($handle = fopen (ZING_LOC."fileshash.txt", "w")) {
			foreach ($files as $file => $checksum) {
				fwrite($handle, $file.','.$checksum."\r\n");
			}
			fclose($handle);
			return true;
		} else { return false; }
	}

	function readFilesHash() {
		$files=array();
		if (file_exists(ZING_LOC.'fileshash.txt')) {
			$lines=file(ZING_LOC.'fileshash.txt');
			foreach ($lines as $line) {
				list($file,$checksum)=explode(',',$line);
				$files[$file]=$checksum;
			}
		}
		return $files;
	}

	function compare() {
		$errors=array();
		$ref=$this->readFilesHash();
		$mine=$this->makeFilesHash();
		foreach ($ref as $file => $checksum) {
			if (!isset($mine[$file])) $errors[$file]=1;
			elseif ($mine[$file] != trim($checksum)) $errors[$file]=2;
		}
		return $errors;
	}

}