<?php
if (get_option("zing_ws_baseurl") && get_option("zing_ws_accname")) {
	add_action("init","zing_init");
	//		add_filter('wp_footer','zing_footer');
	add_filter('get_pages','zing_exclude_pages');
	//add_action("plugins_loaded", "zing_sidebar_init");
	add_filter('the_content', 'zing_content', 10, 3);
	add_action('wp_head','zing_header');
	add_action('wp_head','zing_ws_header_custom',100);
	add_filter('wp_title','zing_ws_title');
	add_filter('the_title','zing_ws_page_title',10,2);
	/*
	 if ($integrator->wpCustomer) {
	 add_action('wp_login','zing_login');
	 add_action('wp_logout','zing_logout');
	 add_filter('check_password','zing_check_password',10,4);
	 add_action('profile_update','zing_profile'); //post wp update
	 add_action('user_register','zing_profile'); //post wp update
	 add_action('delete_user','zing_delete_user');
	 }
	 */
}
//	add_action("init","zing_init_uninstall");
//	add_action('admin_notices','zing_admin_notices');
add_action('admin_menu', 'zing_ws_add_admin');

function zing_ws_add_admin() {

	global $zing_ws_name, $zing_ws_shortname, $zing_ws_options, $menus, $txt, $wpdb, $integrator;
	global $dbtablesprefix;


	zing_set_options();
	if (strstr($_GET['page'],'zingiri-web-shop')) {

		if ( 'install' == $_REQUEST['action'] ) {
			update_option('zing_ws_installed',1);
			foreach ($zing_ws_options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] );
			}

			foreach ($zing_ws_options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) {
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
				} else { delete_option( $value['id'] ); }
			}
				
			zing_ws_install();
				
			header("Location: "."options-general.php?page=zingiri-web-shop&installed=true");
			die;
		}
	}
	//add_options_page('Web shop live', 'Web shop live', 'administrator', 'zing-ws-admin','zing_ws_admin');
	add_menu_page($zing_ws_name, 'Web Shop', 'administrator', 'zingiri-web-shop','zing_ws_admin',ZING_URL.'images/menu_webshop.png');
	add_submenu_page('zingiri-web-shop', $zing_ws_name.'- Configuration', 'Configuration', 'administrator', 'zingiri-web-shop', 'zing_ws_admin');
	//add_submenu_page('zingiri-web-shop', $zing_ws_name.'- Export', 'Export', 'administrator', 'zingiri-ws-export', 'zing_ws_export');

}
function zing_content($content) {
	global $remoteMsg,$showPage;

	//print_r($remoteMsg['seo']);
	if ($remoteMsg['status'] == 'loginfailed') {
		echo '<a href="'.get_option('home').'/?'.$remoteMsg['redirect'].'">'.'Back'.'</a>';
	} elseif ($remoteMsg['status'] == 'loginsuccess') {
		echo '<a href="'.get_option('home').'/?'.$remoteMsg['redirect'].'">'.'Success'.'</a>';
		header('Location: '.get_option('home').'/?'.$remoteMsg['redirect']);
		die();
	} elseif ($remoteMsg['status'] == 'logoutsuccess') {
		echo '<a href="'.get_option('home').'/?'.$remoteMsg['redirect'].'">'.'Success'.'</a>';
		header('Location:'.get_option('home'));
	} elseif ($showPage) return $remoteMsg['main'];
	else return $content;
	//print_r($remoteMsg['widgets']);
	/*
	foreach ($remoteMsg as $a => $b) {
	echo '<br />'.$a;
	}
	main
	widgets
	txt
	vars
	scripts
	css
	cookie
	*/
}
function zing_ws_install_roles() {
	global $current_user;
	get_currentuserinfo();
	$roles=new WP_Roles();
	$roles->add_role('administer_web_shop','Administer Web Shop',array('administer_web_shop'));
	$current_user->add_role('administer_web_shop');
}

function zing_ws_install() {
	if (get_option("zing_webshop_version")) {
		wp_clear_scheduled_hook('zing_ws_cron_hook');
	}
	
	if (!get_option("zing_webshop_pages")) {
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
		update_option("zing_webshop_pages",$ids);
	}
	mkdir(get_option('zing_ws_cache'));
	update_option("zing_webshop_live_version",ZING_VERSION);
	
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
	global $zing_ws_options;
	zing_set_options();
	zing_ws_uninstall_delete_pages();
	delete_option('zing_ws_installed');
	foreach ($zing_ws_options as $value) {
		delete_option( $value['id'] );
	}
	delete_option("zing_webshop_live_version");
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
	delete_option("zing_webshop_pages");
}

/**
 * Header hook: loads FWS addons and css files
 * @return unknown_type
 */
function zing_header()
{
	global $remoteMsg;

	$ret='';

	if (isset($remoteMsg['seo']['description'])) $ret.=sprintf("<meta name=\"description\" content=\"%s\" />", $remoteMsg['seo']['description']);
	if (isset($remoteMsg['seo']['keywords'])) $ret.=sprintf("<meta name=\"keywords\" content=\"%s\" />", $remoteMsg['seo']['keywords']);

	if (count($remoteMsg['vars'])>0) {
		foreach ($remoteMsg['vars'] as $v => $c) {
			if ($v == 'wsURL') $x=get_option('home').'/index.php?page=ajax&wscr=';
			else $x=$c;
			$script.="var ".$v."='".$x."';";
		}
	}


	//	$script.="var wsURL='".get_option('home')."/?q=".urlencode('webshop/ajax')."&wscr=';";
	$ret.='<script type="text/javascript">'.$script.'</script>';

	if (count($remoteMsg['scripts'])>0) {
		foreach ($remoteMsg['scripts'] as $s) {
			$ret.='<script type="text/javascript" src="' . $s . '"></script>';
		}
	}

	if (count($remoteMsg['css'])>0) {
		foreach ($remoteMsg['css'] as $c) {
			$ret.='<link type="text/css" rel="stylesheet" media="all" href="'.$c.'" />';
		}
	}
	//return $ret;
	echo $ret;
}

function zing_ws_header_custom()
{
	if (file_exists(BLOGUPLOADDIR . 'zingiri-web-shop/custom.css')) echo '<link rel="stylesheet" type="text/css" href="' . BLOGUPLOADURL . 'zingiri-web-shop/custom.css" media="screen" />';
}

/**
 * Initialization of page, action & page_id arrays
 * @return unknown_type
 */
function zing_init()
{
	global $page_content,$remoteMsg;
	global $zing_page_id_to_page, $zing_page_to_page_id, $wpdb;

	session_start();

	if (is_admin()) return;

	if (isset($_REQUEST['wscr'])) {
		ob_end_clean();

		wsConnectURL('ajax');
		echo $remoteMsg['main'];
		die();

		die();
		return;
	}

	ob_start();

	wp_enqueue_script('jquery');

	//get pages

	$zing_page_id_to_page=array();
	$zing_page_to_page_id=array();

	$sql = "SELECT post_id,meta_value FROM $wpdb->postmeta,$wpdb->posts WHERE $wpdb->postmeta.post_id=$wpdb->posts.id AND $wpdb->posts.post_type='page' AND meta_key = 'zing_page'";
	$a = $wpdb->get_results( $sql );

	foreach ($a as $i => $o )
	{
		if (!isset($zing_page_id_to_page[$o->post_id])) $zing_page_id_to_page[$o->post_id][0]=$o->meta_value;
	}
	$sql = "SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key = 'zing_action'";
	$a = $wpdb->get_results( $sql );
	foreach ($a as $i => $o )
	{
		if (!isset($zing_page_id_to_page[$o->post_id][1])) $zing_page_id_to_page[$o->post_id][1]=$o->meta_value;
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

	//end get pages

	/*
	 if (!is_admin() && empty($_GET['page'])) {
		if (isset($_POST['page'])) $_GET['page']=$_POST['page'];
		elseif (isset($_REQUEST['page_id'])) {
		$_GET['page']=zing_page($_REQUEST['page_id']);
		}
		else $_GET['page']='main';
		}
		*/
	$page_content=zing_main('content');

	/*
	 if (isset($_REQUEST['wslive'])) {
	 $_GET['page_id']=zing_page_id("main");
	 } elseif ((!isset($_GET['page_id']) || empty($_GET['page_id'])) && isset($_GET['page'])) {
	 $pageId='';
	 $pageId=zing_page_id($_GET['page']);
	 if ($pageId) $_GET['page_id']=$pageId;
	 else $_GET['page_id']=zing_page_id("main");
	 }

	 //cat is a parameter used by Wordpress for categories
	 if (isset($_GET['cat']) && isset($_GET['page'])) {
	 $_GET['kat']=$_GET['cat'];
	 unset($_GET['cat']);
	 }
	 */
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
		return $zing_page_id_to_page[$page_id][0];
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
	if ($loggedin) $isadmin=false;
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
		elseif ($security == "1" && !$loggedin==1) {
			$show=true;
		}
		elseif ($security == "2" && $loggedin==1 && $iscustomer) {
			$show=true;
		}
		elseif ($security == "3" && $loggedin==1) {
			$show=true;
		}
		elseif ($security == "4" && (!$loggedin==1 || $iscustomer)) {
			$show=true;
		}
		elseif ($security == "5" && $loggedin==1 && !$isadmin && ($isuser || $iscustomer)) {
			$show=true;
		}
		elseif ($security == "6" && ($iscustomer || $isadmin)) { //should really be shown only if something in cart
			$show=true;
		}
		elseif ($security == "9" && $loggedin==1 && $isadmin) {
			$show=true;
		}
		if (!$show || get_option("zing_ws_show_menu_".$page->ID)=="No")
		{
			unset($pages[$i]);
			$unsetpages[$page->ID]=true;
		}
	}

	return $pages;
}

function LoggedIn() {
	global $remoteMsg;
	return $remoteMsg['loggedin'];

}
/**
 * Register sidebar widgets
 * @return unknown_type
 */
//require(dirname(__FILE__).'/extensions/widgets/index.php');
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
	global $remoteMsg;
	if ($remoteMsg['seo']['title']) return $remoteMsg['seo']['title'];
	else return $title;
}

function zing_ws_page_title($pageTitle,$id=0) {
	global $remoteMsg;

	if (!in_the_loop()) return $pageTitle;

	if ($remoteMsg['title']) return ($remoteMsg['title']);
	else return $pageTitle;


	//if (!zing_ws_is_shop_page($post->ID) || $id==0 || ($id != $post->ID)) return $pageTitle;

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

//Widgets
class zing_ws_widget0 extends WP_Widget {
	function zing_ws_widget0() {
		parent::WP_Widget(false, $name = 'Zingiri Product Carousel');
	}
	function widget($args, $instance) {
		zing_ws_widget($args, 0);
	}
}
class zing_ws_widget1 extends WP_Widget {
	function zing_ws_widget1() {
		parent::WP_Widget(false, $name = 'Zingiri Web Shop Cart');
	}
	function widget($args, $instance) {
		zing_ws_widget($args, 1);
	}
}
class zing_ws_widget2 extends WP_Widget {
	function zing_ws_widget2() {
		parent::WP_Widget(false, $name = 'Zingiri Web Shop General');
	}
	function widget($args, $instance) {
		zing_ws_widget($args, 2);
	}
}
class zing_ws_widget3 extends WP_Widget {
	function zing_ws_widget3() {
		parent::WP_Widget(false, $name = 'Zingiri Web Shop Random Product');
	}
	function widget($args, $instance) {
		zing_ws_widget($args, 3);
	}
}
class zing_ws_widget4 extends WP_Widget {
	function zing_ws_widget4() {
		parent::WP_Widget(false, $name = 'Zingiri Web Shop Products');
	}
	function widget($args, $instance) {
		zing_ws_widget($args, 4);
	}
}
class zing_ws_widget5 extends WP_Widget {
	function zing_ws_widget5() {
		parent::WP_Widget(false, $name = 'Zingiri Web Shop Search');
	}
	function widget($args, $instance) {
		zing_ws_widget($args, 5);
	}
}

function zing_ws_widget($args, $i) {
	global $remoteMsg;
	extract( $args );
	echo $before_widget;
	if ($remoteMsg['status']=='maintenance') echo $remoteMsg['main'];
	else echo $remoteMsg['widgets'][$i]['content'];
	echo $after_widget;
}

add_action('widgets_init', create_function('', 'return register_widget("zing_ws_widget0");'));
add_action('widgets_init', create_function('', 'return register_widget("zing_ws_widget1");'));
add_action('widgets_init', create_function('', 'return register_widget("zing_ws_widget2");'));
add_action('widgets_init', create_function('', 'return register_widget("zing_ws_widget3");'));
add_action('widgets_init', create_function('', 'return register_widget("zing_ws_widget4");'));
add_action('widgets_init', create_function('', 'return register_widget("zing_ws_widget5");'));

?>