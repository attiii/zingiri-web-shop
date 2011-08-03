<?php
function zing_ws_live_set_options_signup() {
	global $zing_ws_options,$zing_ws_name,$zing_ws_shortname;
	global $db_prefix,$base_path,$base_url,$db_url;

	$zing_ws_name = "Zingiri Web Shop";
	$zing_ws_shortname = "zing_ws";
	$zing_yn = array("Yes", "No");

	$carturl="http://www.clientcentral.info/cart.php?a=add&pid=102&carttpl=modern";
	if (get_option($zing_ws_shortname."_accname") && !$_REQUEST['confirm']) {
		$desc ='It seems you have signed up already for a Zingiri Web Shop Live account. If you want to sign up again, please confirm by clicking <a href="admin.php?page=wslive&confirm=1">here</a>.';
	} else {
		$desc ='Start by signing up for a free Zingiri Web Shop Live Trial account by filling in the form below. Once you have completed the sign up, you will receive an email with your account ID which you can then fill in <a href="admin.php?page=zingiri-web-shop">here</a>.';
		$iframe.='<iframe src="'.$carturl.'" width="100%" height="1400"></iframe>';
	}
	
	$zing_ws_options = array (
	array(  "name" => $desc,
    	        "type" => "heading",
				"desc" => $iframe,
	));
}

function zing_ws_signup() {

	global $zing_ws_name, $zing_ws_shortname, $zing_ws_options, $integrator;

	zing_ws_live_set_options_signup();

	if ( $_REQUEST['installed'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' installed.</strong></p></div>';
	if ( $_REQUEST['uninstalled'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' uninstalled.</strong></p></div>';
	?>
<div class="wrap"><?php
if (ZING_CMS=='jl') echo '<input type="hidden" name="option" value="com_zingiriwebshop" />';?>
<?php zing_options($zing_ws_options);?>
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