<?php
/*  frontpage.php
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
$screenshot = "";
if ($use_prodgfx == 1) {
	if ($pictureid == 1) {
		$picture = $f_row[0];
	}
	else { $picture = $f_row[1]; }

	$thumb = "";
	if (!empty($f_row['DEFAULTIMAGE']) && thumb_exists($product_dir ."/". $f_row['DEFAULTIMAGE'])) { $thumb = $product_url."/".$f_row['DEFAULTIMAGE']; }
	elseif (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = $product_url ."/". $picture . ".jpg"; }
	elseif (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = $product_url ."/". $picture . ".gif"; }
	elseif (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = $product_url ."/". $picture . ".png"; }

	if ($thumb == "") {
		$thumb = $gfx_dir."/nothumb.jpg";
		$screenshot = "<img src=\"".$thumb."\" width=\"100\" height=\"100\" />";
	} else {
		$size = getimagesize(str_replace($product_url,$product_dir,$thumb));
		$max_height = 100;
		$max_width = 100;
		$percent = min($max_height / $size[1], $max_width / $size[0]);
		$height = intval($size[1] * $percent);
		$width = intval($size[0] * $percent);
		$screenshot = "<img src=\"".$thumb."\" width=\"".$width."\" height=\"".$height."\" />";
	}
}
if ($row_count == 1) { echo "<tr>"; }
echo '<td width="33%">
			       <h5>'.$f_row[1].'</h5>'."<a class=\"plain\" href=\"index.php?page=details&prod=".$f_row[0]."&cat=".$f_row[2]."\">".$screenshot.'</a><br />
				   <br />
                  <form id="order'.$f_row[0].'" method="post" action="?page=cart&action=add">
                       <input type="hidden" name="prodid" value="'.$f_row[0].'">';
if (!$f_row[4] == 0) {
	$tax=new wsTax($f_row[4]);
	if ($no_vat == 1) {
		echo "<normal\>" . $txt['details5'] . ": ". $currency_symbol_pre.$tax->inFtd.$currency_symbol_post."</normal>";
	}
	else {
		echo "<strong>" . $txt['details5'] . ": ".$currency_symbol_pre.$tax->inFtd.$currency_symbol_post."</strong>";
		echo "<br /><small>(".$currency_symbol_pre.$tax->exFtd.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>";
	}
}

echo '<br /><input name="sub" ';
if (ZING_PROTOTYPE || ZING_JQUERY) echo 'type="button"';
else echo 'type="submit"';
echo ' class="addtocart" id="addtocart" value="'.$txt['details7'].'" />
                   </form></td>';
if ($row_count == $prods_per_row) { echo "</tr>"; }
?>