<?php
class zfcustomer extends zfForm {
	function postSave($success=true) {
		if ($this->action == 'edit') {
			global $integrator;
			$login=$this->rec['LOGINNAME'];
			$initials=$this->rec['INITIALS'];
			$surname=$this->rec['LASTNAME'];
			$email=$this->rec['EMAIL'];
			//$pass1=$this->searchByFieldName('PASSWORD');
				
			if ($integrator->wpCustomer) {
				//$integrator->updateWpUser(array('user_pass'=>$pass1,'user_login'=>$login,'first_name'=>$initials,'last_name'=>$surname,'user_email'=>$email));
			//	$integrator->updateWpUser(array('user_login'=>$login,'first_name'=>$initials,'last_name'=>$surname,'user_email'=>$email));
			}
		} elseif ($this->action == 'add') {
			$_SESSION['zing_session']['customerid']=$this->recid;
		}
		return $success && true;
	}
}
?>