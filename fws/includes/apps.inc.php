<?php
function fwktecError($message) {
	global $gfx_dir,$txt;
	PutWindow($gfx_dir, $txt['general12'], $message, "warning.gif", "50");
}