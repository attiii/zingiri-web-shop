<?php
global $zing_apps_builder_projects;
$zing_apps_builder_projects['fws']=array('label'=>'Web Shop','dir'=>ZING_DIR,'url'=>ZING_URL.'fws/');

if (!get_option('zing_webshop_pro')) {
	define("ZING_WS_PRO_DIR",'');
	define("ZING_WS_PRO_URL",'');
	define('ZING_WS_PRO',false);
}
define("ZING_VERSION","1.6.8");
@include(dirname(__FILE__)."/source.inc.php");
@include(dirname(__FILE__)."/fixme.php");
require(dirname(__FILE__)."/load.php");
define("ZING_APPS",dirname(__FILE__)."/fws/fields/");
define("ZING_APPS_CUSTOM",dirname(__FILE__)."/fws/");
define("ZING_GLOBALS",dirname(__FILE__)."/fws/globals.php");
define("ZING_APPS_EMBED","zap/");
define("ZING_APPS_TRANSLATE",'z_');
define("ZING_APPS_EDITABLES","'register','profile'");
define("ZING_APPS_MENU","zingiri-web-shop");

define("ZING_JQUERY",true);
define("ZING_PROTOTYPE",false);

require(dirname(__FILE__)."/zap/embed.php");

require(dirname(__FILE__)."/zing.inc.php");

require(dirname(__FILE__)."/extensions/index.php");

define("ZING_APPS_CAPTCHA",BLOGUPLOADDIR.ZING_SLUG.'/cache/');

?>