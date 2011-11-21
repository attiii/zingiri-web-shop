<?php if ($index_refer <> 1) { exit(); } ?>
<?php
// check for the existance of thumbs for all pictures in the shop
if (IsAdmin() == false) {
	PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
} else {
	if ($action == "generate_thumbs") {
		createallthumbs($product_dir,$pricelist_thumb_width,$pricelist_thumb_height);
		PutWindow($gfx_dir, $txt['general13'] , $txt['productadmin29'], "notify.gif", "50");
	} elseif ($action == "update_exchange_rates") {
		$mc=new wsMultiCurrency();
		$output=$mc->updateRates();
		PutWindow($gfx_dir, $txt['general13'] , $txt['mc1'].'<br />'.$output, "notify.gif", "50");
	}
	
	echo '<a href="'.zurl("?page=task").'">'.$txt['general14'].'</a>';
}
