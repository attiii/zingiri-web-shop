<?php
function zing_ws_admin_menus() {
	global $zing_ws_name, $zing_ws_name,$txt,$menus,$zing_version;
	add_menu_page($zing_ws_name, 'Zingiri', 'administrator', 'zingiri-web-shop','zing_ws_admin',ZING_URL.'fws/templates/default/images/menu_webshop.png');
	add_submenu_page('zingiri-web-shop', $zing_ws_name.'- Integration', 'Integration', 'administrator', 'zingiri-web-shop', 'zing_ws_admin');
	if ($zing_version && get_option('zing_ws_install_type')) {
		$cap='administer_web_shop';
		$groupings=array();
		foreach ($menus as $page => $menu) {
			if (!$menu['hide']) {
				$g=$menu['grouping'];
				if (!isset($groupings[$g]) && !isset($menu['single']) && !$menu['single']) {
					add_menu_page($zing_ws_name, $txt[$menu['group']], $cap, $page,'zing_ws_settings',ZING_URL.'fws/templates/default/images/menu_'.$g.'.png');
					$groupings[$g]=$page;
				} elseif (isset($menu['single']) && $menu['single']) {
					add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], $cap, $page, 'zing_ws_settings');
				} else {
					add_submenu_page($groupings[$g], $txt[$menu['label']], $txt[$menu['label']], $cap, $page, 'zing_ws_settings');
				}
			}
		}
		if ($menus[$_GET['page']] && $menus[$_GET['page']]['hide']) {
			$menu=$menus[$_GET['page']];
			add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], $cap, $_GET['page'], 'zing_ws_settings');
		}
		add_submenu_page('admineditmain', 'Forms settings', 'Forms settings', $cap, 'zingiri-apps', 'zing_apps_settings');
		add_submenu_page('admineditmain', 'Forms editor', 'Forms editor', $cap, 'zingiri-apps-settings', 'zing_apps_editor');
	}
}

