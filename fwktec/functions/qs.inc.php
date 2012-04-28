<?php
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

function aphpsSanitize($var,$type=null){
	$flags = NULL;
	switch($type)
	{
		case 'url':
			$filter = FILTER_SANITIZE_URL;
			break;
		case 'int':
			$filter = FILTER_SANITIZE_NUMBER_INT;
			break;
		case 'float':
			$filter = FILTER_SANITIZE_NUMBER_FLOAT;
			$flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
			break;
		case 'email':
			$var = substr($var, 0, 254);
			$filter = FILTER_SANITIZE_EMAIL;
			break;
		case 'string':
		default:
			$filter = FILTER_SANITIZE_STRING;
			$flags = FILTER_FLAG_NO_ENCODE_QUOTES;
			break;

	}
	$output = filter_var($var, $filter, $flags);
	return($output);
}
