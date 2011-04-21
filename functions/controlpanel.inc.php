<?php
function zing_set_options() {
	global $zing_ws_options,$zing_ws_name,$zing_ws_shortname;
	global $db_prefix,$base_path,$base_url,$db_url;
	
	$zing_ws_name = "Zingiri Web Shop";
	$zing_ws_shortname = "zing_ws";
	$install_type = array("Yes","No");
	$zing_yn = array("Yes", "No");

	$zing_ws_options = array (
	array(  "name" => "Zingiri Web Shop Settings",
    	        "type" => "heading",
				"desc" => 'Start by signing up for a <a href="http://www.zingiri.com/portal/cart.php?a=add&pid=6">Zingiri Web Shop Live</a> account. Use the discount code <strong style="color:black">WSLIVE</strong> to obtain a 3 month free trial.<br />&nbsp',
	)
	);

	$zing_ws_options[]=	array(	"name" => "Base URL",
		"desc" => "URL of the Zingiri Web Shop Live service. Normally this should be left to it's default value.",
		"id" => $zing_ws_shortname."_baseurl",
		"std" => 'http://live.zingiri.com/',
		"type" => "text");
	
	$zing_ws_options[]=	array(	"name" => "Account ID",
		"desc" => "Your Zingiri Web Shop Live account ID.",
		"id" => $zing_ws_shortname."_accname",
		"std" => '',
		"type" => "text");
	
	$zing_ws_options[]=	array(	"name" => "Cache directory",
		"desc" => 'The Web Shop uses a local cache directory to operate. Make sure this directory exists and is writable and don\'t forget the trailing /.<br />For best security we suggest that you put this directory outside of your public html directory.',
		"id" => $zing_ws_shortname."_cache",
		"std" => BLOGUPLOADDIR.'zingiri-web-shop/'.md5(BLOGUPLOADURL.md5(BLOGUPLOADURL.time())).'/',
		"type" => "text");
	
	/* still to be implemented
	$zing_ws_options[]=	array(	"name" => "Single sign on",
		"desc" => "If selected, users logged in to your site are automatically signed in to the web shop.",
		"id" => $zing_ws_shortname."_sso",
		"std" => "y",
		"type" => "select",
		"options" => array("y" => "Yes","n" => "No"));
		*/
}



function zing_ws_admin() {

	global $zing_ws_name, $zing_ws_shortname, $zing_ws_options, $integrator;

	zing_set_options();
	
	if ( $_REQUEST['installed'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' installed.</strong></p></div>';
	if ( $_REQUEST['uninstalled'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' uninstalled.</strong></p></div>';
	?>
<div class="wrap">
	<?php
	$zing_errors=zing_check();
	$zing_version=get_option("zing_webshop_version");

	if ($zing_errors) {
		echo '<div style="background-color:pink" id="message" class="updated fade"><p>';
		echo '<strong>Errors - it is strongly recommended you resolve these errors before continuing:</strong><br /><br />';
		foreach ($zing_errors as $zing_error) echo $zing_error.'<br />';
		echo '</p></div>';
	}

	?>
<form method="post">
<?php if (ZING_CMS=='jl') echo '<input type="hidden" name="option" value="com_zingiriwebshop" />';?>
<table class="optiontable">

<?php if ($zing_ws_options) foreach ($zing_ws_options as $value) {

	if ($value['type'] == "text") { ?>

	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
			type="<?php echo $value['type']; ?>"
			value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>"
			size="80"
		/></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small>
		<br /><br />
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
		<?php foreach ($value['options'] as $option => $label) { ?>
			<option value="<?php echo $option?>" <?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $label; ?></option>
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
			<?php if ( get_option( $value['id'] ) == $key) { echo ' selected="selected"'; }?>
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
<center class="submit"><input name="install" type="submit" value="Update" /></center>
<input type="hidden"
	name="action" value="install"
/>
</form>
<hr />
<?php if (zing_ws_active_install()) {
	echo '<h3>Migrate</h3>';
	echo '<p style="">';
	echo 'To migrate your curent local data, first download <a href="'.ZING_UPLOADS_URL.'zingiri-web-shop.zip">this file</a> to your computer and then upload it to your <a href="http://live.zingiri.com">Zingiri Live Admin Panel</a>.';
	echo '</p>';
	zing_ws_migrate();
}
?>
<br />
<hr />
<h3>Support</h3>
<p>For more info and support, contact us at <a href="http://www.zingiri.com/web-shop/">Zingiri</a>
or check out our <a href="http://forums.zingiri.com/">support forums</a>.</p>
<br /><hr />
<center><image src="<?php echo ZING_URL;?>zingiri-logo.png"/></center>
<?php
}
?>