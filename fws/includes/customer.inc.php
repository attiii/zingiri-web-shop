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
