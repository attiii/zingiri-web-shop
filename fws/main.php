<?php
/*  main.php
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
// if the shop is disabled, the admin can still do everything. let's make sure he/she does not forget it's disabled
if ($shop_disabled == 1 && IsAdmin() == true) {
	PutWindow($gfx_dir, $shop_disabled_title,"<font color=red><strong>".$txt['general8']."</strong></font>","warning.gif", "50");
	echo "<br /><br />";
}
?>
<table width="100%" class="datatable">
	<caption><?php
	echo $txt['main1']." "; PrintUsername($txt['header3']);
	if (IsAdmin() == true) { echo "[<a href=\"".zurl('?page=adminedit&filename=main.txt&root=0')."\">".$txt['browse7']."</a>]"; }
	?></caption>
	<tr>
		<td><?php
		$main=$zingPrompts->get('main');
		$main=str_replace("templates/default/images",$gfx_dir,$main);
		echo "<p>".nl2br($main)."</p>";
		?></td>
	</tr>
</table>
<br />

		<?php
		// Are there any special offers (frontpage=1 in product details)?
		if ($prods_per_row = wsSetting('productsperrow')); else $prods_per_row=3;
		$row_count = 0;
		$f_query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `FRONTPAGE` = '1'";
		$f_sql = mysql_query($f_query) or die(mysql_error());
		if (mysql_num_rows($f_sql) != 0) {
			if (mysql_num_rows($f_sql) < $prods_per_row) { $prods_per_row = mysql_num_rows($f_sql); }
			echo "<div style=\"text-align:center;\">";
			echo "<h2>".$txt['main2']."</h2>";
			echo "<br />";
			echo '<table width="100%" class="borderless" style="width:100%">';

			while ($f_row = mysql_fetch_array($f_sql)) {
				$row_count++;
				include ("frontpage.php");
				if ($row_count == $prods_per_row) { $row_count = 0; }
			}
			echo "</table></div>";
		}
		if (ZING_PROTOTYPE) {
		?>
<script type="text/javascript" language="javascript">
//<![CDATA[
document.observe("dom:loaded", function() {
    wsFrontPage=true;
	cart=new wsCart();
	cart.order();
});
//]]>
</script>
<?php } elseif (ZING_JQUERY) {?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	jQuery(document).ready(function() {
	    wsFrontPage=true;
		cart=new wsCart();
		cart.order();
	});
//]]>
</script>
<?php }?>