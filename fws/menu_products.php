<?php
/*  menu_products.php
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
echo "<h1>".$txt['menu15']."</h1>\n";
// if the category is send, then use that to find out the group
if ($cat != 0 && $group == 0) { $group = TheGroup($cat); }

$query = "SELECT * FROM `".$dbtablesprefix."group` ORDER BY `NAME` ASC";
$sql = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($sql) == 0) {
	echo $txt['menu17']; // no groups found
}
else {
	echo "<ul id=\"navlist\">\n";
	while ($row = mysql_fetch_row($sql)) {
		// lets find out how many categories there are in the group
		$query_cat = sprintf("SELECT * FROM `".$dbtablesprefix."category` where `GROUPID`=%s ORDER BY `DESC` ASC", quote_smart($row[0]));
		$sql_cat = mysql_query($query_cat) or die(mysql_error());
		$ahref = "";

		// if there is only 1 category in the group, then jump to the browse list instandly
		if (mysql_num_rows($sql_cat) == 1) {
			$row_cat = mysql_fetch_row($sql_cat);
			$ahref = "\"index.php?page=browse&action=list&orderby=DESCRIPTION&group=".$row[0]."&cat=".$row_cat[0]."\"";
			if ($group != $row[0]) {
				echo "<li><a href=".$ahref.">" . $row[1] . "</a></li>\n";
			}
			else {
				//select/highlight
				echo "<li id=\"active\"><a id=\"current\" href=".$ahref.">" . $row[1] . "</a></li>\n";
			}
		}
		// if there are more categories in the group, then show the category list
		if (mysql_num_rows($sql_cat) > 1) {
			$ahref = "\"index.php?page=browse&action=list&orderby=DESCRIPTION&group=".$row[0]."\"";
			echo "<li>".$row[1];
			echo '<ul>';
			while ($row_cat = mysql_fetch_row($sql_cat)) {
				if ($cat==$row_cat[0]) $active='id="active"'; else $active="";
				$ahref = "\"index.php?page=browse&action=list&orderby=DESCRIPTION&group=".$row[0]."&cat=".$row_cat[0]."\"";
				echo "<li ".$active."><a href=".$ahref.">" . $row_cat[1] . "</a>";
			}
			echo '</ul>';
			echo '</li>';
		}
	}
	echo "</ul>\n";
}
?>