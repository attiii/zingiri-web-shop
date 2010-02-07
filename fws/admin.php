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
	if (defined("ZING") &&	current_user_can('manage_options'))
	include (ZING_SUB."./includes/checklogin.inc.php");
	else
	PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
	?>
	<?php include ("includes/httpclass.inc.php"); ?>
	<?php
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
			$news = new HTTPRequest('http://www.zingiri.com/news.php?e='.$current_user->user_email.'&w='.ZING_HOME.'&a='.get_option("zing_ws_install"));
			if ($news->live()) {
				PutWindow($gfx_dir, $txt['general13'], $news->DownloadToString(), "news.gif", "90");
			}

		}
			
		$num_below_stock = StockWarning($stock_warning_level);
		if ($stock_enabled == 1 && $use_stock_warning == 1 && $num_below_stock != 0) {
			PutWindow($gfx_dir, $txt['general13'], $txt['admin33'].$num_below_stock.$txt['admin34']."<br /><br />".$txt['editsettings100'].": ".$stock_warning_level, "warning.gif", "90");
		}

		?>
<table width="80%" class="datatable">
	<caption><?php echo $txt['admin1']; ?></caption>
	<tr>
		<td>

		<table class="borderless" width="100%">
			<tr>
				<td colspan="3">
				<h6><?php echo $txt['admin23']; ?></h6>
				</td>
			</tr>
			<tr>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=orderadmin"><img
					src="<?php echo $gfx_dir; ?>/orders.gif" alt="" /><br />
					<?php echo $txt['admin2']." (".CountAllOrders().")"; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=customeradmin&action=showcustomers"><img
					src="<?php echo $gfx_dir; ?>/customers.gif" alt="" /><br />
					<?php echo $txt['admin3']." (".CountCustomers('CUSTOMER').")"; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=customeradmin&action=showadmins"><img
					src="<?php echo $gfx_dir; ?>/admins.gif" alt="" /><br />
					<?php echo $txt['admin29']." (".CountCustomers('ADMIN').")"; ?></a><br />
				<br />
				</div>
				</td>
			</tr>
			<tr>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=productadmin&action=add_product"><img
					src="<?php echo $gfx_dir; ?>/products.gif" alt="" /><br />
					<?php echo $txt['admin5']." (".CountProducts().")"; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=groupadmin"><img
					src="<?php echo $gfx_dir; ?>/groups.gif" alt="" /><br />
					<?php echo $txt['admin6']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=paymentadmin"><img
					src="<?php echo $gfx_dir; ?>/paymentadmin.gif" alt="" /><br />
					<?php echo $txt['admin21']; ?></a><br />
				<br />
				</div>
				</td>
			</tr>
			<tr>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=shippingadmin"><img
					src="<?php echo $gfx_dir; ?>/shippingadmin.gif" alt="" /><br />
					<?php echo $txt['admin18']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=uploadadmin"><img
					src="<?php echo $gfx_dir; ?>/uploadlist.gif" alt="" /><br />
					<?php echo $txt['admin9']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=editsettings"><img
					src="<?php echo $gfx_dir; ?>/settings.gif" alt="" /><br />
					<?php echo $txt['admin8']; ?></a><br />
				<br />
				</div>
				</td>
			</tr>
			<tr>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=discountadmin"><img
					src="<?php echo $gfx_dir; ?>/discount.gif" alt="" /><br />
					<?php echo $txt['admin38']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?zfaces=list&form=taxes"><img
					src="<?php echo $gfx_dir; ?>/taxes.png" alt="" height="36px" /><br />
					<?php echo $txt['admin100']; ?></a><br />
				<br />
				</div>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3">
				<h6><?php echo $txt['admin24']; ?></h6>
				</td>
			</tr>
			<tr>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=adminedit&filename=conditions.txt&root=0&wysiwyg=0"><img
					src="<?php echo $gfx_dir; ?>/conditionsadmin.gif" alt="" /><br />
					<?php echo $txt['admin15']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=adminedit&filename=banned.txt&root=1&wysiwyg=0"><img
					src="<?php echo $gfx_dir; ?>/banned.gif" alt="" /><br />
					<?php echo $txt['admin19']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=adminedit&filename=main.txt&root=0"><img
					src="<?php echo $gfx_dir; ?>/mainadmin.gif" alt="" /><br />
					<?php echo $txt['admin22']; ?></a><br />
				<br />
				</div>
				</td>
			</tr>
			<tr>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=adminedit&filename=countries.txt&root=1&wysiwyg=0"><img
					src="<?php echo $gfx_dir; ?>/countries.gif" alt="" /><br />
					<?php echo $txt['admin37']; ?></a><br />
				<br />
				</div>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3">
				<h6><?php echo $txt['admin25']; ?></h6>
				</td>
			</tr>
			<tr>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=errorlogadmin"><img
					src="<?php echo $gfx_dir; ?>/errorlog.gif" alt="" /><br />
					<?php echo $txt['admin26']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=accesslogadmin"><img
					src="<?php echo $gfx_dir; ?>/accesslog.gif" alt="" /><br />
					<?php echo $txt['admin31']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=stockadmin"><img
					src="<?php echo $gfx_dir; ?>/stockadmin.gif" alt="" /><br />
					<?php echo $txt['admin32']; ?></a><br />
				<br />
				</div>
				</td>
			</tr>
			<tr>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=admin&adminaction=optimize_tables"><img
					src="<?php echo $gfx_dir; ?>/optimize.gif" alt="" /><br />
					<?php echo $txt['admin7']; ?></a><br />
				<br />
				</div>
				</td>
				<td>
				<div style="text-align: center;"><a class="plain"
					href="?page=mailinglist"><img
					src="<?php echo $gfx_dir; ?>/mailinglist.gif" alt="" /><br />
					<?php echo $txt['admin35']; ?></a><br />
				<br />
				</div>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	
	</tr>
</table>
<br />
<br />

					<?php
					// show the live news feed
	}
}
?>
