<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$features=array();

$wsFeatures=new wsFeatures();
$wsFeatures->setFeaturesFromBasketId(isset($_GET['basketid']) ? intval($_GET['basketid']) : 0);

// read product details
$query = sprintf("SELECT * FROM `".$dbtablesprefix."product` where `ID`=%s", quote_smart($prod));
$sql = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($sql) == 0) {
	PutWindow($gfx_dir, $txt['general12'], $txt['general9'], "warning.gif", "50");
}
else {
	if ($row = mysql_fetch_array($sql)) {

		$similar=similarProducts($row['PRODUCTID'],$row['CATID']);

		$screenshot = "";
			
		if ($use_prodgfx == 1) {
			if ($pictureid == 1) {
				$picture = $row[0];
			}
			else { $picture = $row[1]; }

			list($thumb,$height,$width,$resized)=wsDefaultProductImageUrl($picture,str_replace('tn_','',$row['DEFAULTIMAGE']),false);

			if ($resized == 0) {
				$screenshot = "<div id=\"productcenterimage\" style=\"text-align:center;height:".$product_max_height."px\"><img id=\"highlight_image\" class=\"borderimg\" src=\"".$thumb."\" ".$height." ".$width." alt=\"\" /></div>";
			}
			else {
				if ($use_imagepopup == 0) {
					$screenshot = "<a id=\"highlight_ref\" href=\"".$thumb."\"><div style=\"text-align:center;height:".$product_max_height."px\"><img class=\"borderimg\" id=\"highlight_image\" src=\"".$thumb."\" ".$height." ".$width." alt=\"\"/></div>"."</a>";
				}
				else {
					$screenshot = "<a id=\"highlight_ref\" href=\"".$thumb."\"><div style=\"text-align:center;height:".$product_max_height."px\"><img class=\"borderimg\" id=\"highlight_image\" src=\"".$thumb."\" ".$height." ".$width." alt=\"\"/></div>"."</a>";
				}
			}

		}

		$orderAction=zurl("?page=cart&action=add");

		//other images
		$imagesCount=0;
		$imagesMarkUp='';
		$picid=$row['ID'];
		$imgs=array();
		if ($handle=opendir($product_dir)) {
			while (($img = readdir($handle))!==false) {
				if (strstr($img,'tn_'.$picid.'.') || strstr($img,'tn_'.$picid.'__')) {
					$imgs[]=$img;
				}
			}
			closedir($handle);
		}
		asort($imgs);
		if (count($imgs) > 0) {
			foreach ($imgs as $img) {
				$imagesCount++;
				$imagesMarkUp.='<div class="wsthumbnail" id="'.$img.'" style="position:relative;float:left">';
				$size=wsResizeImage($product_dir.'/'.str_replace('tn_','',$img),false);
				$imagesMarkUp.='<a href="javascript:void(0);" onMouseOver="wsHoverImage(\''.$product_url.'/'.str_replace('tn_','',$img).'\','.$size['height'].','.$size['width'].')"><img src="'.$product_url.'/'.$img.'" class="borderimg" />';
				$imagesMarkUp.="</a>";
				$imagesMarkUp.='</div>';
			}
		}
		if ($imagesCount > 1) {
			//echo '<div id="uploaded_images">';
			//echo $imagesMarkUp;
			//echo '</div><div style="clear:both"></div>';
		}

		if ($ordering_enabled) {
			if (!$row[4] == 0) {
				$tax=new wsTax(wsPrice::price($row[4]),$row['TAXCATEGORYID']);
				$priceOut="<big><strong>" . $txt['details5'] . ": ". wsPrice::currencySymbolPre().'<span class="wspricein" id="wsprice'.$row[0].'">'.$tax->inFtd.'</span>'.wsPrice::currencySymbolPost()."</strong></big>";
				if (!$no_vat && wsSetting('show_tax_breakdown')) $priceOut.="<br /><small>(".wsPrice::currencySymbolPre().'<span class="wspriceex" id="wsprice'.$row[0].'">'.$tax->exFtd.'</span>'.wsPrice::currencySymbolPost()." ".$txt['general6']." ".$txt['general5'].")</small>";
			}

			// product features
			$allfeatures = $row[8];
			$wsFeatures->setFeatures($allfeatures,$row['FEATURESHEADER'],$row['FEATURES_SET']);
			$wsFeatures->setProduct($row[0]);
			$wsFeaturesOut='';
			$wsFeaturesOut.='<input type="hidden" name="featuresets" value="'.$wsFeatures->sets.'" />';
			if (isset($wsFeatures->set) && $wsFeatures->set) $wsFeaturesOut.='<input type="hidden" name="featuresuniqueset" value="'.$wsFeatures->setid.'" />';
			if (count($wsFeatures->prefil)>0) {
				for ($i=0;$i<$wsFeatures->sets;$i++) {
					$wsFeaturesOut.='<input type="hidden" name="basketid[]" value="'.$wsFeatures->prefil[$i]['id'].'" />';
				}
			}
			$wsFeaturesOut.='<div style="clear:both"></div>';
			$wsFeaturesOut.='<div class="wsfeatures">';
			$wsFeaturesOut.='<table class="'.$wsFeatures->tableClass.'">';
			$features=$wsFeatures->displayFeatures(false);
			$wsFeaturesOut.=$features;
			if (!$row['LINK']) {
				$wsFeaturesOut.='<tr>';
				$wsFeaturesOut.='<td>'.$txt['details6'].':</td>';
				for ($i=0;$i<$wsFeatures->sets;$i++) {
					if (isset($wsFeatures->prefil[$i]['qty'])) $numprod=$wsFeatures->prefil[$i]['qty'];
					elseif (!isset($wsFeatures->setid)) $numprod=1;
					else $numprod='';
					$wsFeaturesOut.='<td><input type="text" size="2" name="numprod[]" value="'.$numprod.'" maxlength="4" /></td>';
				}
				$wsFeaturesOut.='</tr>';
			}
			$wsFeaturesOut.='</table>';
			$wsFeaturesOut.='</div>'; //wsfeatures
			//echo $wsFeaturesOut;

		}
		if ($similar) {
			$similarOut='<div id="similarproducts"><h3>'.$txt['details100'].'</h3><ul>';
			foreach ($similar as $sId => $sName) {
				$similarOut.='<li><a href="?page=details&prod='.$sId.'">'.$sName.'</a></li>';
			}
			$similarOut.='</ul></div>';
		} else $similarOut='';
		if (!isset($refermain)) {
		}

	}
	$tpl=new wsTemplate('productdetails');
	$tpl->parse('image',$screenshot);
	$tpl->parse('thumbnails',$imagesMarkUp);
	$tpl->parse('pricewithtax',wsPrice::currencySymbolPre().$tax->inFtd.wsPrice::currencySymbolPost());
	$tpl->parse('pricewithouttax',wsPrice::currencySymbolPre().$tax->exFtd.wsPrice::currencySymbolPost());
	$tpl->parse('description',nl2br($row['DESCRIPTION']));
	$tpl->parse('price',$row['PRICE']);
	$tpl->parse('similar',$similarOut);
	$tpl->parse('features',$wsFeaturesOut);
	$tpl->parse('orderaction',$orderAction);
	$tpl->parse('show_taxes_breakdown', $no_vat || !wsSetting('show_tax_breakdown') ? 0 : 1);
	$tpl->parse('images_count',$imagesCount);
	$tpl->parse('ordering_enabled',$ordering_enabled);
	$tpl->parse('id',$row['ID']);
	if (class_exists('wsMultiCurrency')) {
		$mc=new wsMultiCurrency();
		$tpl->parse('currency_selector',$mc->currencySelector(false));
	} else $tpl->parse('currency_selector','');



	$tpl->conditions();

	echo $tpl->content;
}

?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	jQuery(document).ready(function() {
          wsFrontPage=false;
          wsCart.order();
	});
//]]>
</script>
<script
	type="text/javascript"
	src="<?php echo ZING_URL;?>fws/js/<?php echo APHPS_JSDIR;?>/imagedisplay.jquery.js"
></script>
