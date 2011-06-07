<?php
/* discountadmin.php
 Copyright 2006, 2007, 2008 Elmar Wenners
 Support site: http://www.chaozz.nl

 This file is part of FreeWebshop.org.

 FreeWebshop.org is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FreeWebshop.org is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
// admin check
if (IsAdmin() == false) {
	PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
	if ($action == "add_discount") {
		$number = $_POST['number'];
		for ($i = 1; $i <= $number; $i++) {
			$code = CreateRandomCode(15);
			if (CheckBox($_POST['percentage'])) $percentage = $_POST['amount'];
			else $amount = $_POST['amount'];
			$createdate = Date($date_format);
			$discount_query="INSERT INTO `".$dbtablesprefix."discount` (`code`, `orderid`, `amount`, `percentage`, `createdate`, `expiryqty`) VALUES ('".$code."', '0', '".$amount."', '".$percentage."', '".$createdate."',1)";
			$discount_sql = mysql_query($discount_query) or die(mysql_error());

		}
	}
	if ($action == "delete_all") {
		$db=new db();
		$discount_query="DELETE FROM `##discount` WHERE `orderid` = 0 AND `createdate` <> '' AND `code` NOT IN (select distinct(`discountcode`) from `##order` where `discountcode` is not null)";
		$db->update($discount_query);
	}
	echo '<table width="100%" class="borderless">
				<tr><td>
					<form method="POST" action="'.zurl('index.php?zfaces=list&form=discount'.(wsIsAdminPage() ? '&page=discountadmin' : '')).'">
						<input type="hidden" name="action" value="add_discount">
						'.$txt['discountadmin2'].' <input type="text" name="number" value="10" size="4" maxlength="4"><br />
						'.$txt['discountadmin3'].' <input type="text" name="amount" value="" size="7" maxlength="7"><br />
						'.$txt['discountadmin4'].' <input type="checkbox" name="percentage"><br />
						<input type="submit" value="'.$txt['discountadmin5'].'">
					</form>
					</td>
					<td>
					<form method="POST" action="'.zurl('index.php?zfaces=list&form=discount'.(wsIsAdminPage() ? '&page=discountadmin' : '')).'">
						<input type="hidden" name="action" value="delete_all">
						<input type="submit" value="'.$txt['generic1'].' '.strtolower($txt['discountadmin6']).'">
					</form>
				</td></tr>	
			</table>
			<br /><br />';		
}
?>