<?php
$zing_ws_name = "Zingiri Web Shop";
$zing_ws_shortname = "zing_ws";
$install_type = array("Clean" );

$zing_ws_options = array (

array(  "name" => "Zingiri Web Shop Settings",
            "type" => "heading",
			"desc" => "This section customizes the Zingiri Web Shop area.",
),

array(	"name" => "Type of install",
			"desc" => "Select the type install.",
			"id" => $zing_ws_shortname."_install",
			"std" => "Clean",
			"type" => "select",
			"options" => $install_type),
 
);

function zing_ws_add_admin() {

	global $zing_ws_name, $zing_ws_shortname, $zing_ws_options;

	if ( $_GET['page'] == basename(__FILE__) ) {

		
		if ( 'install' == $_REQUEST['action'] ) {
			zing_activate();
			foreach ($zing_ws_options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
			}

			foreach ($zing_ws_options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) { 
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); 
				} else { delete_option( $value['id'] ); 
				} 
			}
			header("Location: options-general.php?page=controlpanel.php&installed=true");
			die;
		} 
		
		if( 'uninstall' == $_REQUEST['action'] ) {
			zing_uninstall();
			foreach ($zing_ws_options as $value) {
				delete_option( $value['id'] );
				update_option( $value['id'], $value['std'] );
			}
			header("Location: options-general.php?page=controlpanel.php&uninstalled=true");
			die;
		}
	}

	add_options_page($zing_ws_name." Options", "$zing_ws_name", 8, basename(__FILE__), 'zing_ws_admin');
}

function zing_ws_admin() {

	global $zing_ws_name, $zing_ws_shortname, $zing_ws_options;

	if ( $_REQUEST['installed'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' installed.</strong></p></div>';
	if ( $_REQUEST['uninstalled'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' uninstalled.</strong></p></div>';

?>
<div class="wrap">
<h2><b><?php echo $zing_ws_name; ?></b></h2>

<?php 
$zing_version=get_option("zing_webshop_version");
if (empty($zing_version))
	echo 'Please proceed with a clean install or deactivate your plugin';
elseif ($zing_version != ZING_VERSION) 
	echo 'You downloaded version '.ZING_VERSION.' and need to upgrade your database (currently at version '.$zing_version.').';
elseif ($zing_version == ZING_VERSION)
	echo 'Your version is up to date!';
	 	
?>
<?php if ($zing_version != ZING_VERSION) { ?>
<form method="post">

<table class="optiontable">

<?php if ($zing_ws_options) foreach ($zing_ws_options as $value) {

	if ($value['type'] == "text") { ?>

	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><input name="<?php echo $value['id']; ?>"
			id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>"
			value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>"
			size="40" /></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small>
		<hr />
		</td>
	</tr>

	<?php } elseif ($value['type'] == "textarea") { ?>
	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><textarea name="<?php echo $value['id']; ?>"
			id="<?php echo $value['id']; ?>" cols="50" rows="8" />
			<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes (get_settings( $value['id'] )); }
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
		<td><select name="<?php echo $value['id']; ?>"
			id="<?php echo $value['id']; ?>">
			<?php foreach ($value['options'] as $option) { ?>
			<option
			<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
			<?php } ?>
		</select></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small>
		<hr />
		</td>
	</tr>

	<?php } elseif ($value['type'] == "heading") { ?>

	<tr valign="top">
		<td colspan="2" style="text-align: left;">
		<h2 style="color: green;"><?php echo $value['name']; ?></h2>
		</td>
	</tr>
	<tr>
		<td colspan=2><small>
		<p style="color: red; margin: 0 0;"><?php echo $value['desc']; ?></P>
		</small>
		<hr />
		</td>
	</tr>

	<?php } ?>
	<?php
}
?>
</table>

<p class="submit"><input name="install" type="submit" value="Install" />
<input type="hidden" name="action" value="install" /></p>
</form>
<?php } ?>
<?php if ($zing_version) { ?>
<hr />
<p>Please note that the user administration in the Zingiri Webshop is separate from <br />
	the Wordpress user administration (mainly for security purposes).<br /><br />
	If it's your first time logging in, you can use user <strong>admin</strong> with password <strong>admin_1234</strong>.</p>
<form method="post" action="<?php echo get_option("home");?>/index.php?page=admin">
<p class="submit"><input name="admin" type="submit"
	value="Admin" /></p>
</form>
<hr />
<form method="post">
<p class="submit"><input name="uninstall" type="submit"
	value="Uninstall" /> <input type="hidden" name="action"
	value="uninstall" /></p>
</form>
<?php } ?>
<hr />
<p>For more info and support, contact us at <a
	href="http://www.zingiri.com/webshop/">Zingiri</a> or check out our <a
	href="http://forums.zingiri.com/">support forums</a>.</p>
<?php
}
add_action('admin_menu', 'zing_ws_add_admin'); ?>