<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');

if ($_REQUEST['cms']=='jl') {
	define('ZING_CMS','jl');
	$_REQUEST['tmpl'] = 'component';
	$_REQUEST['option'] = 'com_zingiriwebshop';
	ob_start();
	require($_REQUEST['abspath'].'/index.php');
	ob_end_clean();
} else {
	define('ZING_CMS','wp');
	/** Loads the WordPress Environment */
	require(dirname(__FILE__).'/../../../../../wp-blog-header.php');
	/** Load Zingiri Web Shop */
	require(dirname(__FILE__).'/../../zing.readcookie.inc.php');
	require(dirname(__FILE__).'/../../startmodules.inc.php');
}
?>