<?php
while (count(ob_get_status(true)) > 0) ob_end_clean();
$form=$_REQUEST['form'];
$form=str_replace('.php','',$form);
if (isset($_REQUEST['mod'])) $mod=$_REQUEST['mod'];
elseif (isset($_REQUEST['module'])) $mod=$_REQUEST['module'];
if (preg_match('/[^a-zA-Z_0-9]/i', $form) || preg_match('/[^a-zA-Z_0-9]/i', $mod)) die();

$f=ZING_APPS_PLAYER_DIR.'../'.$mod.'/ajax/'.$form.'.php';
if (file_exists($f)) {
	require($f);
	die();
}

$f=$aphps_projects[$mod]['dir'].'ajax/'.$form.'.php';
if (file_exists($f)) {
	require($f);
	die();
}

die('Not found');