<?php
if ($_REQUEST['cms']=='jl') {
	define('ZING_CMS','jl');
	$_REQUEST['tmpl'] = 'component';
	$_REQUEST['option'] = 'com_zingiriwebshop';
	ob_start();
	ob_end_clean();
} elseif ($_REQUEST['cms']=='dp') {
	//all bootstrapping is already done
} else {
	/** WordPress Environment already loaded */
}
?>