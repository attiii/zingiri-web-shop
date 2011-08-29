<?php
global $saasRet;
setcookie ("fws_cust","", time() - 3600, '/');
if ($integrator->wpCustomer) {
	wp_logout();
}
if (!ZING_LIVE) {
	header('Location:'.zurl(get_option('home')));
	die();
} else {
	echo $txt['logout1'];
	$saasRet['status']='logoutsuccess';
	$saasRet['redirect']=$_REQUEST['pagetoload'];

}
?>