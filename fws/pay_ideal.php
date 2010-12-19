<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');

$wsAction=$_GET['action'];
if ($wsAction) {
	require(ZING_DIR.'../extensions/gateways/ideal/'.$wsAction.'.php');
} else {
	require(ZING_DIR.'../extensions/gateways/ideal/checkout.php');
}
