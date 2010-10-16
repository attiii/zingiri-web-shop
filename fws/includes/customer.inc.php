<?php 
function wsCid() {
	global $customerid;
	if (IsAdmin() && $_SESSION['zing_session']['customerid']) $cid=$_SESSION['zing_session']['customerid'];
	else $cid=$customerid;
	return $cid;
}