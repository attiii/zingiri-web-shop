<?php
function fwktecSendEmail($from,$to,$subject,$message,$charset) {
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset='.$charset."\r\n";
	$headers .= 'From: '.$from.' <'.$from.'>' . "\r\n";
	mail($to, $subject, $message, $headers);
	return true;
}

if (!function_exists('fwktecLicensedFor')) {
	function fwktecLicensedFor($option) {
		return true;
	}
}


function display($message='') {
	if (is_array($message)) $message=print_r($message,true);
	//if (APHPS_DEV) trigger_error($message);
	echo $message.EOL;
}
