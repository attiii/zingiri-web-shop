<?php
$gateway=$_REQUEST['gateway'];
if ($gateway=='paypal') {
	require(ZING_LOC.'extensions/gateways/paypal/ipn.php');
}
?>