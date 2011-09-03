<?php
/*
 Plugin Name: Zingiri Web Shop Live
 Plugin URI: http://www.zingiri.com
 Description: This plugin is no longer containted in this package, please download Zingiri Web Shop Live.
 Author: Zingiri
 Version: 2.1.1
 Author URI: http://www.zingiri.com
 */

add_action('admin_notices','zing_wslive_active');

function zing_wslive_active() {
	$message='This plugin now only contains the Zingiri Web Shop (Developer) version.<br />To continue with the version you selected, please follow these steps:<br />1) Download the <a href="'.get_option('siteurl').'/wp-admin/plugin-install.php?tab=search&type=term&s=Zingiri+Web+Shop+live&plugin-search-input=Search+Plugins">Zingiri Web Shop Live</a> plugin.<br />2) Activate the Zingiri Web Shop Live plugin.<br />3) Deactivate the current Web Shop (Developer) plugin';
	if ($message) echo "<div id='zing-warning' style='background-color:orangered;color:white' class='updated fade'><p><strong>".$message."</strong> "."</p></div>";
}

