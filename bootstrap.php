<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');

define('ZING_VERSION','2.0.0');

require(dirname(__FILE__).'/classes/index.php');
require(dirname(__FILE__).'/functions/index.php');
require(dirname(__FILE__).'/init.inc.php');
require(dirname(__FILE__).'/'.ZING_CMS.'.hooks.inc.php');
require(dirname(__FILE__).'/init.inc.php');
require(dirname(__FILE__).'/'.ZING_CMS.'.init.inc.php');
