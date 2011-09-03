<?php
/*
 Plugin Name: Zingiri Web Shop Live (obsolete)
 Plugin URI: http://www.zingiri.com
 Description: This plugin is no longer containted in this package, please download Zingiri Web Shop Live.
 Author: Zingiri
 Version: 2.1.1
 Author URI: http://www.zingiri.com
 */

add_action('admin_notices','zing_wslive_active');

function zing_wslive_active() {
	$message='Please replace the plugin zingiri-web-shop with the plugin ws-live. The zingiri-web-shop plugin now only contains the Developer version. To continue with the version you selected, please download and install the <a href="wp-admin/plugin-install.php?tab=search&type=term&s=Zingiri+Web+Shop+live&plugin-search-input=Search+Plugins">Zingiri Web Shop Live</a> plugin.';
	if ($message) echo "<div id='zing-warning' style='background-color:greenyellow' class='updated fade'><p><strong>".$message."</strong> "."</p></div>";
}

