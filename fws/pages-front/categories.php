<?php
/*  submenu.php
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

<table width="80%" class="datatable">
	<tr>
		<td>
		<div style="text-align: center;"><?php
		$query_cat = sprintf("SELECT * FROM `".$dbtablesprefix."category` where `GROUPID`=%s ORDER BY `SORTORDER`,`DESC` ASC", quote_smart($group));
		$sql_cat = mysql_query($query_cat) or die(mysql_error());
		$counter = 0;
		if (mysql_num_rows($sql_cat) == 0) {
			echo $txt['submenu2'];
		}
		else {
			echo "<table width=\"100%\" class=\"borderless\">"; // a table within a cell
			while ($row_cat = mysql_fetch_array($sql_cat)) {

				// count number of products in this category
				if ($stock_enabled == 1 && IsAdmin() == false) { // filter out products with stock lower than 1
					$query_count = "SELECT `ID` FROM `".$dbtablesprefix."product` WHERE `STOCK` > 0 AND `CATID`=".$row_cat[0];
				}
				else { $query_count = "SELECT `ID` FROM `".$dbtablesprefix."product` WHERE `CATID`=".$row_cat[0]; }

				$sql_count = mysql_query($query_count) or die(mysql_error());
				$prod_num = mysql_num_rows($sql_count);
				 
				$counter = $counter +1;
				if ($counter == 1) { echo "<tr><td width=\"33%\" valign=\"top\"><div style=\"text-align:center;\">"; }
				else { echo "<td width=\"33%\" valign=\"top\"><div style=\"text-align:center;\">"; }

				if (file_exists($brands_dir."/" . $row_cat['IMAGE'])) { $thumb = $brands_url."/".$row_cat['IMAGE']; }
				elseif (file_exists(ZING_UPLOADS_DIR . $row_cat['IMAGE'])) { $thumb = BLOGUPLOADURL."zingiri-web-shop/".$row_cat['IMAGE']; }
				else $thumb = $gfx_dir."/cat.gif";

				// determine resize of thumbs
				$size = getimagesize(str_replace($brands_url,$brands_dir,$thumb));
				$height = $size[1];
				$width = $size[0];
				$percenth=$percentw=1;				
				if ($category_thumb_height > 0 && $height > $category_thumb_height)
				{
					$percenth = ($size[1] / $category_thumb_height);
				}
				if ($category_thumb_width && $width > $category_thumb_width)
				{
					$percentw = ($size[0] / $category_thumb_width);
				}
				if ($percentw > $percenth) $percent=$percentw;
				else $percent=$percenth;
				$height = round(($size[1] / $percent));
				$width = round(($size[0] / $percent));
				echo "<a style=\"text-align:center\" href=\"".zurl("index.php?page=browse&action=list&group=" . $group . "&cat=" . $row_cat[0]) . "\">";
				if ($category_thumb_height) echo '<div style="min-height:'.($category_thumb_height+10).'px">';
				echo '<img src="'.$thumb.'" height="'.$height.'" width="'.$width.'" />';
				if ($category_thumb_height) echo '</div>';
				echo "<br />".$row_cat[1]." (".$prod_num.")</a>";
				if ($counter == 3) {
					echo "<br /><br /></div></td></tr>";
					$counter = 0;
				}
				else { echo "<br /><br /></div></td>"; }

			}

			echo "</table>";
		}
		?>
		
		</td>
	</tr>
</table>
