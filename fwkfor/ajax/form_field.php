<?php
require(dirname(__FILE__).'/init.inc.php');

$ret=array();
$class=isset($_POST['wsData']['class']) ? $_POST['wsData']['class'] : null;
$fnct=$_POST['wsData']['fnct'];

if (!$class && function_exists($fnct)) {
	$ret=$fnct($_POST['wsData']);
} elseif ($class) {
	$o=new $class;
	$ret=$o->$fnct($_POST['wsData']);
} else {
	$ret['error']=0;
	$ret['result']=$_POST['wsData']['value'];
}
echo json_encode($ret);