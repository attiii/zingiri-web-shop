<?php
require_once(ZING_DIR.'addons/fusioncharts/Class/FusionCharts_Gen.php');

if ($handle = opendir(dirname(__FILE__))) {
	while (false !== ($filex = readdir($handle))) {
		if (!strstr($filex,"index.php") && strstr($filex,".php")) {
			require_once(dirname(__FILE__).'/'.$filex);
			$zing->dashboardWidgets[]=str_replace('.php','',$filex).'_widget';
		}
	}
	closedir($handle);
}

add_action('wp_dashboard_setup', 'zing_dashboard_widgets' );
function zing_dashboard_widgets() {
	global $zing,$txt;

	foreach ($zing->dashboardWidgets as $widget) {
		wp_add_dashboard_widget($widget, z_($widget), $widget);
	}
}

