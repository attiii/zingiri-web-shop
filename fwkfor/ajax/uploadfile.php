<?php
require(dirname(__FILE__).'/init.inc.php');
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$key=$_POST['upload_key'];
$dir=defined('APHPS_DATA_DIR') ? APHPS_DATA_DIR : constant($_POST['wsdir']);
$ret=array();
$name = $_FILES['userfile']['name'];
$ext = strtolower(substr(strrchr($name, '.'), 1));

$allowedExtensions=array('jpg','bmp','png','zip','pdf','gif','doc','xls','wav','jpeg','docx','ppt','pptx','mp3');

if (in_array(strtolower($ext),$allowedExtensions)) {

	$target_path = $dir."/".$key."__".$name;

	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path)) {
		@chmod($target_path,0644); // new uploaded file can sometimes have wrong permissions
		$ret['target_file']=$name;
		$ret['error']=0;
	} else {
		$ret['error']='Can not upload file';
	}
} else {
	$ret['error']='Extension not allowed';
}
echo json_encode($ret);
