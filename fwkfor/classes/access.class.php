<?php

if (!class_exists('zfAccess')) {
	class zfAccess {
		var $type;
		var $formid;
		var $action;
		var $filter;
		var $data;
		var $page;

		function zfAccess($formid,$type,$action,$filter,$data) {
			$this->formid=$formid;
			$this->page=$formid;
			$this->type=$type;
			$this->action=$action;
			$this->filter=$filter;
			$this->data=$data;
		}

		function allowed() {
			$allowed=false;
			
			//if (defined('ZING_APPS_BUILDER') && ZING_APPS_BUILDER) return true;
			if (defined('APHPS_ACCESS_CHECK_DISABLED') && APHPS_ACCESS_CHECK_DISABLED) return true;
			if (function_exists('faces_group')) $role=faces_group();
			elseif (!function_exists('faces_group') && ZING_CMS=='wp' && current_user_can('edit_plugins')) $role="ADMIN";
			else $role="GUEST";
			$roles=new aphpsDb();
			$query="select * from ##frole where name=".qs($role);
			if ($roles->select($query)) {
				$roles->next();
				$roleid=$roles->get('id');
				switch ($this->type)
				{
					case "form":
					case "list":
						$access=new aphpsDb();
						//check on role, form, action & rules
						$query="select id,rules,allowed from ##faccess where (`type`<>2 or `type` is null) and (roleid=0 or roleid=".$roleid.") and formid=".qs($this->formid)." and `action`=".qs($this->action)." and (rules is not null and rules<>'')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							$rules=explode(",",$access->get('rules'));
							$check=$this->checkRules($rules);
							if ($access->get('allowed')) $allowed=$check;
							else $allowed=!$check;
							break;
						}
						//check on role, form, any action & rules
						$query="select id,rules,allowed from ##faccess where (`type`<>2 or `type` is null) and (roleid=0 or roleid=".$roleid.") and formid=".qs($this->formid)." and (`action`='0' or `action`='*' or `action` is null) and (rules is not null and rules<>'')";
						if (!$allowed && $access->select($query)) {
							$row=$access->next();
							print_r($row);
							$rules=explode(",",$access->get('rules'));
							$check=$this->checkRules($rules);
							if ($access->get('allowed')) $allowed=$check;
							else $allowed=!$check;
							break;
						}
						//check on role, form & action
						$query="select id,allowed from ##faccess where (`type`<>2 or `type` is null) and (roleid=0 or roleid=".$roleid.") and formid=".qs($this->formid)." and `action`=".qs($this->action)." and (rules is null or rules='')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							if ($access->get('allowed')) $allowed=true;
							else $allowed=false;

							break;
						}
						//check on role & form
						$query="select id,allowed from ##faccess where (`type`<>2 or `type` is null) and (roleid=0 or roleid=".$roleid.") and formid=".qs($this->formid)." and (`action`='0' or `action`='*' or `action` is null) and (rules is null or rules='')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							if ($access->get('allowed')) $allowed=true;
							else $allowed=false;
							break;
						}
						//check on role & any form
						$query="select id,allowed from ##faccess where (`type`<>2 or `type` is null) and (roleid=0 or roleid is null or roleid=".$roleid.") and (formid=0 or formid is null) and (`action`='0' or `action`='*' or `action` is null) and (rules is null or rules='')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							if ($access->get('allowed')) $allowed=true;
							else $allowed=false;
							break;
						}
						break;
					case "edit":
					case "summary":
					case "face":
						if ($role != 'ADMIN') $allowed=false;
						break;
					case "page":
						$access=new aphpsDb();
						//check on role, page, action & rules
						$query="select id,rules,allowed from ##faccess where `type`=2 and (roleid=0 or roleid=".$roleid.") and page=".qs($this->page)." and `action`=".qs($this->action)." and (rules is not null and rules<>'')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							$rules=explode(",",$access->get('rules'));
							$check=$this->checkRules($rules);
							if ($access->get('allowed')) $allowed=$check;
							else $allowed=!$check;
							break;
						}
						//check on role, page, any action & rules
						$query="select id,rules,allowed from ##faccess where `type`=2 and (roleid=0 or roleid=".$roleid.") and page=".qs($this->page)." and (`action`='0' or `action`='*' or `action` is null) and (rules is not null and rules<>'')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							$rules=explode(",",$access->get('rules'));
							$check=$this->checkRules($rules);
							if ($access->get('allowed')) $allowed=$check;
							else $allowed=!$check;
							break;
						}
						//check on role, page & action
						$query="select id,allowed from ##faccess where `type`=2 and (roleid=0 or roleid=".$roleid.") and page=".qs($this->page)." and `action`=".qs($this->action)." and (rules is null or rules='')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							if ($access->get('allowed')) $allowed=true;
							else $allowed=false;

							break;
						}
						//check on role & page
						$query="select id,allowed from ##faccess where `type`=2 and (roleid=0 or roleid=".$roleid.") and page=".qs($this->page)." and (`action`='0' or `action`='*' or `action` is null) and (rules is null or rules='')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							if ($access->get('allowed')) $allowed=true;
							else $allowed=false;
							break;
						}
						//check on role & any page
						$query="select id,allowed from ##faccess where `type`=2 and (roleid=0 or roleid is null or roleid=".$roleid.") and page='' and (`action`='0' or `action`='*' or `action` is null) and (rules is null or rules='')";
						if (!$allowed && $access->select($query)) {
							$access->next();
							if ($access->get('allowed')) $allowed=true;
							else $allowed=false;
							break;
						}
						break;
				}
			}
			return $allowed;
		}

		function checkRules($rules) {
			$failed=0;
			foreach ($rules as $rule) {

				if (strstr($rule,':')) {
					list($i,$v)=explode(":",$rule);
					if (function_exists($v)) {
						$result=$v();
					}
					if (isset($this->filter[$i]) && $this->filter[$i]!=$result) $failed++;
					if (isset($this->data[$i]) && $this->data[$i]!=$result) $failed++;
					if (isset($this->data[strtoupper($i)]) && $this->data[strtoupper($i)]!=$result) $failed++;
				} elseif (strstr($rule,'<>')) {
					list($i,$v)=explode("<>",$rule);
					if (function_exists($v)) {
						$result=$v();
					}
					if (isset($this->filter[$i]) && $this->filter[$i]==$result) $failed++;
					if (isset($this->data[$i]) && $this->data[$i]==$result) $failed++;
					if (isset($this->data[strtoupper($i)]) && $this->data[strtoupper($i)]==$result) $failed++;
						
				}
			}
			if ($failed > 0) $check=false;
			else $check=true;
			return $check;

		}
	}
}
