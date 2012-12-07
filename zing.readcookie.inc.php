<?php
// open the cookie and read the fortune ;-)
if (isset($_COOKIE['fws_cust'])) {
	$fws_cust = explode("#", $_COOKIE['fws_cust']);
	$name = aphpsSanitize($fws_cust[0]);
	$customerid = intval($fws_cust[1]);
	$md5pass = aphpsSanitize($fws_cust[2]);
}
if (!isset($_COOKIE['fws_cust'])) {
	// you're not logged in, so you're a guest. let's see if you already have a session id
	if (!isset($_COOKIE['fws_guest'])) {
		$fws_guest = create_sessionid(8); // create a sessionid of 8 numbers, assuming a shop will never get 10.000.000 customers it's always a non existing customer id
		setcookie ("fws_guest", $fws_guest, time()+172800, '/');
		$customerid = $fws_guest;
		$_COOKIE['fws_guest']=$customerid;
	} else {
		$customerid = intval($_COOKIE['fws_guest']);
	}
}
