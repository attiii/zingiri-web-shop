<?php
// open the cookie and read the fortune ;-)
if (isset($_COOKIE['fws_cust'])) {
	$fws_cust = explode("-", $_COOKIE['fws_cust']);
	$name = $fws_cust[0];
	$customerid = $fws_cust[1];
	$md5pass = $fws_cust[2];
}
else {
	/*
	if (isset($_REQUEST['wsliveuserid']) && $_REQUEST['wsliveuserid']) {
		$db=new db();
		if ($db->select('select id from ##customer where id='.intval($_REQUEST['wsliveuserid']))) {
			echo 'here';
			$db->next();
			setcookie ("fws_guest", "", time() - 3600, '/');
			$cookie_data = $db->get('loginname').'-'.$db->get('id').'-'.md5($db->get('password'));
			setcookie ("fws_cust",$cookie_data, 0, '/');
			$db->update("UPDATE `##customer` SET `IP` = '".GetUserIP()."' WHERE `ID`=".intval($_REQUEST['wsliveuserid']));
		}
	}
	*/
}
if (!isset($_COOKIE['fws_cust'])) {
	// you're not logged in, so you're a guest. let's see if you already have a session id
	if (!isset($_COOKIE['fws_guest'])) {
		$fws_guest = create_sessionid(8); // create a sessionid of 8 numbers, assuming a shop will never get 10.000.000 customers it's always a non existing customer id
		setcookie ("fws_guest", $fws_guest, time()+3600, '/');
		$customerid = $fws_guest;
	} else {
		$customerid = $_COOKIE['fws_guest'];
	}
}
