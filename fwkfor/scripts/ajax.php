<?php
while (count(ob_get_status(true)) > 0) ob_end_clean();
$form=$_REQUEST['form'];
if (isset($_REQUEST['mod'])) $mod=$_REQUEST['mod'];
elseif (isset($_REQUEST['module'])) $mod=$_REQUEST['module'];
if (!ctype_alnum($form) || !ctype_alnum($mod)) die();

$f=ZING_APPS_PLAYER_DIR.'../'.$mod.'/ajax/'.$form.'.php';
if (file_exists($f)) {
	require($f);
	die();
}

foreach($aphps_projects as $id=>$project) {
	$f=$aphps_projects[$mod]['dir'].'ajax/'.$form.'.php';
	if (file_exists($f)) {
		require($f);
		die();
	}
}

die('Not found');