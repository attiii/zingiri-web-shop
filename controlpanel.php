<?php

//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');
if (get_option('zing_webshop_version')) {
//	require(dirname(__FILE__).'/zing.dashboard.php');
}


function zing_set_options() {
	global $wpdb,$zing_ws_options,$zing_ws_name,$zing_ws_shortname;

	$zing_ws_name = "Zingiri Web Shop";
	$zing_ws_shortname = "zing_ws";
	$install_type = array("Yes","No");
	$zing_yn = array("Yes", "No");

	$zing_ws_options = array (

	array(  "name" => "Zingiri Web Shop Settings",
            "type" => "heading",
			"desc" => "This section customizes the Zingiri Web Shop area.",
	)
	);
//	$zing_ws_options[]=array(  "name" => "Zingiri Web Shop Integration",
//            "type" => "heading",
//			"desc" => "This section customizes the way Zingiri Web Shop integrates with Wordpress.",);

	$zing_ws_options[]=	array(	"name" => "User management",
			"desc" => "Select whether you want to use full integration<br />with Wordpress user management or<br />Zingiri's stand alone user management.",
			"id" => $zing_ws_shortname."_login",
			"std" => "Zingiri",
			"type" => "select",
			"options" => array("Zingiri","WP"));

	$zing_ws_options[]=	array(	"name" => "Javascript library",
			"desc" => "Select the javascript library you want to activate.<br />Our plugin fully supports Prototype and jQuery. If your theme uses either of these it is best to select the same.<br />If your theme uses another one, you can try with the jQuery library and if it doesn't work you can deactivate javascript by selecting Off.",
			"id" => $zing_ws_shortname."_effects",
			"std" => "jQuery",
			"type" => "select",
			"options" => array("jQuery","Prototype","Off"));

	if ($ids=get_option("zing_webshop_pages")) {
		$ida=explode(",",$ids);
		foreach ($ida as $i) {
			$p = $wpdb->get_results( "SELECT post_title FROM ".$wpdb->prefix."posts WHERE id='".$i."'" );
			$zing_ws_options[]=array(	"name" => $p[0]->post_title." page",
			"desc" => "Display ".$p[0]->post_title." page in the menus.",
			"id" => $zing_ws_shortname."_show_menu_".$i,
			"std" => "Yes",
			"type" => "select",
			"options" => $zing_yn);
		}
	}
	$zing_ws_options[]= array(	"name" => "Newsletter",
			"desc" => "We regularly send out a newsletter containing information about new releases, security warnings, ... 
			<br />If you don't wish to receive this newsletter, please select 'No'.
			<br />If you choose to receive the newsletter, we will send it to <strong>".get_option('admin_email')."</strong>
			<br />You can change this option at any time.",
			"id" => $zing_ws_shortname."_install",
			"std" => "Yes",
			"type" => "select",
			"options" => $install_type);
}
function zing_ws_add_admin() {

	global $zing_ws_name, $zing_ws_shortname, $zing_ws_options, $menus, $txt, $wpdb, $zing_version, $integrator;
	global $dbtablesprefix;
	if ($zing_version) require(dirname(__FILE__).'/zing.startmodules.inc.php');
	
	zing_set_options();

	if ( $_GET['page'] == "zingiri-web-shop" ) {

		if( isset($_REQUEST['sync']) ) {
			$integrator->sync();
			header("Location: options-general.php?page=zingiri-web-shop&synced=true");
			die;
		}

		if ( 'install' == $_REQUEST['action'] ) {
			zing_install();
			foreach ($zing_ws_options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] );
			}

			foreach ($zing_ws_options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) {
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
				} else { delete_option( $value['id'] ); }
			}

			$integrator->sync();

			header("Location: options-general.php?page=zingiri-web-shop&installed=true");
			die;
		}

		if( 'uninstall' == $_REQUEST['action'] ) {
			zing_uninstall();
			foreach ($zing_ws_options as $value) {
				delete_option( $value['id'] );
			}
			header("Location: options-general.php?page=zingiri-web-shop&uninstalled=true");
			die;
		}

	}

	add_menu_page($zing_ws_name, $zing_ws_name, 'administrator', 'zingiri-web-shop','zing_ws_admin');
	add_submenu_page('zingiri-web-shop', $zing_ws_name.'- Integration', 'Integration', 'administrator', 'zingiri-web-shop', 'zing_ws_admin');
	if ($zing_version && $integrator->wpAdmin) {
		foreach ($menus as $page => $menu) {
			if (!$menu['hide']) {
				add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], 'administrator', $page, 'zing_ws_settings');
			}
		}
		if ($menus[$_GET['page']] && $menus[$_GET['page']]['hide']) {
			$menu=$menus[$_GET['page']];
			add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], 'administrator', $_GET['page'], 'zing_ws_settings');
		}
	}

}

function zing_ws_settings() {
	global $menus,$txt,$wpdb;

	zing_header();
	
	echo '<div style="height:64px;float">';
	echo '<center>';
	foreach ($menus as $id => $menu) {
		if (!$menu['hide']) {
			echo '<a href="?page='.$id.'" alt="'.$txt[$menu['label']].'">';
			echo '<img align="top" class="zing_cp_icon" height="32px" width="32px" id="'.$id.'" src="'.ZING_URL.'fws/templates/default/images/'.$menu['img'].'" alt="'.$txt[$menu['label']].'" />';
			echo '</a>';
		}
	}
	echo '</center>';
	echo '<div style="display:none" id="icon_label"></div>';
	echo '</div>';

	echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/controlpanel.js"></script>';

	//main window
	echo '<div style="width:80%;float:left;position:relative">';
	$page=$_GET['page'];
	//echo $page.'-'.$menus[$page]['href'];
	$params=array();
	$pairs=explode('&',$menus[$page]['href']);
	foreach ($pairs as $pair) {
		list($n,$v)=explode('=',$pair);
		if ($n!='page') {
			if (($n=='form' || $n=='formid') && (isset($_GET['form']) || isset($_GET['formid']))) break;
			elseif (!isset($_GET[$n])) $_GET[$n]=$v;
			$params[$n]=$v;
		}
	}

	if (isset($menus[$page]['page'])) $_GET['page']=$menus[$page]['page'];
	echo '<link rel="stylesheet" type="text/css" href="'.ZING_URL.'zing.css" />';
	zing_main('content');
	if (isset($menus[$page]['type']) && $menus[$page]['type']=="apps") {
		//$_GET['no_redirect']=true;
		echo '<link rel="stylesheet" type="text/css" href="'.ZING_APPS_PLAYER_URL.'css/integrated_view.css" />';
		
		zing_apps_player_content('content');
	}
	echo '</div>';
	
	//news
	echo '<div class="updated" style="width:15%;float:right;position:relative">';
	global $current_user;
	get_currentuserinfo();
	$db=$wpdb->get_results( "SELECT count(*) as oc FROM ".$wpdb->prefix."zing_order");
	require(dirname(__FILE__).'/fws/includes/httpclass.inc.php');
	$news = new HTTPRequest('http://www.zingiri.com/news.php?e='.urlencode(isset($current_user->user_email) ? $current_user->user_email : $sales_mail).'&w='.urlencode(ZING_HOME).'&a='.get_option("zing_ws_install").'&v='.urlencode(ZING_VERSION).'&oc='.(string)$db[0]->oc);
	if ($news->live() && !$_SESSION['zing']['news']) {
		update_option('zing_ws_news',$news->DownloadToString());
		//echo $news->DownloadToString();
		$_SESSION['zing']['news']=true;
	}
	echo '<h3>Latest news</h3>';
	echo get_option('zing_ws_news');
	
	echo '</div>';
	

}

function zing_ws_admin() {

	global $zing_ws_name, $zing_ws_shortname, $zing_ws_options, $integrator;

	zing_set_options();

	if ( $_REQUEST['installed'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' installed.</strong></p></div>';
	if ( $_REQUEST['uninstalled'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_ws_name.' uninstalled.</strong></p></div>';
	if ( $_REQUEST['synced'] ) {
		echo '<div id="message" class="updated fade"><p><strong>The following users are synchronised<br /></strong>';
		$integrator->showUsers();
		echo '</p></div>';
	}
	?>
<div class="wrap">
<h2><?php echo $zing_ws_name; ?></h2>
<?php
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
//elseif (!$zing_errors && !$zing_warnings)	echo 'Your version is up to date!';

?>
<form method="post">

<table class="optiontable">

<?php if ($zing_ws_options) foreach ($zing_ws_options as $value) {

	if ($value['type'] == "text") { ?>

	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
			type="<?php echo $value['type']; ?>"
			value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>"
			size="40"
		/></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small>
		<hr />
		</td>
	</tr>

	<?php } elseif ($value['type'] == "textarea") { ?>
	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="50"
			rows="8"
		/>
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
		<td><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
		<?php foreach ($value['options'] as $option) { ?>
			<option <?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
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

<?php if (!$zing_version) {?>
<p class="submit"><input name="install" type="submit" value="Install" /> <?php } elseif ($zing_version != ZING_VERSION) {?>
<p class="submit"><input name="install" type="submit" value="Upgrade" /> <?php } else {?>
<p class="submit"><input name="install" type="submit" value="Update" /> <?php if ($integrator->wpAdmin) {?>
<hr />
You can synchronise Wordpress and Web Shop back office users. Wordpress adminstrators and editors
are given the Web Shop administrator rights.
<p class="submit"><input name="sync" type="submit" value="Sync users" /> <?php }?> <?php }?> <input
	type="hidden" name="action" value="install"
/></p>

</form>
<?php if ($zing_version == ZING_VERSION && !$integrator->wpAdmin) { ?>
<hr />
<p>Please note that you have selected to use the user administration in the Zingiri Webshop.<br />
If you wish you can use the Wordpress user administration instead by selecting the appropriate option above.<br />
<br />
If it's your first time logging in, you can use user <strong>admin</strong> with password <strong>admin_1234</strong>.</p>
<form method="post" action="<?php echo get_option("home");?>/index.php?page=admin">
<p class="submit"><input name="admin" type="submit" value="Admin" /></p>
</form>
<?php } if ($zing_version) {?>
<hr />
<form method="post">
<p class="submit"><input name="uninstall" type="submit" value="Uninstall" /> <input type="hidden"
	name="action" value="uninstall"
/></p>
</form>
<?php }?>
<hr />
<p>For more info and support, contact us at <a href="http://www.zingiri.com/web-shop/">Zingiri</a> or
check out our <a href="http://forums.zingiri.com/">support forums</a>.</p>
<hr />
<?php
if ($zing_version) {
	$index_refer=1;
	require(dirname(__FILE__).'/fws/about.php');
}
}
add_action('admin_menu', 'zing_ws_add_admin'); ?>