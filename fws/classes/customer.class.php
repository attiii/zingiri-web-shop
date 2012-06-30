<?php
class wsCustomer {
	var $data;
	var $loggedin=false;
	var $id=0;

	function wsCustomer() {
		global $dbtablesprefix;

		if (!isset($_COOKIE['fws_cust'])) { return false; }
		$fws_cust = explode("#", $_COOKIE['fws_cust']);
		$customerid = aphpsSanitize($fws_cust[1]);
		$md5pass = aphpsSanitize($fws_cust[2]);
		if (is_null($customerid)) { return false; }
		$query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = '" . qs($customerid). "'";
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_array($sql)) {
			if (md5($row[2]) == $md5pass && $row[6] == GetUserIP()) $this->loggedin=true;
			$this->data=$row;
			return true;
		}
		return false;
	}

}
function getCustomerNameById($customerid) {
	$db=new db();
	$query = "SELECT * FROM ##customer WHERE ID = '" . $customerid. "'";
	if ($db->select($query)) {
		if ($db->next()) return $db->get('initials').' ' .$db->get('lastname');
	}
	return '';
}
?>