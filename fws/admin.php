<?php
/*  admin.php
 Copyright 2006, 2007, 2008 Elmar Wenners
 Support site: http://www.chaozz.nl

 This file is part of UltraShop.

 UltraShop is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 UltraShop is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with UltraShop; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
?>
<?php if ($index_refer <> 1) { exit(); } ?>

<?php
if (IsAdmin() == false) {
	if (defined("ZING") && current_user_can('manage_options'))
	include (ZING_SUB."./includes/checklogin.inc.php");
	else
	PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
	if (ZING_CMS=="wp" && empty($_GET['adminaction']) && wsCurrentCmsUserIsShopAdmin()) {
		header('Location:'.get_option("siteurl").'/wp-admin/admin.php?page=dashboard');
	}
	include ("includes/httpclass.inc.php"); 
	if (!empty($_GET['adminaction'])) {
		$adminaction = $_GET['adminaction'];
		if ($adminaction == "optimize_tables") {
			echo "<strong>".$txt['admin10']."</strong><br /><br />";
			//get all tables
			$alltables = mysql_query("SHOW TABLES");
			//go trough them, save as an array
			while($table = mysql_fetch_assoc($alltables)){
				//go through the array ( $db => $tablename )
				foreach ($table as $db => $tablename) {
					$sizeprefix = strlen($dbtablesprefix);
					$sizetable = strlen($tablename);
					if ($sizeprefix == 0) {
						//optimize every table
						echo $txt['admin11']." ".$tablename.".. ";
						mysql_query("OPTIMIZE TABLE `".$tablename."`") or die(mysql_error());
						echo "<strong>".$txt['admin12']."</strong><br />";
					}else{
						if ($sizetable >= $sizeprefix){
							if (substr($tablename, 0, $sizeprefix) == $dbtablesprefix){
								//optimize every table with the shop prefix tablename
								echo $txt['admin11']." ".$tablename.".. ";
								mysql_query("OPTIMIZE TABLE `".$tablename."`") or die(mysql_error());
								echo "<strong>".$txt['admin12']."</strong><br />";
							}
						}
					}
				}
			}
		}
		if (!empty($_GET['adminaction'])) {
			if ($adminaction == "export_database") {
				echo "<strong>".$txt['admin27']."</strong><br /><br />";
				//backup via shell
				$backupFile = $dbname . date("Y-m-d-H-i-s") . '.gz';
				$command = "mysqldump --opt -h $dblocation -u $dbuser -p $dbpass $dbname | gzip > $backupFile";
				system($command);
				echo $txt['admin28'];
				echo "<br /><a href=\"".$backupFile."\">$backupFile</a><br /><br />";
			}
		}
	}
	else {
			
		// the live news feed
		if ($live_news == true || $live_news == false) {
			global $current_user;
			get_currentuserinfo();
			$news = new wsNewsRequest('http://www.zingiri.com/news.php?e='.urlencode(isset($current_user->user_email) ? $current_user->user_email : $sales_mail).'&w='.urlencode(ZING_HOME).'&a='.get_option("zing_ws_install").'&v='.urlencode(ZING_VERSION));
			if ($news->live()) {
				PutWindow($gfx_dir, $txt['general13'], $news->DownloadToString(), "news.gif", "90");
			}

		}
			
		$num_below_stock = StockWarning($stock_warning_level);
		if ($stock_enabled == 1 && $use_stock_warning == 1 && $num_below_stock != 0) {
			PutWindow($gfx_dir, $txt['general13'], $txt['admin33'].$num_below_stock.$txt['admin34']."<br /><br />".$txt['editsettings100'].": ".$stock_warning_level, "warning.gif", "90");
		}

		$menuItemsPerLine=3;
		echo '<table width="80%" class="datatable">';
		echo '<tr><td><table class="borderless" width="100%">';
		$menugroup="";
		$i=0;
		foreach ($menus as $menu) {
			if (!isset($menu['hide']) || !$menu['hide']) {
				if ($menu['group'] != $menugroup) {
					echo '<tr><td colspan="'.$menuItemsPerLine.'"><h6>'.$txt[$menu['group']].'</h6></td></tr>';
					$menugroup=$menu['group'];
					$i=0;
				}
				$i++;
				if ($i > $menuItemsPerLine) {
					echo '</tr><tr>';
					$i=1;
				}
				echo '<td><div style="text-align: center;"><a class="plain" href="?'.$menu['href'].'"><img src="'.$gfx_dir.'/'.$menu['img'].'" width="32px" height="32px" alt="" /><br />'.$txt[$menu['label']];
				if (isset($menu['func'])) {
					$func=$menu['func'];
					echo " (".$func($menu['param']).")";
				}
				echo '</a><br /><br /></div></td>';
			}
		}

		echo '</tr></table></td></tr></table>';

	}
}
?>
