<?php
function fwktecError($message) {
	global $gfx_dir,$txt;
	PutWindow($gfx_dir, $txt['general12'], $message.' (01)', "warning.gif", "50");
}

function fwktecWarning($message) {
	global $gfx_dir,$txt;
	PutWindow($gfx_dir, $txt['general13'], $message.' (02)', "notify.gif", "50");
}