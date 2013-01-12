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
	if (IsAdmin() == true) { echo "[<a href=\"".zurladmin('?page=prompts&zfaces=list&form=prompt&element_4_1=main')."\">".$txt['browse7']."</a>]"; }
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
		if ($hide_outofstock == 1) { // filter out products with stock lower than 1
			$f_query.= " AND `STOCK` > 0";
		}
		$f_sql = mysql_query($f_query) or die(mysql_error());
		if (mysql_num_rows($f_sql) != 0) {
			if (mysql_num_rows($f_sql) < $prods_per_row) { $prods_per_row = mysql_num_rows($f_sql); }
			if (class_exists('wsMultiCurrency')) {
				$mc=new wsMultiCurrency();
				$mc->currencySelector();
			}
			echo "<div style=\"text-align:center;\">";
			echo "<h2>".$txt['main2']."</h2>";
			echo "<br />";
			echo '<table width="100%" class="borderless" style="width:100%">';

			while ($f_row = mysql_fetch_array($f_sql)) {
				$row_count++;
				echo wsShowProductCell($f_row,$row_count,$prods_per_row);
				if ($row_count == $prods_per_row) { $row_count = 0; }
			}
			echo "</table></div>";
		}
		?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	jQuery(document).ready(function() {
	    wsFrontPage=true;
		wsCart.order();
	});
//]]>
</script>