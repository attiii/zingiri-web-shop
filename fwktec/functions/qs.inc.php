<?php
/*
 * //old version from webshop
 * 
if (!function_exists('qs')) {
	function qs($value) {
		return quote_smart($value);
	}
}


function quote_smart($value)
{
	if( is_array($value) ) {
		return array_map("quote_smart", $value);
	} else {
		if( get_magic_quotes_gpc() ) {
			$value = stripslashes($value);
		}
		if( $value == '' ) {
			$value = '';
		}
		if( !is_numeric($value) || $value[0] == '0' ) {
			$value = "'".wsEscapeString($value)."'";
		}
		return $value;
	}
}
*/

function qs($value,$checknull = FALSE,$forcequotes = FALSE)
{
	$value=quote_smart($value,$checknull);
	if ($forcequotes && substr($value,0,1) != "'")
	{
		return "'".$value."'";
	}
	return $value;
}

function quote_smart($value,$checknull = FALSE)
{
	if ($checknull && is_null($value)) { return "NULL"; }

	if( is_numeric($value) && $value == 0) {
		return '0';
	}

	if( is_array($value) ) {
		return array_map("quote_smart", $value);
	} else {
		if( get_magic_quotes_gpc() ) {
			$value = stripslashes($value);
		}
		if( $value == ''  && $value != 0) {
			$value = '';
		}
		if( !is_numeric($value)) {
			$value = "'".mysql_escape_string($value)."'";
		}
		return $value;
	}
}
