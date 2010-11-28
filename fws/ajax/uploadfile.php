<?php
require(dirname(__FILE__).'/init.inc.php');
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$key=$_POST['upload_key'];
$dir=constant($_POST['wsdir']);
$ret=array();
$name = $_FILES['userfile']['name'];
$ext = strtolower(substr(strrchr($name, '.'), 1));

$target_path = $dir."/".$key."__".$name;

if(move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path)) {
	@chmod($target_path,0644); // new uploaded file can sometimes have wrong permissions
	$ret['target_file']=$name;
}
echo json_encode($ret);
?>