<?php
function is_user_logged_in() {
	return LoggedIn();
}

function wsIsAdminPage() {
	return false;	
}


if (!defined("ZING_APPS_EMBED")) {
	define("ZING_APPS_EMBED","");
}

if (!defined("ZING_APPS_PLAYER_PLUGIN")) {
	define("ZING_APPS_PLAYER_PLUGIN", 'fwkfor');
}

if (!defined("ZING_APPS_PLAYER")) {
	define("ZING_APPS_PLAYER", true);
}

if (!defined("ZING_APPS_PLAYER_URL")) {
	define("ZING_APPS_PLAYER_URL", BASE_URL . ZING_APPS_PLAYER_PLUGIN."/");
}
if (!defined("ZING_APPS_PLAYER_DIR")) {
	define("ZING_APPS_PLAYER_DIR", dirname(dirname(__FILE__))."/".ZING_APPS_PLAYER_PLUGIN."/");
}
if (!defined("FACES_DIR")) {
	define("FACES_DIR", BASE_URL . ZING_APPS_PLAYER_PLUGIN."/fields/");
}

