<?php
require(dirname(__FILE__).'/init.inc.php');
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$img=$_POST['id'];
if (defined('APHPS_DATA_DIR')) $product_dir=APHPS_DATA_DIR;
unlink($product_dir.'/'.$img);
unlink($product_dir.'/tn_'.$img);
echo 'success';
?>