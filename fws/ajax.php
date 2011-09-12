<?php if ($index_refer <> 1) { exit(); } ?>
<?php
ob_end_clean();
$wscr=$_REQUEST['wscr'];
if (!ctype_alnum($wscr)) die();
foreach ($zing->paths as $p) {
	$f=$p.'ajax/'.$wscr.'.php';
	if (file_exists($f)) {
		require($f);
		die();		
	}
}
