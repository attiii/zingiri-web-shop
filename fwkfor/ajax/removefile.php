<?php
require(dirname(__FILE__).'/init.inc.php');
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$img=$_POST['wsid'];
$key=$_POST['wskey'];
$dir=defined('APHPS_DATA_DIR') ? APHPS_DATA_DIR : constant($_POST['wsdir']);
unlink($dir.'/'.$key.'__'.$img);
echo 'success';
?>