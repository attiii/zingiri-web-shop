<?php
function zing_ws_admin_menus() {
	global $zing_ws_name, $zing_ws_name,$txt,$menus,$zing_version;
	add_menu_page($zing_ws_name, 'Zingiri', 'manage_options', 'zingiri-web-shop','zing_ws_admin',ZING_URL.'fws/templates/default/images/menu_webshop.png');
	add_submenu_page('zingiri-web-shop', $zing_ws_name.'- Integration', 'Integration', 'manage_options', 'zingiri-web-shop', 'zing_ws_admin');
	if ($zing_version && (get_option('zing_ws_install_type')!='live')) {
		$cap='administer_web_shop';
		$groupings=array();
		foreach ($menus as $page => $menu) {
			if (!isset($menu['hide']) || !$menu['hide']) {
				$g=$menu['grouping'];
				$groupLabel=$txt[$menu['group']] ? $txt[$menu['group']] : $menu['group'];
				$menuLabel=$txt[$menu['label']] ? $txt[$menu['label']] : $menu['label'];
				if (!isset($groupings[$g]) && !isset($menu['single'])) {
					add_menu_page($groupLabel, $groupLabel , $cap, $page,'zing_ws_settings',ZING_URL.'fws/templates/default/images/menu_'.$g.'.png');
					$groupings[$g]=$page;
				} elseif (isset($menu['single']) && $menu['single']) {
					add_submenu_page('zingiri-web-shop', $menuLabel, $menuLabel, $cap, $page, 'zing_ws_settings');
				} else {
					add_submenu_page($groupings[$g], $menuLabel, $menuLabel, $cap, $page, 'zing_ws_settings');
				}
			}
		}
		if (isset($_GET['page']) && isset($menus[$_GET['page']]) && $menus[$_GET['page']] && isset($menus[$_GET['page']]['hide']) && $menus[$_GET['page']]['hide']) {
			$menu=$menus[$_GET['page']];
			add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], $cap, $_GET['page'], 'zing_ws_settings');
		}
	}
}


add_action('init','zing_enqueue_tinymce');
function zing_enqueue_tinymce() {
	if (is_admin()) {
		wp_enqueue_script(array('editor', 'thickbox', 'media-upload'));
		wp_enqueue_style('thickbox');
	}
}


