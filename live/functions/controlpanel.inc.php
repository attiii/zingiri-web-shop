<?php
function zing_ws_live_set_options() {
	global $zing_ws_options,$zing_ws_name,$zing_ws_shortname;
	global $db_prefix,$base_path,$base_url,$db_url;

	$zing_ws_name = "Zingiri Web Shop";
	$zing_ws_shortname = "zing_ws";
	$install_type = array("Yes","No");
	$zing_yn = array("Yes", "No");

	if (get_option($zing_ws_shortname."_baseurl") && get_option($zing_ws_shortname."_accname")) {
		$desc='To manage your shop, login to your <a href="'.get_option($zing_ws_shortname."_baseurl").get_option($zing_ws_shortname."_accname").'">Zingiri Web Shop Live</a> account.<br />&nbsp';
	} else {
		$desc='If you haven\'t done so yet, start by signing up for a Zingiri Web Shop Live account <a href="admin.php?page=wslive">here</a>.<br /><br />';
	}
	
	$zing_ws_options = array (
	array(  "name" => "Web Shop Settings",
    	        "type" => "heading",
				"desc" => $desc,
	)
	);

	$zing_ws_options[]=	array(	"name" => "Base URL",
		"desc" => "URL of the Zingiri Web Shop Live service. Normally this should be left to it's default value.",
		"id" => $zing_ws_shortname."_baseurl",
		"std" => 'http://live.zingiri.com/',
		"type" => "hidden");

	$zing_ws_options[]=	array(	"name" => "Account ID",
		"desc" => "Your Zingiri Web Shop Live account ID.",
		"id" => $zing_ws_shortname."_accname",
		"std" => '',
		"type" => "text");

	if (ZING_CMS=='wp') {
		global $wpdb;
		if ($ids=get_option("zing_webshop_pages")) {
			$ida=explode(",",$ids);
			foreach ($ida as $i) {
				$p = $wpdb->get_results( "SELECT post_title FROM ".$wpdb->prefix."posts WHERE post_status<>'trash' and id='".$i."'" );
				$zing_ws_options[]=array(	"name" => $p[0]->post_title." page",
			"desc" => "Display ".$p[0]->post_title." page in the menus.",
			"id" => $zing_ws_shortname."_show_menu_".$i,
			"std" => "Yes",
			"type" => "select",
			"options" => $zing_yn);
			}
		}
	}
	/* still to be implemented
	 $zing_ws_options[]=	array(	"name" => "Single sign on",
		"desc" => "If selected, users logged in to your site are automatically signed in to the web shop.",
		"id" => $zing_ws_shortname."_sso",
		"std" => "y",
		"type" => "select",
		"options" => array("y" => "Yes","n" => "No"));
		*/
}

function zing_wslive_ws_admin() {

	global $zing_ws_name, $zing_ws_shortname, $zing_ws_options, $integrator;

	zing_ws_live_set_options();

	if ( $_REQUEST['installed'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' settings updated.</strong></p></div>';
	?>
<div class="wrap">
<form method="post">
<?php if (ZING_CMS=='jl') echo '<input type="hidden" name="option" value="com_zingiriwebshop" />';?>
<?php zing_options($zing_ws_options);?>
<center class="submit"><input name="install" type="submit" value="Update" /></center>
<input type="hidden" name="action" value="install" /></form>
<?php if (zing_ws_active_install()) {
	echo '<hr />';
	echo '<h3>Migrate</h3>';
	echo '<p>';
	zing_ws_migrate();
	echo 'To migrate your curent local data, first download <a href="'.ZING_WSLIVE_UPLOADS_URL.get_option('zing_ws_dumpfile').'.zip">this file</a> to your computer and then upload it to your <a href="'.get_option('zing_ws_baseurl').get_option('zing_ws_accname').'/wp-admin/admin.php?page=migrate" target="_blank">Zingiri Live Admin Panel</a>.';
	echo '</p>';
}
?> <br />
<hr />
<h3>Support</h3>
<p>For more info and support, contact us at <a href="http://www.zingiri.com">Zingiri</a>
or check out our <a href="http://forums.zingiri.com/">support forums</a>.</p>
<br />
<hr />
<center><image src="http://www.zingiri.com/logo.png" /></center>
</div>
<?php
}
?>
