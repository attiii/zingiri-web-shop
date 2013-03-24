<?php
class fwktec_package {
	var $test;

	function __construct($test=false) {
		$this->test=$test;
	}

	function exportLinks() {
		global $aphps_projects;
		$db=new aphpsDb();
		$db2=new aphpsDb();
		$db3=new aphpsDb();

		$links=array();
		foreach ($aphps_projects as $id => $project) {
			$xml = new SimpleXMLElement('<links/>');
			$found=false;
			if ($c=$db->select(sprintf("select ID,NAME from ##faces where project=%s",qs($id)))) {
				while ($form=$db->next()) {
					$xmlForm=$xml->addChild('form');
					$xmlForm->addAttribute('formname',$db->get('name'));
					if ($db2->select(sprintf("select *,(select name from ##faces where ##faces.id=##flink.formin) as FORMINNAME,(select name from ##faces where ##faces.id=##flink.formout) as FORMOUTNAME from ##flink where formin=%s",qs($form['ID'])))) {
						while ($link=$db2->next()) {
							$found=true;
							$link2=$link;
							foreach (array('ID','DATE_CREATED','DATE_UPDATED') as $field) unset($link2[$field]);
							if (in_array(serialize($link2),$links)) { //duplicate
								$db3->update(sprintf("delete from ##flink where id=%s",qs($db2->get('id'))));
								continue;
							}
							$xmlLink = $xmlForm->addChild('link');
							foreach ($link as $f => $v) {
								if (!in_array(strtoupper($f),array('ID','DATE_CREATED','DATE_UPDATED')) && $v) {
									$xmlChild=$xmlLink->addChild(strtolower($f), htmlentities($v));
									if (in_array(strtolower($f),array('forminname','formoutname'))) $xmlChild->addAttribute('type','comment');
								}
							}

							$links[]=serialize($link2);
						}
					}
				}
			}
			if ($found) {
				if (!isset($project['system']) || !$project['system']) echo 'Warning, project '.$id.' is not set to System, no export done<br />';
				$this->save($id,'links',$xml,true);
			}
		}
	}

	function exportAcl() {
		global $aphps_projects;
		$db=new aphpsDb();
		$db2=new aphpsDb();
		$db3=new aphpsDb();

		foreach ($aphps_projects as $id => $project) {
			$xml = new SimpleXMLElement('<acls/>');
			$xml->addAttribute('target','faccess');
			$found=false;
			if ($db->select(sprintf("select * from ##faccess where project=%s",qs($id)))) {
				while ($acl=$db->next()) {
					$found=true;
					$xmlChild = $xml->addChild('acl');
					//$xmlChild->addAttribute('target','faccess');
					foreach ($acl as $f => $v) {
						if (!in_array(strtoupper($f),array('ID','DATE_CREATED','DATE_UPDATED'))) {
							$xmlSubChild=$xmlChild->addChild(strtolower($f), $v);
						}
					}
					if (is_numeric($db->get('formid')) && ($db->get('formid')>0)) {
						if ($db2->select(sprintf("select name from ##faces where id=%s",qs($db->get('formid')))) && $db2->next()) {
							$xmlSubChild=$xmlChild->addChild('formname',$db2->get('name'));
							$xmlSubChild->addAttribute('type','comment');
						}
					}
					if (is_numeric($db->get('roleid')) && ($db->get('roleid')>0)) {
						if ($db2->select(sprintf("select name from ##frole where id=%s",qs($db->get('roleid')))) && $db2->next()) {
							$xmlSubChild=$xmlChild->addChild('rolename',$db2->get('name'));
							$xmlSubChild->addAttribute('type','comment');
						}
					}
				}
			}
			if ($found) $this->save($id,'access',$xml,true);
		}
	}

	function exportRoles() {
		global $aphps_projects;
		$db=new aphpsDb();
		$db2=new aphpsDb();
		$db3=new aphpsDb();

		foreach ($aphps_projects as $id => $project) {
			$xml = new SimpleXMLElement('<roles/>');
			$xml->addAttribute('target','frole');
			$found=false;
			if ($db->select(sprintf("select * from ##frole where project=%s",qs($id)))) {
				while ($acl=$db->next()) {
					$found=true;
					$xmlChild = $xml->addChild('role');
					foreach ($acl as $f => $v) {
						if (!in_array(strtoupper($f),array('DATE_CREATED','DATE_UPDATED')) && $v) $xmlChild->addChild(strtolower($f), $v);
					}
				}
			}
			if ($found) $this->save($id,'roles',$xml,true);
		}
	}

	function exportConfig2($type,$entity1,$entity2,$key) {
		global $aphps_projects;
		$db=new aphpsDb();
		$db2=new aphpsDb();
		$db3=new aphpsDb();

		foreach ($aphps_projects as $id => $project) {
			$xml = new SimpleXMLElement('<'.$type.'/>');
			$xml->addAttribute('targetgroup',$entity1);
			$xml->addAttribute('targetitem',$entity2);
			$xml->addAttribute('key',$key);
			$found=false;
			if ($db->select(sprintf("select * from ##%s where project=%s",$entity1,qs($id)))) {
				while ($group=$db->next()) {
					$found=true;
					$xmlGroup = $xml->addChild('group');
					foreach ($group as $f => $v) {
						if (!in_array(strtoupper($f),array('ID','DATE_CREATED','DATE_UPDATED')) && $v) $xmlGroup->addChild(strtolower($f), $v);
					}
					$xmlItems=$xmlGroup->addChild('items');
					if ($db2->select(sprintf("select * from ##%s where %s=%s",$entity2,$key,qs($db->get('id'))))) {
						while ($item=$db2->next()) {
							$xmlItem=$xmlItems->addChild('item');
							foreach ($item as $f => $v) {
								if (!in_array(strtoupper($f),array('ID','DATE_CREATED','DATE_UPDATED',strtoupper($key))) && $v) $xmlItem->addChild(strtolower($f), htmlentities($v));
							}
						}
					}
				}
			}
			if ($found) $this->save($id,$type,$xml,true);
		}
	}

	function exportConfig3($id,$type,$entity1,$entity2,$key) { //same as above but without project
		$db=new aphpsDb();
		$db2=new aphpsDb();
		$db3=new aphpsDb();

		$xml = new SimpleXMLElement('<'.$type.'/>');
		$xml->addAttribute('targetgroup',$entity1);
		$xml->addAttribute('targetitem',$entity2);
		$xml->addAttribute('key',$key);
		$found=false;
		if ($db->select(sprintf("select * from ##%s",$entity1))) {
			while ($group=$db->next()) {
				$found=true;
				$xmlGroup = $xml->addChild('group');
				foreach ($group as $f => $v) {
					if (!in_array(strtoupper($f),array('DATE_CREATED','DATE_UPDATED')) && $v) $xmlGroup->addChild(strtolower($f), $v);
				}
				$xmlItems=$xmlGroup->addChild('items');
				if ($db2->select(sprintf("select * from ##%s where %s=%s",$entity2,$key,qs($db->get('id'))))) {
					while ($item=$db2->next()) {
						$xmlItem=$xmlItems->addChild('item');
						foreach ($item as $f => $v) {
							if (!in_array(strtoupper($f),array('DATE_CREATED','DATE_UPDATED',strtoupper($key))) && $v) $xmlItem->addChild(strtolower($f), htmlentities($v));
						}
					}
				}
			}
		}
		if ($found) $this->save($id,$type,$xml,true);
	}

	function exportSingleTable($type,$entity,$key,$overWrite) { //same as above but without project
		global $aphps_projects;
		$db=new aphpsDb();
		$db2=new aphpsDb();
		$db3=new aphpsDb();

		foreach ($aphps_projects as $id => $project) {
			$xml = new SimpleXMLElement('<'.$type.'/>');
			$xml->addAttribute('target',$entity);
			$xml->addAttribute('key',strtolower($key));
			$xml->addAttribute('overwrite',intval($overWrite));
			$found=false;
			if ($db->select(sprintf("select * from ##%s where project=%s",$entity,qs($id)))) {
				while ($acl=$db->next()) {
					$found=true;
					$xmlChild = $xml->addChild('item');
					//$xmlChild->addAttribute('key',$db->get($key));
					foreach ($acl as $f => $v) {
						if (!in_array(strtoupper($f),array('ID','DATE_CREATED','DATE_UPDATED')) && $v) $xmlChild->addChild(strtolower($f), htmlentities($v));
					}
				}
			}
			if ($found) $this->save($id,$type,$xml,true);
		}
	}

	function save($id,$name,$xml,$save=false) {
		global $aphps_projects;
		//Format XML to save indented tree rather than one line
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());

		if (isset($aphps_projects[$id]['srcdir'])) $fileName=$aphps_projects[$id]['srcdir'].'config/'.$name.'.xml';
		elseif (defined('APHPS_DEV_DIR')) $fileName=APHPS_DEV_DIR.($id=='player' ? 'fwkfor' : $id).'/config/'.$name.'.xml';
		if (!$this->test && $save && APHPS_LOCAL_SAVE && $fileName) {
			echo 'Saved to '.$fileName.'<br />';
			$dom->save($fileName);
		} else {
			echo 'Test save to '.$fileName.'<br />';
			echo htmlentities($dom->saveXML());
			echo '<br /><br />';
		}

	}

}