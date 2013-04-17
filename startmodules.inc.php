<?php
//correction for parameter 'cat' used by Wordpress for categories
if (isset($_GET['kat'])) { $_GET['cat']=$_GET['kat']; }
include (ZING_DIR."./includes/readvals.inc.php");        // get and post values
include (ZING_DIR."./includes/readsettings.inc.php");    // read shop settigns
include( ZING_DIR."./includes/setfolders.inc.php");      // set appropriate folders

$product_url = ZING_UPLOADS_URL.$product_dir;
if (!defined("ZING_WS_PRODUCT_URL")) define("ZING_WS_PRODUCT_URL",$product_url);
$brands_url = ZING_UPLOADS_URL.$brands_dir;
define('ZING_WS_CATS_URL',$brands_url.'/');
$orders_url = ZING_UPLOADS_URL.$orders_dir;

$product_dir = ZING_UPLOADS_DIR.$product_dir;
if (!defined("ZING_WS_PRODUCT_DIR")) define("ZING_WS_PRODUCT_DIR",$product_dir);
$brands_dir = ZING_UPLOADS_DIR.$brands_dir;
define('ZING_WS_CATS_DIR',$brands_dir.'/');
$orders_dir = ZING_UPLOADS_DIR.$orders_dir;
$lang_dir = ZING_DIR.$lang_dir;

$gfx_dir = ZING_URL.'fws/'.$template_dir."/".$template."/images";
$scripts_dir = ZING_DIR;
	
include (ZING_DIR."./includes/initlang.inc.php");        // init the language
