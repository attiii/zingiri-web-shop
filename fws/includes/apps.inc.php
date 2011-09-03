<?php
function fwktecError($message) {
	global $gfx_dir,$txt;
	PutWindow($gfx_dir, $txt['general12'], $message, "warning.gif", "50");
}

function fwktecWarning($message) {
	global $gfx_dir,$txt;
	PutWindow($gfx_dir, $txt['general13'], $message, "notify.gif", "50");
}