<?php
/** Loads the WordPress Environment */
require(dirname(__FILE__).'/../../../../../wp-blog-header.php');

/** Load Zingiri Web Shop */
require(dirname(__FILE__).'/../../zing.readcookie.inc.php');
require(dirname(__FILE__).'/../../zing.startmodules.inc.php');
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$img=$_POST['id'];
unlink($product_dir.'/'.$img);
unlink($product_dir.'/tn_'.$img);
echo 'success';
?>