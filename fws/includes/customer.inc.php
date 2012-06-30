<?php
function wsCid() {
	global $customerid;
	if (IsAdmin() && isset($_SESSION['zing_session']['customerid']) && $_SESSION['zing_session']['customerid']) $cid=$_SESSION['zing_session']['customerid'];
	else $cid=$customerid;
	return $cid;
}

function getCustomerName($id) {
	$name='';
	$db=new db();
	$query = "SELECT `INITIALS`,`MIDDLENAME`,`LASTNAME` FROM `##customer` WHERE `ID` = " . qs($id);
	if ($db->select($query)) {
		$db->next();
		$name=$db->get('INITIALS').' '.$db->get('LAStNAME');
	}
	return $name;
}

function wsGetCustomerByLoginName($loginName) {
	$db=new db();
	$query = "SELECT * FROM `##customer` WHERE `LOGINNAME` = " . qs($loginName);
	if ($db->select($query) && $c=$db->next()) {
		return $c;
	}
	return false;
}

function wsNoRegistration() {
	if (!wsSetting('require_registration') && $_GET['registration']==0) return 1;
	else return 0;
}

/*
 * Is the customer or admin logged in?
 *
 * - return 0 if not logged in, 1 if logged in as admin or customer and 2 if logged in as guest
 */
//
function LoggedIn() {
	global $dbtablesprefix,$integrator;
	
	$loggedin=false;

	if ($integrator->loggedIn()) return true;

	if (isset($_COOKIE['fws_cust'])) {
		$fws_cust = explode("#", $_COOKIE['fws_cust']);
		$customerid = aphpsSanitize($fws_cust[1]);
		$md5pass = aphpsSanitize($fws_cust[2]);
		if (is_null($customerid)) { return false; }
		$f_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . qs($customerid);
		$f_sql = mysql_query($f_query) or die(mysql_error());
		if ($f_row = mysql_fetch_array($f_sql)) {
			if ($f_row['GROUP']=='GUEST') {
				if ($f_row[6] == GetUserIP()) return 2;
				else return false;
			} else {
				if (md5($f_row[2]) == $md5pass)
				{
					if ($f_row[6] == GetUserIP()) return true;
					else return false;
				} else
				{
					return false;
				}
			}
		}
	}

	return $loggedin;
}

function wsGetCustomerByEmail($email) {
	$db=new db();
	$query = "SELECT * FROM `##customer` WHERE `email` = " . qs($email);
	if ($db->select($query) && $c=$db->next()) {
		return $c;
	}
	return false;
}

function wsCreateCustomer($user_login,$first_name,$last_name,$user_email,$group='CUSTOMER') {
	$db=new db();
	$query="INSERT INTO `##customer` (`LOGINNAME`, `INITIALS`, `LASTNAME`, `EMAIL`, `GROUP`, `DATE_CREATED`) VALUES";
	$query.="('".$user_login."', '".$first_name."', '".$last_name."', '".$user_email."', '".$group."', '".date("Y-m-d")."')";
	if ($db->update($query)) return $db->getInsertId();
	else return false;
}

function wsGuestToCustomer($id) {
	global $dbtablesprefix;
	if (isset($_COOKIE['fws_guest'])) {
		$fws_cust = aphpsSanitize($_COOKIE['fws_guest']);
		$sessionid = $fws_cust; // read the sessionid

		// now check if this guest has products in his basket
		$update_query = "UPDATE `".$dbtablesprefix."basket` SET `CUSTOMERID` = ".qs($id)." WHERE CUSTOMERID = ".qs($sessionid);
		$update_sql = mysql_query($update_query) or die(mysql_error());

		// now kill the cookie
		setcookie("fws_guest", "", time() - 3600, '/');
	}
}

function wsLoginCustomer($id,$name,$pass='') {
	global $dbtablesprefix;

	//if (!$pass) $pass=rand(26);
	
	$cookie_data = $name.'#'.$id.'#'.md5($pass); //name userid and encrypted password

	// store IP
	$query = "UPDATE `".$dbtablesprefix."customer` SET `IP` = '".GetUserIP()."' WHERE `ID`=".$id;
	$sql = mysql_query($query) or die(mysql_error());

	// make acccesslog entry
	$query = sprintf("INSERT INTO ".$dbtablesprefix."accesslog (login, time, succeeded) VALUES(%s, '".date("F j, Y, g:i a")."', '1')", quote_smart($name ? $name : $id));
	$sql = mysql_query($query) or die(mysql_error());

	if(setcookie("fws_cust",$cookie_data, 0, '/')==TRUE) return true;
	else return false;
}
