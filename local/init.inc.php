<?php
if (!get_option('zing_webshop_pro')) {
	define("ZING_WS_PRO_DIR",'');
	define("ZING_WS_PRO_URL",'');
	define('ZING_WS_PRO',false);
}
if (!defined('ZING_VERSION')) define("ZING_VERSION","2.6.5");
global $aphps_projects;
$aphps_projects['fws']=array('label'=>'Web Shop','dir'=>ZING_LOC.'fws/apps/','url'=>ZING_URL.'fws/apps/');
@include(dirname(dirname(__FILE__))."/source.inc.php");
if (!defined('APHPS_JSDIR')) define('APHPS_JSDIR','min');

@include(dirname(dirname(__FILE__))."/fixme.php");
require(dirname(__FILE__)."/zing.php");
$zing->paths[]=dirname(dirname(__FILE__)).'/fws/';

define("ZING_APPS",dirname(dirname(__FILE__))."/fws/fields/");
if (!defined('ZING_APPS_CUSTOM')) define("ZING_APPS_CUSTOM",dirname(dirname(__FILE__))."/fws/");
define("ZING_GLOBALS",dirname(dirname(__FILE__))."/fws/globals.php");
define("ZING_APPS_EMBED","fwkfor/");
define("ZING_APPS_TRANSLATE",'z_');
define("ZING_APPS_EDITABLES","'register','profile'");
define("ZING_APPS_MENU","zingiri-web-shop");

define("ZING_JQUERY",true);
define("ZING_PROTOTYPE",false);
define("APHPS_SECRET",md5(AUTH_KEY.dirname(__FILE__)));

require(dirname(dirname(__FILE__))."/fwktec/functions-core/index.php");
require(dirname(dirname(__FILE__))."/fwktec/classes-core/index.php");
require(dirname(dirname(__FILE__))."/".ZING_APPS_EMBED."/embed.php");

require(dirname(dirname(__FILE__))."/zing.inc.php");

require(dirname(dirname(__FILE__))."/extensions/index.php");

define("ZING_APPS_CAPTCHA",ZING_UPLOADS_DIR.'cache/');

if (!function_exists('wsHooks')) require(dirname(dirname(__FILE__))."/hooks.inc.php");
