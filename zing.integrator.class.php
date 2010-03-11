<?php

$integrator=new integrator();

class integrator {
	var $prefix;
	var $wpAdmin=false;
	var $wpCustomer=false;

	function integrator() {
		global $wpdb;
		$this->prefix=$wpdb->prefix."zing_";
		if (get_option('zing_ws_login') == "WP") {
			$this->wpAdmin=true;
			$this->wpCustomer=true;
		}
	}

	function sync() {
		global $wpdb;

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
//$query2="INSERT INTO `".$wpdb->prefix."users` (`user_login`, `user_nicename`, `user_email`, `user_pass`, `user_registered`, `user_status`, `display_name`) VALUES";
//$query2.="('".$row['LOGINNAME']."', '".$row['INITIALS'].' '.$row['LASTNAME']."', '".$row['EMAIL']."', '".''."', '".$row['DATE_CREATED']."', '0', '".$row['INITIALS']." 	".$row['LASTNAME']."')";
				$data=array();
				$data['user_login']=$row['LOGINNAME'];
				$data['user_nicename']=$row['INITIALS'].' '.$row['LASTNAME'];
				$data['user_firstname']=$row['INITIALS'];
				$data['user_lastname']=$row['LASTNAME'];
				$data['user_email']=$row['EMAIL'];
				$data['user_pass']='';

				if ($row['GROUP']=='ADMIN') $this->createWpUser($data,'editor');
				else $this->createWpUser($data,'subscriber');
			}
		}
		//sync Wordpress to Web Shop - Wordpress is master so we're updating roles in Web Shop
		//$query="select * from `##users`,`##usermeta` where `##users`.`ID`=`##usermeta`.`user_id` and `##usermeta`.`meta_key`='wp_user_level'";
		$query="select * from `##users`";
		$query=str_replace("##",$wpdb->prefix,$query);
		$sql = mysql_query($query) or die(mysql_error());
		while ($row = mysql_fetch_array($sql)) {
			$user=new WP_User($row['ID']);
			if (!isset($user->data->first_name)) $user->data->first_name=$user->data->display_name;
			if (!isset($user->data->last_name)) $user->data->last_name=$user->data->display_name;
			
			//if ($user->data['meta_value'] >= 8 || $user->data['meta_value'] >= 5) { //administrator role or editor role
			if ($user->has_cap('level_5')) {	
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
				$query2=sprintf("UPDATE `".$this->prefix."customer` SET `GROUP`='%s' WHERE `LOGINNAME`='%s'",$group,$user->data->user_login);
				$wpdb->query($query2);
			}
		}
	}

	function createWpUser($user,$role) {
		require_once(ABSPATH.'wp-includes/registration.php');
		global $wpdb;
		$user['role']=$role;
		//if (!isset($row['DATE_CREATED'])) $row['DATE_CREATED']=date('Y-m-d');
		$id=wp_insert_user($user);
		/*
		$query2="INSERT INTO `".$wpdb->prefix."users` (`user_login`, `user_nicename`, `user_email`, `user_pass`, `user_registered`, `user_status`, `display_name`) VALUES";
		$query2.="('".$row['LOGINNAME']."', '".$row['INITIALS'].' '.$row['LASTNAME']."', '".$row['EMAIL']."', '".''."', '".$row['DATE_CREATED']."', '0', '".$row['INITIALS']." 	".$row['LASTNAME']."')";
		echo $query2;
		$sql2 = mysql_query($query2) or die(mysql_error());
		$id=mysql_insert_id();
		*/
	}

	function updateWpUser($user,$role) {
		require_once(ABSPATH.'wp-includes/registration.php');
		global $wpdb;
		$olduser=get_userdatabylogin($user['user_login']);
		$id=$user['ID']=$olduser->ID;
		$user['role']=$role;
		//if (!isset($row['DATE_UPDATED'])) $row['DATE_UPDATED']=date('Y-m-d');
		$user['user_pass']=wp_hash_password($user['user_pass']);
		wp_insert_user($user);
	}

	function showUsers() {
		global $wpdb;
		//display list
		$query="select `##users`.* from `##users`,`##zing_customer` where `##users`.`user_login`=`##zing_customer`.`LOGINNAME`";
		$query=str_replace("##",$wpdb->prefix,$query);
		$sql = mysql_query($query) or die(mysql_error());
		echo '<table>';
		while ($row = mysql_fetch_array($sql)) {
			$user=new WP_User($row['ID']);
			echo '<tr><td style="border-right: 1px solid #808080">'.$row['ID'].'</td><td style="border-right: 1px solid #808080">'.$row['user_login'].'</td><td style="border-right: 1px solid #808080">'.$row['user_email'];
			//$level=get_usermeta($row['ID'],$wpdb->prefix.'user_level');
			if ($user->has_cap('level_8')) echo '</td><td>administrator';
			elseif ($user->has_cap('level_5')) echo '</td><td>editor';
			else echo '</td><td>subscriber';
			echo '</td></tr>';
		}
		echo '</table>';
	}

	function wpToWs($row) {

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
}

?>