<?php
//require('../../../../../wp-blog-header.php');
require($_POST['wpabspath'].'/wp-blog-header.php');
//require(dirname(__FILE__).'/../../zap/includes/faces.inc.php');
if (!IsAdmin()) die('No access');

$id=intval($_POST['id']);
$frontpage=$_POST['frontpage'] == 'true' ? 1 : 0;
$db=new db();
$sql=sprintf("update ##product set frontpage=%s where id=%s",$frontpage,$id);
$db->update($sql);
?>