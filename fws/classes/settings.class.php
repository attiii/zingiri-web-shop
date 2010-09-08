<?php
class wsSettings {
	var $settings;
	var $groups=array('editsettings48','editsettings47','editsettings45','editsettings46','wishlist1');
	var $count;

	function wsSettings() {
		//wishlist
		$this->settings[5]['wishlistactive']=array('label'=>'wishlist3',type=>'checkbox');
		$this->count=count($this->groups);
	}

	function query($show) {
		$query='';
		foreach ($this->settings as $group => $settings) {
			foreach ($settings as $setting => $values) {
				if ($show == $group || $show == "all") {
					if ($query) $query.=", ";
					$query.= "`".$setting."` = '".qs($this->parse($setting,$values))."'";
				}
			}
		}
		return $query;
	}

	function parse($setting,$values) {
		if (isset($_POST[$setting])) {
			switch ($values['type']) {
				case 'checkbox':
					return CheckBox($_POST[$setting]);
					break;
				default:
					return $_POST[$setting];
					break;
			}
		}
	}

	function fields($show) {
		global $txt;
		for ($group=5;$group<=$this->count;$group++) {
			if ($show == $group || $show == "all") {
				echo '<tr><td colspan="2"><h6>'.$txt[$this->groups[$group-1]].'</h6><br /></td></tr>';
				foreach ($this->settings[$group] as $setting => $values) {
					echo '<tr><td width="30%">'.$txt[$values['label']].'</td>';
					switch ($values['type']) {
						case 'checkbox':
							echo '<td><input type="checkbox" name="'.$setting.'" '.($this->getValue($setting) == 1 ? "checked" : "").'></td>';
							break;
					}
				}
				echo '</tr>';
			}
		}
	}

	function getValue($setting) {
		$db=new db();
		if (!$db->fieldExists('settings',$setting)) return false;
		if ($db->select("select `".$setting."` from ##settings where `ID`=1")) {
			$db->next();
			return $db->get($setting);
		}
		return false;
	}
}

function wsSetting($setting) {
	$db=new db();
	if (!$db->fieldExists('settings',$setting)) return false;
	if ($db->select("select `".$setting."` from ##settings where `ID`=1")) {
		$db->next();
		return $db->get($setting);
	}
	return false;
}

