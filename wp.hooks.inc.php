<?php
if ($zing_version) {
	add_action("init","zing_init");
	add_filter('wp_footer','zing_footer');
	add_filter('get_pages','zing_exclude_pages');
	add_action("plugins_loaded", "zing_sidebar_init");
	add_filter('the_content', 'zing_content', 10, 3);
	add_action('wp_head','zing_header');
	add_action('wp_head','zing_ws_header_custom',100);
	add_filter('wp_title','zing_ws_title');
	add_filter('the_title','zing_ws_page_title',10,2);
	if ($integrator->wpCustomer) {
		add_action('wp_login','zing_login');
		add_action('wp_logout','zing_logout');
		add_filter('check_password','zing_check_password',10,4);
		//add_action('personal_options_update','zing_profile_pre'); //before wp error check and update
		//add_action('edit_user_profile_update','zing_profile_pre'); //before wp error check and update
		//add_action('user_profile_update_errors','zing_profile_check_errors',10,3); //check errors after wp checks done
		add_action('profile_update','zing_profile'); //post wp update
		add_action('user_register','zing_profile'); //post wp update
		//add_action('show_user_profile','zing_profile_show');
		//add_action('edit_user_profile','zing_profile_edit');
		add_action('delete_user','zing_delete_user');
	}
}
add_action("init","zing_init_uninstall");
add_action('admin_notices','zing_admin_notices');

function zing_ws_install_default_pages($zing_version) {
	if ($zing_version < '0.9.15') {
		$pages=array();
		$pages[]=array("Shop","main","*",0);
		$pages[]=array("Cart","cart","",0);
		$pages[]=array("Checkout","conditions","checkout",6);
		$pages[]=array("Admin","admin","",9);
		$pages[]=array("Personal","my","",3);
		$pages[]=array("Login","my","login",1);
		$pages[]=array("Logout","logout","*",3);
		$pages[]=array("Register","customer","add",1);

		$ids="";
		foreach ($pages as $i =>$p)
		{
			$my_post = array();
			$my_post['post_title'] = $p['0'];
			$my_post['post_content'] = 'Do not delete this page unless you know what you are doing';
			$my_post['post_status'] = 'publish';
			$my_post['post_author'] = 1;
			$my_post['post_type'] = 'page';
			$my_post['comment_status'] = 'closed';
			$my_post['menu_order'] = 100+$i;
			$id=wp_insert_post( $my_post );
			if (empty($ids)) { $ids.=$id; } else { $ids.=",".$id; }
			if (!empty($p[1])) add_post_meta($id,'zing_page',$p[1]);
			if (!empty($p[2])) add_post_meta($id,'zing_action',$p[2]);
			if (!empty($p[3])) add_post_meta($id,'zing_security',$p[3]);
		}
		if (get_option("zing_webshop_pages"))
		{
			update_option("zing_webshop_pages",$ids);
		}
		else {
			add_option("zing_webshop_pages",$ids);
		}
	}
	//set comment status to closed
	elseif ($zing_version < '1.2.0') {
		$ids=get_option("zing_webshop_pages");
		$ida=explode(",",$ids);
		foreach ($ida as $id) {
			$my_post = array();
			$my_post['ID']=$id;
			$my_post['comment_status'] = 'closed';
			wp_update_post($my_post);
		}
	}

	//Update registration page
	$ids=get_option("zing_webshop_pages");
	$ida=explode(",",$ids);
	$id=$ida[7];
	delete_post_meta($id,'zing_form');
	delete_post_meta($id,'zfaces');
	add_post_meta($id,'zing_page','customer');
	add_post_meta($id,'zing_action','add');

	//default Apps page
	$ps=explode(",",get_option("zing_webshop_pages"));
	update_option("zing_apps_player_page",$ps[0]);
}

/**
 * Activation of web shop: creation of database tables & set up of pages
 * @return unknown_type
 */
function zing_activate() {
	//nothing happening here
}

/**
 * Deactivation of web shop
 * @return void
 */
function zing_deactivate() {
	wp_clear_scheduled_hook('zing_ws_cron_hook');
}

function zing_ws_uninstall_delete_pages() {
	global $wpdb;
	$ids=get_option("zing_webshop_pages");
	$ida=explode(",",$ids);
	foreach ($ida as $id) {
		if (!empty($id)) {
			wp_delete_post($id,true);
			$query="delete from ".$wpdb->prefix."postmeta where meta_key in ('zing_page','zing_action','zing_security')";
			$wpdb->query($query);
		}
	}
}

/**
 * Header hook: loads FWS addons and css files
 * @return unknown_type
 */
function zing_header()
{
	echo '<script type="text/javascript" language="javascript">';
	echo "var wsURL='".ZING_URL."fws/ajax/';";
	echo "var wpabspath='".ABSPATH."';";
	echo "var wsCms='".ZING_CMS."';";
	echo '</script>';

	if (ZING_PROTOTYPE) {
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/checkout.proto.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/cart.proto.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/search.proto.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/cookie.proto.js"></script>';
	} elseif (ZING_JQUERY) {
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/jquery-ui-1.7.3.custom.min.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/lib.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/Class-0.0.2.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/cookie.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/checkout.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/cart.jquery.js"></script>';
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/search.jquery.js"></script>';
	}
	if (is_admin()) {
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/admin.js"></script>';
	}
	echo '<link rel="stylesheet" type="text/css" href="' . ZING_URL . 'zing.css" media="screen" />';

	echo '<link rel="stylesheet" href="' . ZING_URL . 'fws/addons/lightbox/lightbox.css" type="text/css" media="screen" />';
	echo '<script type="text/javascript" src="' . ZING_URL . 'fws/addons/lightbox/lightbox.js"></script>';
}

function zing_ws_header_custom()
{
	echo '<link rel="stylesheet" type="text/css" href="' . BLOGUPLOADURL . 'zingiri-web-shop/custom.css" media="screen" />';
}

/**
 * Initialization of page, action & page_id arrays
 * @return unknown_type
 */
function zing_init()
{
	session_start();

	if (is_admin() || !defined("ZING_PROTOTYPE") || ZING_PROTOTYPE) {
		wp_enqueue_script('prototype');
		wp_enqueue_script('scriptaculous');
	} elseif (!defined("ZING_JQUERY") || ZING_JQUERY) {
		wp_enqueue_script('jquery');
	}

	ob_start();

	global $dbtablesprefix;
	global $dblocation;
	global $dbname;
	global $dbuser;
	global $dbpass;
	global $product_dir;
	global $brands_dir;
	global $orders_dir;
	global $lang_dir;
	global $template_dir;
	global $gfx_dir;
	global $scripts_dir;
	global $products_dir;
	global $index_refer;
	global $name;
	global $customerid;
	global $integrator;

	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) { return $pages; }

	global $zing_page_id_to_page, $zing_page_to_page_id, $wpdb;
	global $name;
	global $customerid;

	$zing_page_id_to_page=array();
	$zing_page_to_page_id=array();

	$sql = "SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key = 'zing_page'";
	$a = $wpdb->get_results( $sql );

	foreach ($a as $i => $o )
	{
		$zing_page_id_to_page[$o->post_id][0]=$o->meta_value;
	}
	$sql = "SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key = 'zing_action'";
	$a = $wpdb->get_results( $sql );
	foreach ($a as $i => $o )
	{
		$zing_page_id_to_page[$o->post_id][1]=$o->meta_value;
	}

	$zing_page_to_page_id=array();
	foreach ($zing_page_id_to_page as $i => $a)
	{
		$page=$a[0];
		$action=$a[1];
		if (isset($a[0]) && isset($a[1]))
		{
			$zing_page_to_page_id[$page][$action]=$i;
		}
		if (isset($a[0]) && !isset($a[1]))
		{
			$zing_page_to_page_id[$page]['*']=$i;
		}
	}

	if ($_POST['page']=="login")
	{
		include (ZING_LOC."./startmodules.inc.php");
		require(ZING_DIR."login.php");
		exit;
	}
	if ((!empty($_GET['page_id'])) && ($_GET['page_id']==zing_page_id("logout")) || (!empty($_GET['page']) && $_GET['page']=="logout"))
	{
		include (ZING_LOC."./startmodules.inc.php");
		require(ZING_DIR."logout.php");
		exit;
	}

	if (!isset($_GET['page_id']) && isset($_GET['page']))
	{
		//cat is a parameter used by Wordpress for categories
		if (isset($_GET['cat']) && isset($_GET['page'])) {
			$_GET['kat']=$_GET['cat'];
			unset($_GET['cat']);
		}
		$_GET['page_id']=zing_page_id("main");
	}

}

/**
 * Look up FWS page name based on Wordpress page_id
 * @param $page_id
 * @return unknown_type
 */
function zing_page($page_id)
{
	global $zing_page_id_to_page;
	if (isset($zing_page_id_to_page[$page_id]))
	{
		return $zing_page_id_to_page[$page_id];
	}
	return "main";
}

/**
 * Look up Wordpress page_id based on FWS page and action
 * @param $page
 * @param $action
 * @return unknown_type
 */
function zing_page_id($page,$action="*")
{
	global $zing_page_to_page_id;

	if (isset($zing_page_to_page_id[$page][$action]))
	{
		return $zing_page_to_page_id[$page][$action];
	}
	elseif (isset($zing_page_to_page_id[$page]))
	{
		echo $page;
		echo $action;
		echo "this case";die();
		return $zing_page_to_page_id[$page];
	}
	return "";
}

/**
 * Exclude certain pages from the menu depending on whether the user is logged in
 * or is an administrator. This depends on the custom field "security":
 * 	0 - show if not logged in
 * 	1 - show if not logged in but hide if logged in
 *  2 - show if customer logged in
 *  3 - show if customer or user or admin logged in
 * 	4 - show if not logged in or if customer logged in
 *  5 - show if user or customer logged in
 *  6 - show if user or admin logged in
 *  9 - show if admin logged in
 * @param $pages
 * @return unknown_type
 */
function zing_exclude_pages( $pages )
{
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) return $pages;

	Global $dbtablesprefix;
	Global $cntry;
	Global $lang;
	Global $lang2;
	Global $lang3;

	//require (ZING_DIR."./includes/settings.inc.php");        // database settings

	$loggedin=LoggedIn();
	if ($loggedin) $isadmin=IsAdmin();
	if (!$isadmin) $iscustomer=true;

	$unsetpages=array();
	$l=count($pages);
	for ( $i=0; $i<$l; $i++ ) {
		$page = & $pages[$i];
		$security=get_post_meta($page->ID,"zing_security",TRUE);
		$show=false;
		if ($security == 0) {
			$show=true;
		}
		elseif ($security == "1" && !$loggedin) {
			$show=true;
		}
		elseif ($security == "2" && $loggedin && $iscustomer) {
			$show=true;
		}
		elseif ($security == "3" && $loggedin) {
			$show=true;
		}
		elseif ($security == "4" && (!$loggedin || $iscustomer)) {
			$show=true;
		}
		elseif ($security == "5" && $loggedin && !$isadmin && ($isuser || $iscustomer)) {
			$show=true;
		}
		elseif ($security == "6" && ($iscustomer || $isadmin)) { //should really be shown only if something in cart
			$show=true;
		}
		elseif ($security == "9" && $loggedin && $isadmin) {
			$show=true;
		}
		if (!$show || get_option("zing_ws_show_menu_".$page->ID)=="No")
		{
			unset($pages[$i]);
			$unsetpages[$page->ID]=true;
		}
	}

	return $pages;

	/*
	 $l=count($pages);
	 for ( $i=0; $i<$l; $i++ ) {
		$page = & $pages[$i];
		$parent=$page->post_parent;
		if (isset($unsetpages[$parent]))
		{
		unset($pages[$i]);
		}
		}

		return $pages;
		*/
}

/**
 * Register sidebar widgets
 * @return unknown_type
 */
require(dirname(__FILE__).'/extensions/widgets/index.php');
function zing_sidebar_init()
{
	global $wsWidgets;
	foreach ($wsWidgets as $w) {
		//if (isset($w['class'])) register_sidebar_widget(__($w['name']), array($w['class'],'init'));
		if (isset($w['class'])) {
			$wstemp=new $w['class'];
			register_sidebar_widget(__($w['name']), array($wstemp,'init'));
			if (isset($w['control'])) register_widget_control(__($w['name']), array($wstemp,'control'));
		}
		elseif (isset($w['function'])) register_sidebar_widget(__($w['name']), $w['function']);
	}
}

function zing_ws_title($title) {
	if ($_GET['prod']) {
		$prodid=$_GET['prod'];
		$db=new db();
		//echo 'select product from ##product,##category where ##product.catid=##category.id and ##product.id='.qs($prodid);
		$db->select('select `productid`,`desc` from `##product`,`##category` where ##product.catid=##category.id and ##product.id='.qs($prodid));
		if ($db->next()) {
			return $db->get('desc').' &raquo; '.$db->get('productid');
		}
	}
	return $title;
}

function zing_ws_page_title($pageTitle,$id=0) {
	require(ZING_GLOBALS);
	global $post;

	if (!in_the_loop()) return $pageTitle;

	if (!zing_ws_is_shop_page($post->ID) || $id==0 || ($id != $post->ID)) return $pageTitle;

	if (!$zing_loaded)	{
		require (ZING_LOC."./startmodules.inc.php");
		$zing_loaded=TRUE;
	} else {
		require (ZING_DIR."./includes/readvals.inc.php");        // get and post values
	}

	if ($_GET['prod']) {
		$prodid=$_GET['prod'];
		$db=new db();
		$db->select('select `productid`,`desc` from `##product`,`##category` where ##product.catid=##category.id and ##product.id='.qs($prodid));
		if ($db->next()) {
			$pageTitle=$db->get('productid');
		}
	} elseif ($p=$_GET['page']) {
		if (isset($wsPages[$p])) $pageTitle=$txt[$wsPages[$p]];
	} elseif ($_GET['zfaces'] && $p=$_GET['form']) {
		if ($pt=zing_ws_get_form_title($_GET['form'])) $pageTitle=$pt;
	}
	return $pageTitle;
}

function zing_ws_get_form_title($id) {
	$db=new db();
	$query="select label from ##faces where id=".qs($id)." or name=".qs($id);
	if ($db->select($query)) {
		$db->next();
		return z_($db->get('label'));
	}
	return '';

}

function zing_login($loginname) {
	global $dbtablesprefix;

	$query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE (`LOGINNAME`=%s OR `EMAIL`=%s)", quote_smart($loginname),quote_smart($loginname));
	$sql = mysql_query($query) or die(mysql_error());
	if ($row = mysql_fetch_row($sql)) {
		$id = $row[0];
		$name = $row[1];
		$pass = $row[2];
		$group = $row[13];
		if (isset($_COOKIE['fws_guest'])) {
			$fws_cust = $_COOKIE['fws_guest'];
			$sessionid = $fws_cust; // read the sessionid

			// now check if this guest has products in his basket
			$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (CUSTOMERID = ".$sessionid." AND STATUS = 0) ORDER BY ID";
			$sql = mysql_query($query) or die(mysql_error());
			while ($row = mysql_fetch_row($sql)) {
				$update_query = "UPDATE `".$dbtablesprefix."basket` SET `CUSTOMERID` = ".$id." WHERE ID = '".$row[0]."'";
				$update_sql = mysql_query($update_query) or die(mysql_error());
			}
			// now kill the cookie
			setcookie ("fws_guest", "", time() - 3600, '/');
		}

		$cookie_data = $name.'-'.$id.'-'.md5($pass); //name userid and encrypted password
			
		// store IP
		$query = "UPDATE `".$dbtablesprefix."customer` SET `IP` = '".GetUserIP()."' WHERE `ID`=".$id;
		$sql = mysql_query($query) or die(mysql_error());
		// make acccesslog entry
		$query = sprintf("INSERT INTO ".$dbtablesprefix."accesslog (login, time, succeeded) VALUES(%s, '".date("F j, Y, g:i a")."', '1')", quote_smart($name));
		$sql = mysql_query($query) or die(mysql_error());

		setcookie ("fws_cust",$cookie_data, 0, '/'); //time()+3600
	}
}

function zing_logout() {
	setcookie ("fws_cust","", time() - 3600, '/');
}

function zing_check_password($check,$password,$hash,$user_id) {
	global $dbtablesprefix;

	if (!$check) { //the user could be using his old password, pre Web Shop to Wordpress migration
		$user =  new WP_User($user_id);
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE `LOGINNAME`=%s AND `PASSWORD`=%s", quote_smart($user->data->user_login), quote_smart(md5($password)));
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_row($sql)) return true;
		else return false;
	} else return $check;
}

function zing_profile($user_id) {
	$user=new WP_User($user_id);
	$user_data=$user->data;
	$db=new db();

	$row['LASTNAME']=$user_data->user_lastname;
	$row['INITIALS']=$user_data->user_firstname;
	$row['EMAIL']=$user_data->user_email;
	$row['DATE_UPDATED']=date('Y-m-d');
	$pass=$_POST['pass1'];
	if ($pass != '') $row['PASSWORD']=md5($pass);

	if ($user->has_cap('level_5')) $row['GROUP']='ADMIN';
	else $row['GROUP']='CUSTOMER';

	if ($db->readRecord('customer',array('LOGINNAME' => $user_data->user_login))) {
		$db->updateRecord('customer',array('LOGINNAME' => $user_data->user_login), $row);
	} else {
		$row['LOGINNAME']=$user_data->user_login;
		$row['DATE_CREATED']=date('Y-m-d');
		$db->insertRecord('customer',"",$row);
	}
}

function zing_profile_pre($user_id) {
}

function zing_delete_user($id) {
	$user=get_userdata($id);
	$db=new db();
	$db->deleteRecord('customer',array('LOGINNAME' => $user->user_login));
}

//cron
function zing_ws_cron() {
	$cron=get_option('zing_ws_cron');
	$db=new db();
	$query="delete from `##errorlog` where date_add(`time`, interval 7 day) < curdate()";
	$db->update($query);
	$cron.=$query;

	$query="delete from `##accesslog` where date_add(`time`, interval 7 day) < curdate()";
	$db->update($query);
	$cron.=$query;

	update_option('zing_ws_cron',$cron);
}
if ($zing_version) {
	if (!wp_next_scheduled('zing_ws_cron_hook')) {
		wp_schedule_event( time(), 'hourly', 'zing_ws_cron_hook' );
	}
	add_action('zing_ws_cron_hook','zing_ws_cron');
}

function zing_ws_admin_menus() {
	global $zing_ws_name, $zing_ws_name,$txt,$menus,$zing_version;
	add_menu_page($zing_ws_name, 'Web Shop', 'administrator', 'zingiri-web-shop','zing_ws_admin',ZING_URL.'fws/templates/default/images/menu_webshop.png');
	add_submenu_page('zingiri-web-shop', $zing_ws_name.'- Integration', 'Integration', 'administrator', 'zingiri-web-shop', 'zing_ws_admin');
	/*
	 if ($zing_version) {
		foreach ($menus as $page => $menu) {
		if (!$menu['hide']) {
		add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], 'administrator', $page, 'zing_ws_settings');
		}
		}
		if ($menus[$_GET['page']] && $menus[$_GET['page']]['hide']) {
		$menu=$menus[$_GET['page']];
		add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], 'administrator', $_GET['page'], 'zing_ws_settings');
		}
		add_submenu_page('zingiri-web-shop', 'Forms settings', 'Forms settings', 'administrator', 'zingiri-apps', 'zing_apps_settings');
		add_submenu_page('zingiri-web-shop', 'Forms editor', 'Forms editor', 'administrator', 'zingiri-apps-settings', 'zing_apps_editor');
		}
		*/
	if ($zing_version) {
		$groupings=array();
		foreach ($menus as $page => $menu) {
			if (!$menu['hide']) {
				$g=$menu['grouping'];
				if (!isset($groupings[$g]) && !isset($menu['single']) && !$menu['single']) {
					add_menu_page($zing_ws_name, $txt[$menu['group']], 'administrator', $page,'zing_ws_settings',ZING_URL.'fws/templates/default/images/menu_'.$g.'.png');
					$groupings[$g]=$page;
				} elseif (isset($menu['single']) && $menu['single']) {
					add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], 'administrator', $page, 'zing_ws_settings');
				} else {
					add_submenu_page($groupings[$g], $txt[$menu['label']], $txt[$menu['label']], 'administrator', $page, 'zing_ws_settings');
				}
			}
		}
		if ($menus[$_GET['page']] && $menus[$_GET['page']]['hide']) {
			$menu=$menus[$_GET['page']];
			add_submenu_page('zingiri-web-shop', $txt[$menu['label']], $txt[$menu['label']], 'administrator', $_GET['page'], 'zing_ws_settings');
		}
		add_submenu_page('zingiri-web-shop-templates', 'Forms settings', 'Forms settings', 'administrator', 'zingiri-apps', 'zing_apps_settings');
		add_submenu_page('zingiri-web-shop-templates', 'Forms editor', 'Forms editor', 'administrator', 'zingiri-apps-settings', 'zing_apps_editor');
	}
}

?>