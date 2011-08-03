<?php
$wsAction=$_GET['action'];
if ($wsAction) {
	require(ZING_DIR.'../extensions/gateways/ideal/'.$wsAction.'.php');
} else {
	require(ZING_DIR.'../extensions/gateways/ideal/checkout.php');
}
