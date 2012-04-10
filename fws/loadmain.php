<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$pageFound='';

if (file_exists(dirname(__FILE__)."/$page.php")) {
	$pageFound=dirname(__FILE__)."/$page.php";
} else {
	if (count($zing->paths) > 0) {
		foreach ($zing->paths as $path) {
			if (!$pageFound && file_exists($path."pages-front/$page.php")) {
				$pageFound=$path."pages-front/$page.php";
			}
			elseif (!$pageFound && file_exists($path."pages-back/$page.php")) {
				$pageFound=$path."pages-back/$page.php";
			}
		}
	}
}
if ($shop_disabled == 1 && IsAdmin() == false && $page != "my") {
	PutWindow($gfx_dir, $shop_disabled_title,$shop_disabled_reason,"warning.gif", "50");
}
elseif (IsBanned() == true) {
	PutWindow($gfx_dir, $txt['general12'],$txt['general10'],"warning.gif", "50");
}
elseif ($pageFound) {
	echo actionCompleteMessage();
	require($pageFound);
}
else {
	PutWindow($gfx_dir, $txt['general12'], $txt['general9'], "warning.gif", "50");
}

