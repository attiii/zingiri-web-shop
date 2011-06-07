<?php 
function wsCid() {
	global $customerid;
	if (IsAdmin() && $_SESSION['zing_session']['customerid']) $cid=$_SESSION['zing_session']['customerid'];
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
	Global $dbtablesprefix,$integrator;

	if ($integrator->loggedIn()) return true;

	if (!isset($_COOKIE['fws_cust'])) { return false; }
	$fws_cust = explode("#", $_COOKIE['fws_cust']);
	$customerid = $fws_cust[1];
	$md5pass = $fws_cust[2];
	if (is_null($customerid)) { return false; }
	$f_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . $customerid;
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
	return false;
}
