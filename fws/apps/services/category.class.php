<?php
class zfcategory extends zfForm {
	var $ajaxUpdateURL;
	
	function init() {
		$this->orderKeys="`SORTORDER`,`DESC`";
		$this->maxRows=999;
	}
	
	function sortlist() {
		$this->ajaxUpdateURL=ZING_URL.'fws/ajax/category_sort.php';
	}
	
	function postPrepare($success) {
		if ($this->action == 'delete') {
			$db=new db();
			if ($db->select("select id from ##product where catid=".qs($this->recid))) {
				$this->errorMessage=z_('groupadmin105');
				return false;
			}
		}
		return $success && true;
	}
	
} 
?>