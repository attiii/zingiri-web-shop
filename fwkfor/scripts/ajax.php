<?php
while (count(ob_get_status(true)) > 0) ob_end_clean();
$form=$_REQUEST['form'];
$mod=$_REQUEST['mod'];
if (!ctype_alnum($form) || !ctype_alnum($mod)) die();
$f=ZING_APPS_PLAYER_DIR.'../'.$mod.'/ajax/'.$form.'.php';
if (file_exists($f)) {
	require($f);
	die();
}