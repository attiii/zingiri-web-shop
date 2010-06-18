<?php
class zfgroup extends zfForm {
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