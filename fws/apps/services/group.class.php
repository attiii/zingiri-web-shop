<?php
class zfgroup extends zfForm {
	function init() {
		$this->orderKeys="`SORTORDER`,`NAME`";
		$this->maxRows=999;
	}
	
	function sortlist() {
		$this->ajaxUpdateURL=zurl('index.php?page=ajax&wscr=group_sort');
	}

	function postPrepare($success) {
		if ($this->action == 'delete') {
			$db=new db();
			if ($db->select("select id from ##category where groupid=".qs($this->recid))) {
				$this->errorMessage=z_('groupadmin104');
				return false;
			}
		}
		return $success && true;
	}
} 
?>