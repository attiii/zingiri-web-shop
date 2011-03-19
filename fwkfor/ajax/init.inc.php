<?php
if ($_REQUEST['cms']=='jl') {
	define('ZING_CMS','jl');
	$_REQUEST['tmpl'] = 'component';
	$_REQUEST['option'] = 'com_zingiriwebshop';
	ob_start();
	require($_REQUEST['wpabspath'].'/index.php');
	ob_end_clean();
} elseif ($_REQUEST['cms']=='dp') {
	//all bootstrapping is already done
} else {
	/** Loads the WordPress Environment */
	//require($_REQUEST['wpabspath'].'wp-blog-header.php');
	require($_REQUEST['wpabspath'].'wp-load.php');
}
?>