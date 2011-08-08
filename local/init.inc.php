<?php
if (!get_option('zing_webshop_pro')) {
	define("ZING_WS_PRO_DIR",'');
	define("ZING_WS_PRO_URL",'');
	define('ZING_WS_PRO',false);
}
if (!defined('ZING_VERSION')) define("ZING_VERSION","2.1.1");
define('APHPS_JSDIR','src');
global $aphps_projects;
$aphps_projects['fws']=array('label'=>'Web Shop','dir'=>ZING_LOC.'fws/apps/','url'=>ZING_URL.'fws/apps/');

@include(dirname(dirname(__FILE__))."/source.inc.php");
@include(dirname(dirname(__FILE__))."/fixme.php");
require(dirname(dirname(__FILE__))."/load.php");
define("ZING_APPS",dirname(dirname(__FILE__))."/fws/fields/");
define("ZING_APPS_CUSTOM",dirname(dirname(__FILE__))."/fws/");
define("ZING_GLOBALS",dirname(dirname(__FILE__))."/fws/globals.php");
define("ZING_APPS_EMBED","fwkfor/");
define("ZING_APPS_TRANSLATE",'z_');
define("ZING_APPS_EDITABLES","'register','profile'");
define("ZING_APPS_MENU","zingiri-web-shop");

define("ZING_JQUERY",true);
define("ZING_PROTOTYPE",false);

require(dirname(dirname(__FILE__))."/fwkfor/embed.php");

require(dirname(dirname(__FILE__))."/zing.inc.php");

require(dirname(dirname(__FILE__))."/extensions/index.php");

define("ZING_APPS_CAPTCHA",BLOGUPLOADDIR.'zingiri-web-shop/cache/');

if (!function_exists('wsHooks')) require(dirname(dirname(__FILE__))."/hooks.inc.php");
