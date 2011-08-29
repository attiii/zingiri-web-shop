<?php
require(dirname(__FILE__).'/init.inc.php');

$ret=array();
$fnct=$_POST['wsData']['fnct'];
if (function_exists($fnct)) {
	$ret=$fnct($_POST['wsData']);
} else {
	$ret['error']=1;
}
echo json_encode($ret);