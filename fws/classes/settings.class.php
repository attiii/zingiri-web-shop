<?php
function wsSetting($setting) {
	$db=new db();
	if (!$db->fieldExists('settings',$setting)) return false;
	if ($db->select("select `".$setting."` from ##settings where `ID`=1")) {
		$db->next();
		return $db->get($setting);
	}
	return false;
}

