<?php if ($index_refer <> 1) { exit(); } ?>
<?php
ob_end_clean();
foreach ($zing->paths as $p) {
	$f=$p.'ajax/'.$_REQUEST['wscr'].'.php';
	if (file_exists($f)) {
		require($f);
		die();		
	}
}
