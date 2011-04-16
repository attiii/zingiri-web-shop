<?php
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

	if (ZING_CMS=='wp') {
		$zing_ws_options[]=	array(	"name" => "User management",
			"desc" => "Select whether you want to use full integration with Wordpress user management or Zingiri's stand alone user management.",
			"id" => $zing_ws_shortname."_login",
			"std" => "WP",
			"type" => "select",
			"options" => array("WP","Zingiri"));
	} elseif (ZING_CMS=='dp') {
		$zing_ws_options[]=	array(	"name" => "User management",
			"desc" => "Select whether you want to use full integration with Drupal user management (not yet implemented) or Zingiri's stand alone user management.",
			"id" => $zing_ws_shortname."_login",
			"std" => "Zingiri",
			"type" => "select",
			"options" => array("Zingiri"));

	} else {
		$zing_ws_options[]=	array(	"name" => "User management",
			"desc" => "Select whether you want to use full integration with Joomla user management (not yet implemented) or Zingiri's stand alone user management.",
			"id" => $zing_ws_shortname."_login",
			"std" => "WP",
			"type" => "select",
			"options" => array("Zingiri"));
	}

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
	$zing_ws_options[]= array(	"name" => "Logo",
			"desc" => "Select how and where you want to display the Zingiri logo. You can display it at the bottom of your site or a the bottom of every shop page.<br />Only select other if you received a written confirmation from us that it is ok to do so.",
			"id" => $zing_ws_shortname."_logo",
			"std" => "sf",
			"type" => "selectwithkey",
			"options" => array('sf'=>'In site footer','pf'=>'At bottom of page','na'=>'Other'));
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
	if ($zing_version) require(dirname(__FILE__).'/startmodules.inc.php');

	zing_set_options();
	if ((ZING_CMS=='dp' && strstr($_GET['q'],'admin/webshop')) || (ZING_CMS=='wp' && $_GET['page']=='zingiri-web-shop') || (ZING_CMS=='jl' && $_REQUEST['option'] == "com_zingiriwebshop") ) {
		if( isset($_REQUEST['sync']) ) {
			$integrator->sync();
			if (ZING_CMS=='wp') header("Location: admin.php?page=zingiri-web-shop&synced=true");
			elseif (ZING_CMS=='jl') header("Location: index?option=com_zingiriwebshop&synced=true");
			elseif (ZING_CMS=='dp') header("Location: index.php?q=admin/webshop/integration&synced=true");
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

			if (ZING_CMS=="dp") { menu_router_build(TRUE); menu_cache_clear_all(); }

			if (ZING_CMS=='wp') header("Location: admin.php?page=zingiri-web-shop&installed=true");
			elseif (ZING_CMS=='jl') header("Location: index.php?option=com_zingiriwebshop&installed=true");
			elseif (ZING_CMS=='dp') header("Location: index.php?q=admin/webshop/integration&installed=true");
			die;
		}

		if( 'uninstall' == $_REQUEST['action'] ) {
			zing_uninstall();
			foreach ($zing_ws_options as $value) {
				delete_option( $value['id'] );
			}

			if (ZING_CMS=="dp") { $zing_version=''; menu_router_build(TRUE); menu_cache_clear_all(); }

			if (ZING_CMS=='wp') header("Location: admin.php?page=zingiri-web-shop&uninstalled=true");
			elseif (ZING_CMS=='jl') header("Location: index.php?option=com_zingiriwebshop&uninstalled=true");
			elseif (ZING_CMS=='dp') header("Location: index.php?q=admin/webshop/integration&uninstalled=true");
			die;
		}

	}

	if (ZING_CMS=='wp') {
		zing_ws_admin_menus();
	} elseif (ZING_CMS=='jl') {
		//zing_ws_admin_menus();
	}

}

function zing_ws_settings() {
	global $menus,$txt,$wpdb,$dbtablesprefix,$action;

	//	if ($action=='app_head') $action='';
	zing_header();
	zing_apps_player_header_cp();

	//main window
	echo '<div style="width:80%;float:left;position:relative">';
	$_GET['page']=str_replace('zingiri-web-shop-','',$_GET['page']);
	$page=$_GET['page'];
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
	if (ZING_CMS=='wp' || ZING_CMS=='jl') echo '<link rel="stylesheet" type="text/css" href="'.ZING_URL.'zing.css" />';
	if (isset($_GET['page'])) {
		require(dirname(__FILE__).'/fws/includes/pages.inc.php');
		echo '<h1>'.$txt[$wsPages[$page]].'</h1>';
	}

	zing_main('content');
	if ((isset($menus[$page]['type']) && $menus[$page]['type']=="apps") || ZING_CMS!='wp') {
		//echo '<link rel="stylesheet" type="text/css" href="'.ZING_APPS_PLAYER_URL.'css/apps_wp_admin.css" />';
		zing_apps_player_content('content');
	}
	echo '</div>';

	echo '<div style="width:20%;float:right;position:relative">';
	
	//share and donate
	if (!defined('WP_ZINGIRI_LIVE')) {
		echo '<div class="updated" style="">';
		echo '<h3>Support Us</h3>';
		echo '<p>If you like this plugin, please share it with your friends and help us out with a small token of appreciation</p>';
		echo '<form style="margin-bottom:15px;text-align:center;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		  <input type="hidden" name="cmd" value="_s-xclick">
		  <input type="hidden" name="hosted_button_id" value="ZK6CCBG2TPTXQ">
		  <input align="middle" type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
		  <img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
		  </form>';
		echo '<div style="align:center;margin-bottom:15px;text-align:center">';
		echo '<a style="margin-bottom:15px;" href="http://www.twitter.com/zingiri"><img align="middle" src="http://twitter-badges.s3.amazonaws.com/follow_us-a.png" alt="Follow Zingiri on Twitter"/></a>';
		echo '</div>';
		echo '<div style="margin-bottom:15px;text-align:center">';
		echo '<fb:share-button href="http://www.zingiri.com" type="button" >';
		echo '</div>';
		echo '</div>';
	}

	//news
	echo '<div class="updated" style="">';
	global $current_user;
	get_currentuserinfo();
	$query="SELECT count(*) as oc FROM ".$dbtablesprefix."order";
	$sql = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($sql);

	require(dirname(__FILE__).'/fws/includes/httpclass.inc.php');
	$news = new wsNewsRequest('http://www.zingiri.com/news.php?e='.urlencode(isset($current_user->user_email) ? $current_user->user_email : $sales_mail).'&w='.urlencode(ZING_HOME).'&a='.get_option("zing_ws_install").'&v='.urlencode(ZING_VERSION).'&oc='.(string)$row['oc']);
	if ($news->live() && !$_SESSION['zing_session']['news']) {
		if (ZING_CMS=='jl') update_option('zing_ws_news',urlencode($news->DownloadToString()));
		else update_option('zing_ws_news',$news->DownloadToString());
		$_SESSION['zing_session']['news']=true;
	}
	echo '<h3>Latest news</h3>';
	if (ZING_CMS=='jl') echo urldecode(get_option('zing_ws_news'));
	else echo get_option('zing_ws_news');
	echo '</div>';

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
	//elseif (!$zing_errors && !$zing_warnings)	echo 'Your version is up to date!';

	?>
<form method="post"><?php if (ZING_CMS=='jl') echo '<input type="hidden" name="option" value="com_zingiriwebshop" />';?>
<table class="optiontable">

<?php if ($zing_ws_options) foreach ($zing_ws_options as $value) {

	if ($value['type'] == "text") { ?>

	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
			type="<?php echo $value['type']; ?>"
			value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>"
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

<?php if (!$zing_version) {?>
<p class="submit"><input name="install" type="submit" value="Install" /> <?php } elseif (!wsVersion()) {?>
<p class="submit"><input name="install" type="submit" value="Upgrade" /> <?php } else {?>
<p class="submit"><input name="install" type="submit" value="Update" /> <?php if ($integrator->wpAdmin) {?>
<hr />
You can synchronise Wordpress and Web Shop back office users. Wordpress adminstrators and editors
are given the Web Shop administrator rights.
<p class="submit"><input name="sync" type="submit" value="Sync users" /> <?php }?> <?php }?> <input
	type="hidden" name="action" value="install"
/></p>

</form>
<?php if (wsVersion() && !$integrator->wpAdmin) { ?>
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
<?php }?> <?php } if ($zing_version) {?>
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
}
if (ZING_CMS=='wp') add_action('admin_menu', 'zing_ws_add_admin'); ?>