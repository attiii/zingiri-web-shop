<?php

function wsShowProductRow($row) {
	global $use_prodgfx,$prods_per_row,$txt,$product_url,$product_dir,$pictureid;
	global $db_prices_including_vat,$search_prodgfx,$use_prodgfx,$stock_enabled,$gfx_dir,$hide_outofstock,$show_stock;
	global $ordering_enabled,$order_from_pricelist,$includesearch,$currency_symbol,$no_vat,$optel,$group;

	$output='';

	$optel++;
	if ($optel == 3) { $optel = 1; }
	if ($optel == 1) { $kleur = ""; }
	if ($optel == 2) { $kleur = " class=\"altrow\""; }

	// reset values
	$picturelink = "";
	$new = "";
	$thumb = "";
	$stocktext = "";

	// new product?
	if ($row[7] == 1) { $new = '<div class="wsnew">'.$txt['general3'].'</div>'; }

	// is there a picture?
	if ($search_prodgfx == 1 && $use_prodgfx == 1) {

		if ($pictureid == 1) { $picture = $row[0]; }
		else { $picture = $row[1]; }

		list($image_url,$height,$width)=wsDefaultProductImageUrl($picture,$row['DEFAULTIMAGE']);
		$thumb = "<img src=\"".$image_url."\"".$width.$height." alt=\"\" />";
	}
	// see if you are an admin. if so, add a [EDIT] link to the line
	$admin_edit = "";
	if (IsAdmin() == true) {
		if ($stock_enabled == 1) { $admin_edit .= $txt['productadmin12'].": ".$row[5]."<br />"; }
		$admin_edit = '<div class="wsadminedit">';
		if (wsIsAdminPage()) {
			$admin_edit .= "<a href=\"".zurl("?page=product&zfaces=form&form=product&action=edit&zfp=62&id=".$row[0])."\">".$txt['browse7']."</a>";
			$admin_edit .= " | <a href=\"".zurl("?page=product&zfaces=form&form=product&action=delete&zfp=62&id=".$row[0])."\" >".$txt['browse8']."</a>";
			$admin_edit .= " | ".$txt['productadmin14'].' <input id="wsfp'.$row[0].'" type="checkbox" class="wsfrontpage" onclick="wsFrontPage('.$row[0].',this.checked);"';
			if ($row['FRONTPAGE']) $admin_edit.=" checked";
			$admin_edit.='>';
		} elseif (ZING_CMS=='wp' && isadmin()) {
			$admin_edit .= "<a href=\"".get_option('siteurl')."/wp-admin/admin.php?page=product&zfaces=form&form=product&action=edit&id=".$row[0]."&redirect=".wsCurrentPageURL(true)."\">".$txt['browse7']."</a>";
			$admin_edit .= " | <a href=\"".get_option('siteurl')."/wp-admin/admin.php?page=product&zfaces=form&form=product&action=delete&id=".$row[0]."&redirect=".wsCurrentPageURL(true)."\" >".$txt['browse8']."</a>";
		}
		$admin_edit.= '</div>';
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
		//if ($hide_outofstock == 0 && $row[5] == 0 && !wsIsAdminPage()) { $row[4] = 0; }
		if (wsIsAdminPage() == FALSE && $show_stock == 1) {
			$stocktext = "<br /><small>".$txt['browse13'].": ".$row[5]."</small>";
		}
	}

	$output.= "<td>".$stockpic;
	$output.=$new;
	if (!wsIsAdminPage()) {
		$output.= '<div class="wsbrowseimage">'.$thumb.'</div>';
		$output.= "<a class=\"wsbrowsetitle\" href=\"".zurl("index.php?page=details&prod=".$row[0]."&cat=".$row[2]."&group=".$group)."\">".$print_description."</a> ";
	}
	else $output.= $thumb.$print_description;
	$output.= $picturelink." "." ".$stocktext.$admin_edit."</td>";
	if ($ordering_enabled) {
		$output.= "<td><div style=\"text-align:right;\">";
		if ($order_from_pricelist) {
			$output.= '<form id="order'.$row[0].'" method="POST" action="?page=cart&action=add" enctype="multipart/form-data">';
			$output.= '<div style="text-align: right"><input type="hidden" id="prodid" name="prodid" value="'.$row[0].'">';
			if (!$row[4] == 0 || $stock_enabled != 1) {
				$tax=new wsTax(wsPrice::price($row[4]),$row['TAXCATEGORYID']);
				if ($no_vat == 1) {
					$output.= "<big><strong>". wsPrice::currencySymbolPre().'<span class="wspricein" id="wsprice'.$row[0].'">'.$tax->inFtd.'</span>'.wsPrice::currencySymbolPost()."</strong></big>";
				}
				else {
					$output.= "<big><strong>".wsPrice::currencySymbolPre().'<span class="wspricein" id="wsprice'.$row[0].'">'.$tax->inFtd.'</span>'.wsPrice::currencySymbolPost()."</strong></big>";
					if (wsSetting('show_tax_breakdown')) $output.= "<br /><small>(".wsPrice::currencySymbolPre().'<span class="wspriceex" id="wsprice'.$row[0].'">'.$tax->exFtd.'</span>'.wsPrice::currencySymbolPost()." ".$txt['general6']." ".$txt['general5'].")</small>";
				}

				// product features
				$allfeatures = $row[8];
				$wsFeatures=new wsFeatures($allfeatures,$row['FEATURESHEADER'],$row['FEATURES_SET'],$row[0]);

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
						$output.= '<td><input type="text" size="2" name="numprod[]" value="'.intval((isset($wsFeatures->prefil[$i]['qty']) ? $wsFeatures->prefil[$i]['qty'] : 0) || !count($wsFeatures->prefil)).'" maxlength="4" /></td>';
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
		else { $output.= "<big><strong>".$currency_symbol."&nbsp;".wsPrice::format($row[4])."</strong></big>"; }
		$output.= "</div></td>";
	}
	$output.= "</tr>";

	$output.='<script type="text/javascript" language="javascript">var wsProductDisplayType=\'list\';</script>';
	
	return $output;
}

function wsShowProductCell($row,$row_count,$prods_per_row) {
	global $no_vat,$use_prodgfx,$txt,$product_url,$product_dir,$pictureid,$group;

	$screenshot = "";
	$output='';
	if ($use_prodgfx == 1) {
		if ($pictureid == 1) {
			$picture = $row[0];
		}
		else { $picture = $row[1]; }

		list($thumb,$height,$width)=wsDefaultProductImageUrl($picture,$row['DEFAULTIMAGE']);
		$size = getimagesize(str_replace($product_url,$product_dir,$thumb));
		$max_height = wsSetting('grid_thumb_height');
		$max_width = wsSetting('grid_thumb_width');
		$percent = min($max_height / $size[1], $max_width / $size[0]);
		$height = intval($size[1] * $percent);
		$width = intval($size[0] * $percent);

		$screenshot = '<div class="wsgridimage"><img src="'.$thumb.'" width="'.$width.'" height="'.$height.'" /></div>';
		$screenshot="<div style=\"height:100px\">".$screenshot."</div>";
	}
	if ($row_count == 1) { $output.="<tr>"; }
	$output.='<td width="'.(intval(100/$prods_per_row)).'%" style="text-align:center;">
			       '."<a class=\"plain\" href=\"".zurl("index.php?page=details&prod=".$row[0]."&cat=".$row[2])."\"><h5 style=\"text-align:center\">".$row[1].'</h5>'.$screenshot.'</a><br />
				   <br />';
	if ($row['FEATURES']) $output.='<form class="wsgridform" id="order'.$row[0].'" method="post" action="'.zurl('index.php?page=details&prod='.$row[0]."&cat=".$row[2]).'">';
	else $output.='<form class="wsgridform" id="order'.$row[0].'" method="post" action="'.zurl('index.php?page=cart&action=add').'">';
	$output.='<input type="hidden" name="prodid" value="'.$row[0].'">';
	if (!$row[4] == 0) {
		$tax=new wsTax(wsPrice::price($row[4]),$row['TAXCATEGORYID']);
		if ($no_vat == 1) {
			$output.="<normal>" . wsPrice::currencySymbolPre().$tax->inFtd.wsPrice::currencySymbolPost()."</normal>";
		}
		else {
			$output.="<strong>" .wsPrice::currencySymbolPre().$tax->inFtd.wsPrice::currencySymbolPost()."</strong>";
			if (wsSetting('show_tax_breakdown')) $output.="<br /><small>(".wsPrice::currencySymbolPre().$tax->exFtd.wsPrice::currencySymbolPost()." ".$txt['general6']." ".$txt['general5'].")</small>";
		}
	}

	$output.='<br /><input name="sub" ';
	if (!$row['FEATURES']) $output.='type="button" class="addtocart" ';
	else $output.='type="submit" class="gotoproductpage" ';
	$output.='id="addtocart" value="'.$txt['details7'].'" />
                   </form></td>';
	if ($row_count == $prods_per_row) { $output.="</tr>"; }
	
	$output.='<script type="text/javascript" language="javascript">var wsProductDisplayType=\'grid\';</script>';
	
	return $output;
}