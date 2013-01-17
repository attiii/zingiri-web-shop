<?php
global $integrator;
$integrator=new integrator();

class integrator {
	var $prefix;
	var $wpAdmin=false;
	var $wpCustomer=false;

	function integrator() {
		global $wpdb;
		$this->prefix=$wpdb->prefix."zing_";
		$this->checkSingleSignOn();
	}

	function checkSingleSignOn() {
		if (get_option('zing_ws_login') == "WP") {
			$this->wpAdmin=true;
			$this->wpCustomer=true;
		}
	}

	function sync() {
		global $wpdb;

		$this->checkSingleSignOn();
		if (!$this->wpAdmin) return;

		$wpdb->show_errors();

		//sync Web Shop to Wordpress - Wordpress is master so we're not changing roles in Wordpress
		$query="select * from `##customer`";
		$query=str_replace("##",$this->prefix,$query);
		$sql = mysql_query($query) or die(mysql_error());
		while ($row = mysql_fetch_array($sql)) {
			$query2=sprintf("SELECT `ID` FROM `".$wpdb->prefix."users` WHERE `user_login`='%s'",$row['LOGINNAME']);
			$sql2 = mysql_query($query2) or die(mysql_error());
			if (mysql_num_rows($sql2) == 0) {
				$data=array();
				$data['user_login']=$row['LOGINNAME'];
				$data['user_nicename']=$row['INITIALS'].' '.$row['LASTNAME'];
				$data['first_name']=$row['INITIALS'];
				$data['last_name']=$row['LASTNAME'];
				$data['user_email']=$row['EMAIL'];
				$data['user_pass']='';

				$this->createWpUser($data,'subscriber');
				if ($row['GROUP']=='ADMIN') $this->setAdmin($row['ID'],$row['LOGINNAME']);
			} else {
				if ($row['GROUP']=='ADMIN') $this->setAdmin($row['ID'],$row['LOGINNAME']);
				else $this->unsetAdmin($row['ID'],$row['LOGINNAME']);
			}
		}
		//sync Wordpress to Web Shop - Wordpress is master so we're updating roles in Web Shop
		$query="select * from `##users`";
		$query=str_replace("##",$wpdb->prefix,$query);
		$sql = mysql_query($query) or die(mysql_error());
		while ($row = mysql_fetch_array($sql)) {
			$user=new WP_User($row['ID']);
			if (!isset($user->data->first_name)) $user->data->first_name=$user->data->display_name;
			if (!isset($user->data->last_name)) $user->data->last_name=$user->data->display_name;

			if ($user->has_cap('administer_web_shop')) {
				$group='ADMIN';
			} else {
				$group='CUSTOMER';
			}
			$query2=sprintf("SELECT `ID` FROM `".$this->prefix."customer` WHERE `LOGINNAME`='%s'",$user->data->user_login);
			$sql2 = mysql_query($query2) or die(mysql_error());
			if (mysql_num_rows($sql2) == 0) {
				$query2="INSERT INTO `".$this->prefix."customer` (`LOGINNAME`, `INITIALS`, `LASTNAME`, `EMAIL`, `GROUP`, `DATE_CREATED`) VALUES";
				$query2.="('".$user->data->user_login."', '".$user->data->first_name."', '".$user->data->last_name."', '".$user->data->user_email."', '".$group."', '".date("Y-m-d")."')";
				$wpdb->query($query2);
			} else {
				$query2=sprintf("UPDATE `".$this->prefix."customer` SET `GROUP`='%s',`EMAIL`='%s' WHERE `LOGINNAME`='%s'",$group,$user->data->user_email,$user->data->user_login);
				$wpdb->query($query2);
			}
		}
		global $current_user;
		get_currentuserinfo();
		zing_login($current_user->user_login);
	}

	function createWpUser($user,$role) {
		global $wpdb;
		$user['role']=$role;
		$id=wp_insert_user($user);
	}

	function updateWpUser($user) {
		global $wpdb;
		$olduser=get_userdatabylogin($user['user_login']);
		$id=$user['ID']=$olduser->ID;
		$user['user_pass']=wp_hash_password($user['user_pass']);
		wp_insert_user($user);
	}

	function showUsers() {
		global $wpdb;
		//display list
		$query="select `##users`.* from `##users`,`##zing_customer` where `##users`.`user_login`=`##zing_customer`.`LOGINNAME`";
		$query=str_replace("##",$wpdb->prefix,$query);
		$sql = mysql_query($query) or die(mysql_error());
		echo '<table class="datatable">';
		while ($row = mysql_fetch_array($sql)) {
			$user=new WP_User($row['ID']);
			//print_r($user);
			echo '<tr><td style="border-right: 1px solid #808080">'.$row['ID'].'</td><td style="border-right: 1px solid #808080">'.$row['user_login'].'</td><td style="border-right: 1px solid #808080">'.$row['user_email'];
			//$level=get_usermeta($row['ID'],$wpdb->prefix.'user_level');
			echo '</td><td>';
			$caps='';
			foreach ($user->wp_capabilities as $cap => $status) {
				if ($status) $caps.=($caps ? ',' : '').$cap;
			}
			echo $caps;
			echo '</td></tr>';
		}
		echo '</table>';
	}

	function loggedIn() {
		if ($this->wpAdmin && is_user_logged_in()) return true;
		else return false;
	}

	function isAdmin() {
		if ($this->wpAdmin && (current_user_can('edit_plugins')  || current_user_can('edit_pages'))) return true;
		else return false;
	}

	function loginWpUser($login,$pass) {
		wp_signon(array('user_login'=>$login,'user_password'=>$pass));
	}

	function setAdmin($id,$user_login='') {
		if (!$this->wpAdmin) return;
		if (!$user_login) {
			$db=new db();
			$db->select("select loginname from ##customer where id=".qs($id));
			$db->next();
			$user_login=$db->get('loginname');
		}
		$user=get_userdatabylogin($user_login);
		$wpUser=new WP_User($user->ID);
		$wpUser->add_role('administer_web_shop');
	}

	function unsetAdmin($id,$user_login='') {
		if (!$this->wpAdmin) return;
		if (!$user_login) {
			$db=new db();
			$db->select("select loginname from ##customer where id=".qs($id));
			$db->next();
			$user_login=$db->get('loginname');
		}
		$user=get_userdatabylogin($user_login);
		$wpUser=new WP_User($user->ID);
		$wpUser->remove_role('administer_web_shop');
	}
}

?>