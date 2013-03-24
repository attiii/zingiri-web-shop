<?php
function zing_set_options() {
	global $wpdb,$zing_ws_options,$zing_ws_name,$zing;

	$zing_ws_name = "Zingiri Web Shop";
	$install_type = array("Yes","No");
	$zing_yn = array("Yes", "No");

	if (defined('WP_ZINGIRI_LIVE')) {
		$zing_ws_options = array();
		return;
	}

	
	$zing_ws_options = array();
		
	$zing_ws_options[] = array(  "name" => "Installation Settings",
		"type" => "heading",
		"desc" => "This section covers the local installation option.");
	
	if (ZING_CMS=='wp') {
		$zing_ws_options[]=	array(	"name" => "User management",
			"desc" => "Select whether you want to use full integration with Wordpress user management or Zingiri's stand alone user management.",
			"id" => "zing_ws_login",
			"std" => "WP",
			"type" => "select",
			"options" => array("WP","Zingiri"));
	} elseif (ZING_CMS=='dp') {
		$zing_ws_options[]=	array(	"name" => "User management",
			"desc" => "Select whether you want to use full integration with Drupal user management (not yet implemented) or Zingiri's stand alone user management.",
			"id" => "zing_ws_login",
			"std" => "Zingiri",
			"type" => "select",
			"options" => array("Zingiri"));

	} else {
		$zing_ws_options[]=	array(	"name" => "User management",
			"desc" => "Select whether you want to use full integration with Joomla user management (not yet implemented) or Zingiri's stand alone user management.",
			"id" => "zing_ws_login",
			"std" => "WP",
			"type" => "select",
			"options" => array("Zingiri"));
	}

	$zing_ws_options[]=	array(	"name" => "Data directory",
			"desc" => "Data directory where all your images, etc are stored. Can be left to the default value in most cases.",
			"id" => "zing_ws_uploads_dir",
			"size" => 80,
			"std" => ZING_UPLOADS_DIR,
			"type" => "text");

	$zing_ws_options[]=	array(	"name" => "Data URL",
			"desc" => "URL to your data directory. Can be left to the default value in most cases.",
			"id" => "zing_ws_uploads_url",
			"size" => 80,
			"std" => ZING_UPLOADS_URL,
			"type" => "text");
			
	$zing_ws_options[]= array(	"name" => "Logo",
			"desc" => "Select how and where you want to display the Zingiri logo. You can display it at the bottom of your site or a the bottom of every shop page.<br />Only select other if you received a written confirmation from us that it is ok to do so.",
			"id" => "zing_ws_logo",
			"std" => "sf",
			"type" => "selectwithkey",
			"options" => array('sf'=>'In site footer','pf'=>'At bottom of page','na'=>'Other'));
	$zing_ws_options[]= array(	"name" => "Newsletter",
			"desc" => "We regularly send out a newsletter containing information about new releases, security warnings, ... 
			<br />If you don't wish to receive this newsletter, please select 'No'.
			<br />If you choose to receive the newsletter, we will send it to <strong>".get_option('admin_email')."</strong>
			<br />You can change this option at any time.",
			"id" => "zing_ws_install",
			"std" => "Yes",
			"type" => "select",
			"options" => $install_type);
		
	$zing_ws_options[] = array(  "name" => "Web Shop Settings",
		"type" => "heading",
		"desc" => "This section covers the Web Shop settings.");
	
	if ($ids=get_option("zing_webshop_pages")) {
		$ida=explode(",",$ids);
		foreach ($ida as $i) {
			$p = $wpdb->get_results( "SELECT post_title FROM ".$wpdb->prefix."posts WHERE post_status<>'trash' and id='".$i."'" );
			$zing_ws_options[]=array(	"name" => $p[0]->post_title." page",
			"desc" => "Display ".$p[0]->post_title." page in the menus.",
			"id" => "zing_ws_show_menu_".$i,
			"std" => "Yes",
			"type" => "select",
			"options" => $zing_yn);
		}
	}

	if (count($zing->extensions) > 0) {
		foreach ($zing->extensions as $id) {
			$zing_ws_options[] = array(  "name" => "Web Shop Pro Extension $id",
				"type" => "heading",
				"desc" => "This section covers the options for your $id Pro Extension.");
			$zing_ws_options[]= array(	"name" => "License key",
				"desc" => "License key for $id extension",
				"id" => $id."_license_key",
				"std" => "",
				"type" => "text",);
			if (isset($zing->options[$id]) && count($zing->options[$id]) > 0) {
				foreach ($zing->options[$id] as $option) {
					$zing_ws_options[]=$option;
				}
			}
		}
	}

}


function zing_ws_settings() {
	global $menus,$txt,$wpdb,$dbtablesprefix,$action,$gfx_dir,$wsPages,$page;
	
	zing_admin_header();
	zing_apps_player_header_cp();

	//main window
	echo '<div style="width:80%;float:left;position:relative">';
	$_GET['page']=str_replace('zingiri-web-shop-','',$_GET['page']);
	$pg=$_GET['page'];
	$params=array();
	$pairs=explode('&',$menus[$pg]['href']);
	foreach ($pairs as $pair) {
		list($n,$v)=explode('=',$pair);
		if ($n!='page') {
			if (($n=='form' || $n=='formid') && (isset($_GET['form']) || isset($_GET['formid']))) break;
			elseif (!isset($_GET[$n])) $_GET[$n]=$v;
			$params[$n]=$v;
		}
	}
	if (isset($menus[$pg]['page'])) $_GET['page']=$menus[$pg]['page'];
	$page=$_GET['page'];
	if (ZING_CMS=='wp' || ZING_CMS=='jl') echo '<link rel="stylesheet" type="text/css" href="'.ZING_URL.'zing.css" />';
	if (isset($_GET['page'])) {
		require(dirname(__FILE__).'/fws/includes/pages.inc.php');
		if (isset($wsPages[$pg])) echo '<h1>'.$txt[$wsPages[$pg]].'</h1>';
	}
	zing_main('content');
	if ((isset($menus[$pg]['type']) && $menus[$pg]['type']=="apps") || ZING_CMS!='wp') {
		zing_apps_player_content('content');
	}
	echo '</div>';

	//share and donate
	echo '<div style="width:20%;float:right;position:relative">';
	if (get_option('zing_webshop_version')) require(ZING_LOC.'support-us.inc.php');
	echo '</div>';	
}

function zing_ws_admin() {

	global $zing_ws_name, $zing_ws_options, $integrator, $dbtablesprefix, $gfx_dir;

	zing_set_options();

	if ( isset($_REQUEST['installed']) && $_REQUEST['installed'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' installed.</strong></p></div>';
	if ( isset($_REQUEST['uninstalled']) && $_REQUEST['uninstalled'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' uninstalled.</strong></p></div>';
	if ( isset($_REQUEST['synced']) && $_REQUEST['synced'] ) {
		echo '<div id="message" class="updated fade"><p><strong>The following users are synchronised<br /></strong>';
		$integrator->showUsers();
		echo '</p></div>';
	}
	?>
<div style="width:80%;float:left;position:relative">
<h2><?php echo $zing_ws_name; ?></h2>
	<?php
	if (ZING_CMS=='dp' || ZING_CMS=="jl") zing_admin_notices();
	$zing_eaw=zing_check();
	$zing_errors=$zing_eaw['errors'];
	$zing_warnings=$zing_eaw['warnings'];
	$zing_version=get_option("zing_webshop_version");

	if ($zing_errors) {
		echo '<div style="background-color:pink" id="message" class="updated fade"><p>';
		echo '<strong>Errors - it is strongly recommended you resolve these errors before continuing:</strong><br /><br />';
		foreach ($zing_errors as $zing_error) echo $zing_error.'<br />';
		echo '</p></div>';
	}
	if ($zing_warnings) {
		echo '<div style="background-color:peachpuff" id="message" class="updated fade"><p>';
		echo '<strong>Warnings - you might want to have a look at these issues to avoid surprises or unexpected behaviour:</strong><br /><br />';
		foreach ($zing_warnings as $zing_warning) echo $zing_warning.'<br />';
		echo '</p></div>';
	}

	?>
<form method="post"><?php if (ZING_CMS=='jl') echo '<input type="hidden" name="option" value="com_zingiriwebshop" />';?>
<table class="optiontable">

<?php 
	$firstHeading=true;

	if ($zing_ws_options) foreach ($zing_ws_options as $value) {
	
	if ($value['type'] == "text") { ?>

	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
			type="<?php echo $value['type']; ?>"
			value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>"
			size="<?php echo isset($value['size']) ? $value['size'] : 40?>"
		/></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small>
		</td>
	</tr>

	<?php } elseif ($value['type'] == "textarea") { ?>
	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="50"
			rows="8"
		/>
		<?php if ( get_option( $value['id'] ) != "") { echo stripslashes (get_option( $value['id'] )); }
		else { echo $value['std'];
		} ?>
</textarea></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small>
		<hr />
		</td>
	</tr>

	<?php } elseif ($value['type'] == "select") { ?>

	<tr align="left">
		<th scope="top"><?php echo $value['name']; ?>:</th>
		<td><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
		<?php foreach ($value['options'] as $option) { ?>
			<option <?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
			<?php } ?>
		</select></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small></td>
	</tr>

	<?php } elseif ($value['type'] == "selectwithkey") { ?>

	<tr align="left">
		<th scope="top"><?php echo $value['name']; ?>:</th>
		<td><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
		<?php foreach ($value['options'] as $key => $option) { ?>
			<option value="<?php echo $key;?>"
			<?php 
			if ( get_option($value['id']) && get_option( $value['id'] ) == $key) { echo ' selected="selected"'; }
			elseif ( !get_option($value['id']) && $value['std'] == $key) { echo ' selected="selected"'; }
			?>
			><?php echo $option; ?></option>
			<?php } ?>
		</select></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small></td>
	</tr>

	<?php } elseif ($value['type'] == "heading") { ?>
	<tr valign="top">
		<td colspan="2" style="text-align: left;">
		<?php if ($firstHeading) $firstHeading=false; else echo '<hr />'; ?>
		<h3 class="title"><?php echo $value['name']; ?></h3>
		</td>
	</tr>
	<tr>
		<td colspan=2><small>
		<p style="color: red; margin: 0 0;"><?php echo $value['desc']; ?></P>
		</small></td>
	</tr>

	<?php } ?>
	<?php
}
?>
</table>

<?php if (!defined('WP_ZINGIRI_LIVE') && !$zing_version) {?>
<p class="submit"><input name="install" type="submit" value="Install" /> <?php } elseif (!defined('WP_ZINGIRI_LIVE') && !wsVersion()) {?>
<p class="submit"><input name="install" type="submit" value="Upgrade" /> <?php } elseif (!defined('WP_ZINGIRI_LIVE')) {?>
<p class="submit"><input name="install" type="submit" value="Update" /> <?php if (!defined('WP_ZINGIRI_LIVE') && $integrator->wpAdmin) {?>
<hr />
You can synchronise Wordpress and Web Shop back office users. Wordpress adminstrators and editors
are given the Web Shop administrator rights.
<p class="submit"><input name="sync" type="submit" value="Sync users" /> <?php }?> <?php }?> <input
	type="hidden" name="action" value="install"
/></p>

</form>
<?php if (!defined('WP_ZINGIRI_LIVE') && wsVersion() && !$integrator->wpAdmin) { ?>
<hr />
<p>Please note that you have selected to use the user administration in the Zingiri Webshop.<br />
If you wish you can use your own CMS user administration instead by selecting the appropriate option
above.<br />
<br />
If it's your first time logging in, you can use user <strong>admin</strong> with password <strong>admin_1234</strong>
to login to the web shop.</p>
<?php if (ZING_CMS=="wp") {?>
<form method="post" action="<?php echo get_option("home");?>/index.php?page=admin">
<p class="submit"><input name="admin" type="submit" value="Admin" /></p>
</form>
<?php }?> <?php } if (!defined('WP_ZINGIRI_LIVE') && $zing_version) {?>
<hr />
<form method="post">
<p class="submit"><input name="uninstall" type="submit" value="Uninstall" /> <input type="hidden"
	name="action" value="uninstall"
/></p>
</form>
<?php }?>
<hr />
<p>For more info and support, contact us at <a href="http://www.zingiri.com/web-shop/">Zingiri</a>
or check out our <a href="http://forums.zingiri.com/">support forums</a>.</p>
<hr />
<?php
if ($zing_version) {
	$index_refer=1;
	require(dirname(__FILE__).'/fws/about.php');
}
	echo '</div>';
	//share and donate
	echo '<div style="width:20%;float:right;position:relative">';
	if (get_option('zing_webshop_version')) require(ZING_LOC.'support-us.inc.php');
	echo '</div>';	

}
if (ZING_CMS=='wp') add_action('admin_menu', 'zing_ws_add_admin'); 
?>