<?php
class zfuserpassword extends zfForm {
	function postSave($success=true) {
		if ($this->action == 'edit') {
			global $integrator;
			$db=new db();
			if ($db->select("select loginname,email from ##customer where id=".$this->recid)) {
				$db->next();
				if (wsSetting('login_with_email')) $login=$db->get('email');
				else $login=$db->get('login');
			}
			$pass1=$this->searchByFieldName('PASSWORD');
				
			if ($integrator->wpCustomer) {
				$integrator->updateWpUser(array('user_pass'=>$pass1,'user_login'=>$login));
			}
		}
		return $success && true;
	}
}
?>