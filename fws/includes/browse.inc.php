<?php

function wsShowProductRow($row) {
	global $use_prodgfx,$prods_per_row,$currency_symbol_pre,$currency_symbol_post,$txt,$product_url,$product_dir,$pictureid;
	global $db_prices_including_vat,$search_prodgfx,$use_prodgfx,$stock_enabled,$gfx_dir,$hide_outofstock,$show_stock;
	global $ordering_enabled,$order_from_pricelist,$includesearch,$currency_symbol,$no_vat,$optel;

	$output='';

	$optel++;
	if ($optel == 3) { $optel = 1; }
	if ($optel == 1) { $kleur = ""; }
	if ($optel == 2) { $kleur = " class=\"altrow\""; }

	// the price gets calculated here
	$printprijs = $row[4]; // from the database
	//if ($db_prices_including_vat == 0 && $no_vat == 0) { $printprijs = $row[4] * $vat; }
	$printprijs = myNumberFormat($printprijs); // format to our settings

	// reset values
	$picturelink = "";
	$new = "";
	$thumb = "";
	$stocktext = "";

	// new product?
	if ($row[7] == 1) { $new = "<font color=\"red\"><strong>" . $txt['general3']. "</strong></font>"; }

	// is there a picture?
	if ($search_prodgfx == 1 && $use_prodgfx == 1) {

		if ($pictureid == 1) { $picture = $row[0]; }
		else { $picture = $row[1]; }

		list($image_url,$height,$width)=wsDefaultProductImageUrl($picture,$row['DEFAULTIMAGE']);
		$thumb = "<img class=\"imgleft\" src=\"".$image_url."\"".$width.$height." alt=\"\" />";
	}
	// see if you are an admin. if so, add a [EDIT] link to the line
	$admin_edit = "";
	if (IsAdmin() == true) {
		$admin_edit = "<br /><br />";
		if ($stock_enabled == 1) { $admin_edit .= $txt['productadmin12'].": ".$row[5]."<br />"; }
		if (wsIsAdminPage()) {
			$admin_edit .= "<a href=\"".zurl("?page=product&zfaces=form&form=product&action=edit&id=".$row[0]."&redirect=".wsCurrentPageURL(true))."\">".$txt['browse7']."</a>";
			$admin_edit .= " | <a href=\"".zurl("?page=product&zfaces=form&form=product&action=delete&id=".$row[0]."&redirect=".wsCurrentPageURL(true))."\" >".$txt['browse8']."</a>";
			$admin_edit .= " | ".$txt['productadmin14'].' <input id="wsfp'.$row[0].'" type="checkbox" class="wsfrontpage" onclick="wsFrontPage('.$row[0].',this.checked);"';
			if ($row['FRONTPAGE']) $admin_edit.=" checked";
			$admin_edit.='>';
		} elseif (ZING_CMS=='wp' && isadmin()) {
			$admin_edit .= "<a href=\"".get_option('siteurl')."/wp-admin/admin.php?page=product&zfaces=form&form=product&action=edit&id=".$row[0]."&redirect=".wsCurrentPageURL(true)."\">".$txt['browse7']."</a>";
			$admin_edit .= " | <a href=\"".get_option('siteurl')."/wp-admin/admin.php?page=product&zfaces=form&form=product&action=delete&id=".$row[0]."&redirect=".wsCurrentPageURL(true)."\" >".$txt['browse8']."</a>";
		}
	}
	// make up the description to print according to the pricelist_format and max_description
	$print_description=printDescription($row[1],$row[3],$row['EXCERPT']);

	$output.= "<tr".$kleur.">";

	// see what the stock is
	if ($stock_enabled == 0) {
		if ($row[5] == 1) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_stock1']."\" /> "; } // in stock
		if ($row[5] == 0) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_stock2']."\" /> "; } // out of stock
		if ($row[5] == 2) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_stock3']."\" /> "; } // in backorder
	}
	else {
		$stockpic = "";
		if ($hide_outofstock == 0 && $row[5] == 0 && !wsIsAdminPage()) { $row[4] = 0; }
		if (wsIsAdminPage() == FALSE && $show_stock == 1) {
			$stocktext = "<br /><small>".$txt['browse13'].": ".$row[5]."</small>";
		}
	}

	$output.= "<td>".$stockpic;
	if (!wsIsAdminPage()) $output.= "<a class=\"plain\" href=\"".zurl("index.php?page=details&prod=".$row[0]."&cat=".$row[2]."&group=".$group)."\">".$thumb.$print_description."</a> ";
	else $output.= $thumb.$print_description;
	$output.= $picturelink." ".$new." ".$stocktext.$admin_edit."</td>";
	if ($ordering_enabled) {
		$output.= "<td><div style=\"text-align:right;\">";
		if ($order_from_pricelist) {
			$output.= '<form id="order'.$row[0].'" method="POST" action="?page=cart&action=add" enctype="multipart/form-data">';
			$output.= '<div style="text-align: right"><input type="hidden" id="prodid" name="prodid" value="'.$row[0].'">';
			$output.= '<input type="hidden" name="prodprice" value="'.$row[4].'">';
			if (!$row[4] == 0) {
				$tax=new wsTax($row[4]);
				if ($no_vat == 1) {
					$output.= "<big><strong>". $currency_symbol_pre.'<span class="wspricein" id="wsprice'.$row[0].'">'.$tax->inFtd.'</span>'.$currency_symbol_post."</strong></big>";
				}
				else {
					$output.= "<big><strong>".$currency_symbol_pre.'<span class="wspricein" id="wsprice'.$row[0].'">'.$tax->inFtd.'</span>'.$currency_symbol_post."</strong></big>";
					$output.= "<br /><small>(".$currency_symbol_pre.'<span class="wspriceex" id="wsprice'.$row[0].'">'.$tax->exFtd.'</span>'.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>";
				}

				// product features
				$allfeatures = $row[8];
				$wsFeatures=new wsFeatures($allfeatures,$row['FEATURESHEADER'],$row[0]);

				$output.= '<input type="hidden" name="featuresets" value="'.$wsFeatures->sets.'" />';
				if (count($wsFeatures->prefil)>0) {
					for ($i=0;$i<$wsFeatures->sets;$i++) {
						$output.= '<input type="hidden" name="basketid[]" value="'.$wsFeatures->prefil[$i]['id'].'" />';
					}
				}
				$output.= '<div style="clear:both"></div>';
				$output.= '<div class="wsfeatures">';
				$output.= '<table class="'.$wsFeatures->tableClass.'">';
				$output.=$wsFeatures->displayFeatures(false);

				if (!$row['LINK']) {
					$output.= '<tr>';
					$output.= '<td>'.$txt['details6'].':</td>';
					for ($i=0;$i<$wsFeatures->sets;$i++) {
						$output.= '<td><input type="text" size="2" name="numprod[]" value="'.intval($wsFeatures->prefil[$i]['qty'] || !count($wsFeatures->prefil)).'" maxlength="4" /></td>';
					}
					$output.= '</tr>';
				}
				$output.= '</table>';
				$output.= '</div>';
				$output.= '<div style="clear:both"></div>';
			}
			if (!$includesearch) {
				if (wsSetting('wishlistactive')) $output.= '<input type="button" class="addtowishlist" id="addtowishlist" value="'.$txt['wishlist2'].'" name="sub">';
				$output.= '<input type="';
				if (ZING_JQUERY) $output.= 'button'; else $output.= 'submit';
				$output.='" class="addtocart" id="addtocart" value="'.$txt['details7'].'" name="sub"> ';
			}
			if ($row[4] == 0) {
				if ($row[5] == 0 && $hide_outofstock == 0 && $stock_enabled != 2) { $output.= '<strong><big>'.$txt['browse12'].'</big></strong>'; }
			}
			$output.= '</form>';
		}
		else { $output.= "<big><strong>".$currency_symbol."&nbsp;".$printprijs."</strong></big>"; }
		$output.= "</div></td>";
	}
	$output.= "</tr>";

	return $output;
}

function wsShowProductCell($row,$row_count,$prods_per_row) {
	global $use_prodgfx,$currency_symbol_pre,$currency_symbol_post,$txt,$product_url,$product_dir,$pictureid;

	$screenshot = "";
	$output='';
	if ($use_prodgfx == 1) {
		if ($pictureid == 1) {
			$picture = $row[0];
		}
		else { $picture = $row[1]; }

		list($thumb,$height,$width)=wsDefaultProductImageUrl($picture,$row['DEFAULTIMAGE']);
		$size = getimagesize(str_replace($product_url,$product_dir,$thumb));
		$max_height = 100;
		$max_width = 100;
		$percent = min($max_height / $size[1], $max_width / $size[0]);
		$height = intval($size[1] * $percent);
		$width = intval($size[0] * $percent);

		$screenshot = "<img src=\"".$thumb."\" width=\"".$width."\" height=\"".$height."\" />";
		$screenshot="<div style=\"height:100px\">".$screenshot."</div>";
	}
	if ($row_count == 1) { $output.="<tr>"; }
	$output.='<td width="'.(intval(100/$prods_per_row)).'%" style="text-align:center;">
			       '."<a class=\"plain\" href=\"".zurl("index.php?page=details&prod=".$row[0]."&cat=".$row[2])."\"><h5 style=\"text-align:center\">".$row[1].'</h5>'.$screenshot.'</a><br />
				   <br />
                  <form id="order'.$row[0].'" method="post" action="?page=cart&action=add">
                       <input type="hidden" name="prodid" value="'.$row[0].'">';
	if (!$row[4] == 0) {
		$tax=new wsTax($row[4]);
		if ($no_vat == 1) {
			$output.="<normal>" . $txt['details5'] . ": ". $currency_symbol_pre.$tax->inFtd.$currency_symbol_post."</normal>";
		}
		else {
			$output.="<strong>" . $txt['details5'] . ": ".$currency_symbol_pre.$tax->inFtd.$currency_symbol_post."</strong>";
			$output.="<br /><small>(".$currency_symbol_pre.$tax->exFtd.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>";
		}
	}

	$output.='<br /><input name="sub" ';
	if (ZING_JQUERY) $output.='type="button"';
	else $output.='type="submit"';
	$output.=' class="addtocart" id="addtocart" value="'.$txt['details7'].'" />
                   </form></td>';
	if ($row_count == $prods_per_row) { $output.="</tr>"; }
	return $output;
}