<?php
/** Loads the WordPress Environment */
require(dirname(__FILE__).'/../../../../../wp-blog-header.php');

/** Load Zingiri Web Shop */
require(dirname(__FILE__).'/../../zing.readcookie.inc.php');
require(dirname(__FILE__).'/../../zing.startmodules.inc.php');
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$key=$_POST['upload_key'];
$ret=array();
$name = $_FILES['userfile']['name'];
$ext = strtolower(substr(strrchr($name, '.'), 1));

if ($ext == "jpg" || $ext == "gif" || $ext == "png") {
	$target_path = $product_dir."/".$key."__".$name;
	
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path)) {
		chmod($target_path,0644); // new uploaded file can sometimes have wrong permissions
		// lets try to create a thumbnail of this new image shall we
		//if ($make_thumbs == 1) {
			createthumb($target_path,$product_dir.'/tn_'.$key."__".$name,$pricelist_thumb_width,$pricelist_thumb_height);
		//}
		$ret['target_url']=$product_url."/tn_".$key."__".$name;
		$ret['target_file']=$key."__".$name;
	}
}
echo json_encode($ret);
?>