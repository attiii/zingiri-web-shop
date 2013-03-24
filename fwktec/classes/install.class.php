<?php
class fwktec_install {
	function installAll($aphpsVersion,$version='') {
		global $aphps_projects;

		if (!$version) $version=$aphpsVersion;

		if (!function_exists('zfCreate')) require($aphps_projects['player']['dir'].'includes/create.inc.php');
		if (!function_exists('zfReadRecord')) require($aphps_projects['player']['dir'].'includes/db.inc.php');
		if (!function_exists('zf_json_decode')) require($aphps_projects['player']['dir'].'includes/faces.inc.php');
		if (!class_exists('db')) require(dirname(__FILE__).'/classes/db.class.php');

		$success=true;

		//database structure
		display('Install DB: structure');
		foreach ($aphps_projects as $id => $project) {
			if (isset($project['system']) && $project['system']) $currentVersion=$aphpsVersion;
			else $currentVersion=$version;
			if (isset($project['dir'])) {
				display('Project '.$id);
				$success=$success && $this->installDb($project['dir'].'db/structure/',$currentVersion);
			}
		}

		//forms
		display('Install Forms');
		foreach ($aphps_projects as $id => $project) {
			display('Forms for project '.$id);
			$success=$success && $this->installForms($project);
		}

		//database data
		display('Install DB: content');
		foreach ($aphps_projects as $id => $project) {
			if ($project['system']) $currentVersion=$aphpsVersion;
			else $currentVersion=$version;
			if (isset($project['dir'])) {
				display('Project '.$id);
				$success=$success && $this->installDb($project['dir'].'db/content/',$currentVersion);
			}
		}

		display('Install Links');
		$this->installLinks();
		display('Install Access Control');
		$this->installConfig('access');
		display('Install Roles');
		$this->installConfig('roles');
		display('Install Menus');
		$this->installConfig2('menus');
		if (isset($aphps_projects['fwkpar'])) {
			display('Install Parameters');
			$this->installConfig2('parameters');
		}
		if (isset($aphps_projects['templates'])) {
			display('Install Templates');
			$this->installSingleTable('templates');
		}
		display('Installation Complete');
		
		return $success;
	}

	function updateVersions() {
		update_option("zing_apps_player_version",ZING_APPS_PLAYER_VERSION);
	}

	function installDb($dir,$currentVersion) {
		$success=true;
		$db=new aphpsDb();
		$files=array();
		$execs=array();

		if (is_dir($dir)) {
			if ($handle = opendir($dir)) {
				if (!$currentVersion) { //look for highest baseline
					$baselineVersion='';
					while (false !== ($file = readdir($handle))) {
						if (strstr($file,".sql") && strstr($file,"init-")) {
							$f=explode("-",$file);
							$v=str_replace(".sql","",$f[1]);
							if ($v > $baselineVersion) $baselineVersion=$v;
						}
					}
				} else {
					$baselineVersion=$currentVersion;
				}
				closedir($handle);
			}

			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if (strstr($file,".sql") && !strstr($file,"init-")) {
						$f=explode("-",$file);
						$v=str_replace(".sql","",$f[1]);
						if ($baselineVersion < $v) {
							$files[]=array($dir.$file,$v);
						}
					} elseif (($file!='index.php') && strstr($file,".php")) {
						$f=explode("-",$file);
						$v=str_replace(".php","",$f[1]);
						if ($baselineVersion < $v) {
							$execs[]=$dir.$file;
						}
					}
				}
				closedir($handle);
				asort($files);
				if(!$currentVersion && $baselineVersion) array_unshift($files,array($dir.'init-'.$baselineVersion.'.sql',$baselineVersion));
				asort($execs);
			}
		}

		if (count($files) > 0) {
			display('Loading DB scripts from '.$dir);
			display('Baseline version='.$baselineVersion);

			foreach ($files as $afile) {
				list($file,$v)=$afile;
				display('Process '.$file);
				$file_content = file($file);
				$query = "";
				$success=$success && $db->executeScript($file_content,true);
			}
		}

		//Running scripts
		if ($success && count($execs) > 0) {
			display('Running scripts at '.$dir);
			foreach ($execs as $exec) {
				require($exec);
			}
		}

		return $success;
	}

	function installForms($project) {
		if (isset($project['dir']) && is_dir($project['dir'].'forms')) {
			display('Load forms');
			zing_apps_player_load($project['dir'].'forms/');
		}
		return true;
	}

	function installLinks() {
		global $aphps_projects;
		$db=new aphpsDb();
		foreach ($aphps_projects as $id => $project) {
			if (isset($project['system']) && $project['system']) {
				$fileName=$project['dir'].'config/links.xml';
				if (file_exists($fileName) && ($xmlf=simplexml_load_file($fileName))) {
					display($id.': '.$fileName);
					foreach ($xmlf->children() as $form) {
						$formName=(string)$form->attributes()->formname;
						$query=sprintf("delete from ##flink where formin=(select id from ##faces where name=%s)",qs($formName));
						$db->update($query);
						foreach ($form->children() as $link) {
							$data=(array)$link;
							foreach ($link as $f => $field) {
								if ($field->attributes()->type=='comment') unset($data[$f]);
							}
							$db->insertRecord('flink',null,$data);
						}
					}
				}
			}
		}
		return true;
	}

	function installSingleTable($type) {
		global $aphps_projects;
		$db=new aphpsDb();
		foreach ($aphps_projects as $id => $project) {
			if (!isset($project['dir'])) continue;
			$fileName=$project['dir'].'config/'.$type.'.xml';
			if (file_exists($fileName) && ($xmlf=simplexml_load_file($fileName))) {
				$target=(string)$xmlf->attributes()->target;
				$key=(string)$xmlf->attributes()->key;
				$overWrite=(string)$xmlf->attributes()->overwrite;
				display($id.': '.$fileName);
				foreach ($xmlf->children() as $child) {
					$data=(array)$child;
					$idField=false;
					$keyValue=$child->$key;
					foreach ($data as $f => $v) {
						//need to entity decode?						
					}
					if (!$db->readRecord($target,array($key=>$keyValue))) $db->insertRecord($target,null,$data);
					elseif ($overWrite) $db->updateRecord($target,array($key=>$keyValue),$data);
				}
			}
		}
		return true;
	}
	
	function installConfig($type) {
		global $aphps_projects;
		$db=new aphpsDb();
		foreach ($aphps_projects as $id => $project) {
			if (!isset($project['dir'])) continue;
			$fileName=$project['dir'].'config/'.$type.'.xml';
			if (file_exists($fileName) && ($xmlf=simplexml_load_file($fileName))) {
				$target=(string)$xmlf->attributes()->target;
				display($id.': '.$fileName);
				$query=sprintf("delete from ##%s where project=%s",$target,qs($id));
				$db->update($query);
				foreach ($xmlf->children() as $child) {
					$data=(array)$child;
					$idField=false;
					foreach ($child as $f => $field) {
						if ($field->attributes()->type=='comment') unset($data[$f]);
						elseif ($field) $data2[$f]=$field;
						if (strtoupper($f)=='ID') {
							$idField=$field;
						}
					}
					if ($idField===false) $db->insertRecord($target,null,$data);
					elseif (!$db->readRecord($target,array('ID'=>$idField))) $db->insertRecord($target,null,$data);
					else $db->updateRecord($target,array('ID'=>$idField),$data);
				}
			}
		}
		return true;
	}

	function installConfig2($type) {
		global $aphps_projects;
		$db=new aphpsDb();
		foreach ($aphps_projects as $id => $project) {
			if (!isset($project['dir'])) continue;
			$fileName=$project['dir'].'config/'.$type.'.xml';
			if (file_exists($fileName) && ($xmlf=simplexml_load_file($fileName))) {
				$entity1=(string)$xmlf->attributes()->targetgroup;
				$entity2=(string)$xmlf->attributes()->targetitem;
				$key=(string)$xmlf->attributes()->key;
				$query=sprintf("delete from ##%s where %s in (select ID from ##%s where project=%s)",$entity2,$key,$entity1,qs($id));
				$db->update($query);
				$query=sprintf("delete from ##%s where project=%s",$entity1,qs($id));
				$db->update($query);
				foreach ($xmlf->children() as $child) {
					$group=(array)$child;
					unset($group['items']);
					$keyValue=$db->insertRecord($entity1,null,$group);
					foreach ($child->items->children() as $child2) {
						$item=(array)$child2;
						$item[$key]=$keyValue;
						$db->insertRecord($entity2,null,$item);
					}
				}
			}
		}
		return true;
	}

}