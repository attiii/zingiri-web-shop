<?php 
if (!function_exists('zing_support_us')) {
	function zing_support_us($plugin,$action='check') {
		$option=$plugin.'-support-us';
		if ($action == 'activate' || get_option($option) == '') {
			update_option($option,time());
		} elseif (isset($_REQUEST['support-us']) && ($_REQUEST['support-us'] == 'hide')) {
			update_option($option,time()+7776000);
		} elseif ($action == 'check') {
			if ((time() - get_option($option)) > 1209600) { //14 days 
				return "<div id='zing-warning' style='background-color:red;color:white;font-size:large;margin:20px;padding:10px;'>Looks like you've been using this plugin for quite a while now. Have you thought about showing your appreciation through a small donation?<br /><br /><a href='http://www.zingiri.com/donations'><img src='https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif' /></a><br /><br />If you already made a donation, you can <a href='?page=".$plugin."&support-us=hide'>hide</a> this message.</div>";
			}
		}
	}
}
?>
<div class="updated" style="text-align:center;margin-bottom:20px;">
	<h3>Documentation & Support</h3>
	Our <a href="http://wiki.zingiri.com" target="_blank">wiki</a> provides ample documentation to help you set up your shop. If you encounter any issues, you will most likely find the answer by searching or posting on our <a href="http://forums.zingiri.com" target="_blank">forums</a>. And if you're really stuck, you can always ask your question via our <a href="http://www.clientcentral.info/submitticket.php" target="_blank">support desk</a>. Finally, if you need professional support, you can sign up for one of our <a href="http://www.zingiri.com/pricing" target="_blank">support packages</a>. 
</div>

<div class="updated" style="text-align:center;">
	<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
	<h3>Support Us</h3>
	<p>If you like this plugin, please share it with your friends</p>
	<div style="align:center;margin-bottom:15px;text-align:center">
		<a style="margin-bottom:15px" href="http://www.twitter.com/zingiri"><img align="middle" src="http://twitter-badges.s3.amazonaws.com/follow_us-a.png" alt="Follow Zingiri on Twitter"/></a>
	</div>
	<div style="margin-bottom:15px;text-align:center">
		<fb:share-button href="http://www.zingiri.com" type="button" >
	</div>
	<p>And rate our plugin on Wordpress</p>
	<a href="http://wordpress.org/extend/plugins/zingiri-web-shop" alt="Rate our plugin"><img height="35px" src="<?php echo $gfx_dir;?>/stars.png"><img height="35px" src="<?php echo $gfx_dir;?>/stars.png"><img height="35px" src="<?php echo $gfx_dir;?>/stars.png"><img height="35px" src="<?php echo $gfx_dir;?>/stars.png"><img height="35px" src="<?php echo $gfx_dir;?>/stars.png"></img></a>
<?php 
if (!defined('WP_ZINGIRI_LIVE')) echo zing_support_us('zingiri-web-shop');
?>
</div>
<div class="updated" style="text-align:center;margin-top:20px;padding-bottom:10px;">
<h3>Discover our Themes</h3>
<p>Our themes have been developed in collaboration with renown design companies and will give you a headstart when building your shop. Have a look at our <a target="_blank" href="http://webshop.zingiri.com">demo site</a> to see them in action.</p> 
<a href="http://www.clientcentral.info/cart.php?gid=17" target="_blank"><img src="<?php echo $gfx_dir;?>/buy_now.png" /></a>
<p>Our themes start at <strong style="color:green">$24.95</strong>.</p>
</div>
<?php 
	//news
	echo '<div class="updated" style="text-align:center;margin-top:20px;margin-bottom:20px;">';
	global $current_user;
	get_currentuserinfo();
	$query="SELECT count(*) as oc FROM ".$dbtablesprefix."order";
	$sql = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($sql);
	$oc=$row['oc'];

	require(dirname(__FILE__).'/fws/includes/httpclass.inc.php');
	$news = new wsNewsRequest('http://www.zingiri.com/news.php?e='.urlencode(isset($current_user->user_email) ? $current_user->user_email : $sales_mail).'&w='.urlencode(ZING_HOME).'&a='.get_option("zing_ws_install").'&v='.urlencode(ZING_VERSION).'&oc='.(string)$oc);
	if ($news->live() && !$_SESSION['zing_session']['news']) {
		if (ZING_CMS=='jl') update_option('zing_ws_news',urlencode($news->DownloadToString()));
		else update_option('zing_ws_news',$news->DownloadToString());
		$_SESSION['zing_session']['news']=true;
	}
	echo '<h3>Latest news</h3>';
	if (ZING_CMS=='jl') echo urldecode(get_option('zing_ws_news'));
	else echo get_option('zing_ws_news');
	echo '</div>';
?>
<div style="text-align:center;margin-top:20px;">
	<a href="http://www.zingiri.com" target="_blank"><img src="http://www.zingiri.com/logo.png" /></a>
</div>
