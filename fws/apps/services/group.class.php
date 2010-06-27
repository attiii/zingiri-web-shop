<?php
class zfgroup extends zfForm {
	function init() {
		$this->orderKeys="`SORTORDER`,`NAME`";
	}
	
	function sortlist() {
		$this->ajaxUpdateURL=ZING_URL.'fws/ajax/group_sort.php';
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