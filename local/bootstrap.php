<?php
require(dirname(__FILE__).'/'.ZING_CMS.'.hooks.admin.inc.php');
require(dirname(__FILE__).'/functions/index.php');
require(dirname(__FILE__)."/".ZING_CMS.".init.inc.php");
require(dirname(__FILE__)."/init.inc.php");

require (dirname(__FILE__)."/zing.startfunctions.inc.php");
require_once(dirname(__FILE__) . '/'.ZING_CMS.'.integrator.class.php');
require(dirname(__FILE__).'/'.ZING_CMS.'.hooks.inc.php');

require_once(ZING_LOC . '/controlpanel.php');

require(ZING_GLOBALS);

if (get_option('zing_webshop_version')) {
	require_once(ZING_LOC . '/startmodules.inc.php');
	require_once(ZING_LOC . '/zing.readcookie.inc.php');
}
