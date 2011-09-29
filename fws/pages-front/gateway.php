<?php
if (isset($_REQUEST['gateway']) && ctype_alnum($_REQUEST['gateway'])) $gateway=$_REQUEST['gateway'];
else die(); //unrecognized gateway

foreach ($zing->paths as $path) {
	$f=dirname($path).'/extensions/gateways/'.$gateway.'/ipn.php';
	if (file_exists($f)) {
		require($f);
		break;
	}
}
