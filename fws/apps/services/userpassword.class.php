<?php
class zfuserpassword extends zfForm {
	function postSave($success=true) {
		if ($this->action == 'edit') {
			global $integrator;
			$login=$this->rec['LOGINNAME'];
			$pass1=$this->searchByFieldName('PASSWORD');
				
			if ($integrator->wpCustomer) {
				$integrator->updateWpUser(array('user_pass'=>$pass1,'user_login'=>$login));
			}
		}
		return $success && true;
	}
}
?>