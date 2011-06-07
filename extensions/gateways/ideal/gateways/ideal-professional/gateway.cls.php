<?php
// Load gateway classes & libraries
require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
require_once(dirname(__FILE__) . '/gateway.cls.5.php');
require_once(dirname(__FILE__) . '/idealprofessional.cls.5.php');

if(function_exists('stripos') == false)
{
	function stripos($haystack, $search, $offset = 0)
	{
		return strpos(strtolower($haystack), strtolower($search), $offset);
	}
}

?>