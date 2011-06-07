<?php
require(dirname(__FILE__).'/init.inc.php');

$groupid=intval($_REQUEST['wsgroupid']);
echo '<option value="">'.'</option>';
$db=new db();
if ($db->select("SELECT * FROM `##category` WHERE `GROUPID`=".$groupid." ORDER BY `SORTORDER`,`DESC` ASC")) {
	while ($db->next())	{
		echo '<option value="'.$db->get('id').'">'.$db->get('desc').'</option>';
	}
}
