<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');

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
	if (!defined('ZING_AJAX') || !ZING_AJAX) {
		/** Loads the WordPress Environment */
		//require($_REQUEST['wpabspath'].'wp-blog-header.php');
		require($_REQUEST['wpabspath'].'wp-load.php');
		/** Load Zingiri Web Shop */
		require(dirname(__FILE__).'/../../zing.readcookie.inc.php');
		require(dirname(__FILE__).'/../../startmodules.inc.php');
	}
}
?>