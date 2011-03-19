<?php
function zfqs($value)
{
	if( is_array($value) ) {
		return array_map("quote_smart", $value);
	} else {


		if( get_magic_quotes_gpc() ) {
			$value = stripslashes($value);
		}

		//		if( $value == '') {
		if( $value == '' && $value != 0) {
			$value = '';
		}
		//	       if( !is_numeric($value) || $value[0] == '0' ) {
		if( !is_numeric($value) ) {


			$value = "'".mysql_escape_string($value)."'";
		}
		return $value;
	}
}

function fwktecSendEmail($from,$to,$subject,$message,$charset) {
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset='.$charset."\r\n";
	$headers .= 'From: '.$from.' <'.$from.'>' . "\r\n";
	mail($to, $subject, $message, $headers);
	return true;
}
