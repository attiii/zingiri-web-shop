<?php
/*  images.inc.php
 Copyright 2008,2009,2010 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Web Shop.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Zingiri Web Shop; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
function createthumb($name,$filename,$new_w,$new_h){
	if (file_exists($filename)) { unlink($filename); }
	$ext = strtolower(substr(strrchr($name, '.'), 1));
	if ($ext == 'jpg' || $ext == 'jpeg'){
		$src_img=imagecreatefromjpeg($name);
	}
	if ($ext == 'png'){
		$src_img=imagecreatefrompng($name);
	}
	if ($ext == 'gif'){
		$src_img=imagecreatefromgif($name);
	}
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	if ($old_x > $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$old_y*($new_h/$old_x);
	}
	if ($old_x < $old_y) {
		$thumb_w=$old_x*($new_w/$old_y);
		$thumb_h=$new_h;
	}
	if ($old_x == $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	if ($ext == 'jpg' || $ext == 'jpeg'){
		imagejpeg($dst_img,$filename);
	}
	if ($ext == 'png'){
		imagepng($dst_img,$filename);
	}
	if ($ext == 'gif'){
		imagegif($dst_img,$filename);
	}
	imagedestroy($dst_img);
	imagedestroy($src_img);
	chmod($filename,0644); // new file can sometimes have wrong permissions
}

function createallthumbs($gfx_folder,$thumb_w,$thumb_h) {
	$pics=directory($gfx_folder,'jpg,JPG,JPEG,jpeg,png,PNG');
	$pics=ditchtn($pics,'tn_');
	if ($pics[0]!='')
	{
		foreach ($pics as $p)
		{
			createthumb($gfx_folder.'/'.$p,$gfx_folder.'/tn_'.$p,$thumb_w,$thumb_h);
		}
	}

}
function wsResizeImage($image,$thumb=true,$gfx=false) {
	global $pricelist_thumb_width,$pricelist_thumb_height,$product_url,$product_dir,$product_max_height,$product_max_width;

	if ($thumb) {
		$maxWidth=$pricelist_thumb_width;
		$maxHeight=$pricelist_thumb_height;
	} else {
		$maxWidth=$product_max_width;
		$maxHeight=$product_max_height;
	}
	if (!$gfx) $size = getimagesize(str_replace($product_url,$product_dir,$image));
	else $size = getimagesize(str_replace(ZING_URL,ZING_LOC,$image));
	$height = $size[1] ? $size[1] : $pricelist_thumb_height;
	$width = $size[0] ? $size[0] : $pricelist_thumb_width;
	$resized = 0;
	$percent = min($maxHeight / $size[1], $maxWidth / $size[0]);
	$height = intval($size[1] * $percent);
	$width = intval($size[0] * $percent);
	if ($height!=$size[1] || $width!=$size[0]) $resized=1;

	return array('height' => $height,'width' => $width,'resized' => $resized);
}

function wsDefaultProductImageUrl($picture,$defaultimage,$thumb=true) {
	global $product_max_height,$product_max_width,$product_dir,$product_url,$gfx_dir,$make_thumbs,$thumbs_in_pricelist;

	$image_url = "";

	if (!empty($defaultimage) && thumb_exists($product_dir ."/". $defaultimage)) {
		$image_url = $product_url."/".$defaultimage;
	} elseif (!$thumb) {
		$i=0;
		while ($image_url=="" && $i<=99) {
			if ($i==0) $suffix='';
			else $suffix='__'.sprintf("%03d",$i);
			foreach (array('.jpg','.gif','.png') as $ext) {
				if (thumb_exists($product_dir ."/". $picture . $suffix . $ext)) { $image_url = $product_url."/".$picture.$suffix.$ext; }
			}
			$i++;
		}
	} else {
		$i=0;
		while ($image_url=="" && $i<=99) {
			if ($i==0) $suffix='';
			else $suffix='__'.sprintf("%03d",$i);
			foreach (array('.jpg','.gif','.png') as $ext) {
				if (thumb_exists($product_dir ."/tn_". $picture . $suffix . $ext)) { $image_url = $product_url."/tn_".$picture.$suffix.$ext; }
			}
			$i++;
		}
	}
	if ($image_url) {
		$gfx=false;
	} else {
		$gfx=true;
		if ($thumb) $image_url = $gfx_dir."/nopic.gif";
		else $image_url = $gfx_dir."/photo.gif";
	}
	//list($height,$width,$resized)=
	$size=wsResizeImage($image_url,$thumb,$gfx);
	$width = " width=\"".$size['width']."\"";
	$height = " height=\"".$size['height']."\"";
	return array($image_url,$height,$width,$size['resized']);
}

function wsProductImage($picture,$default_image) {
	global $product_dir,$product_url,$make_thumbs,$pricelist_thumb_width,$pricelist_thumb_height,$thumbs_in_pricelist;

	// determine resize of thumbs
	$width = "";
	$height = "";
	if ($pricelist_thumb_width != 0) { $width = " width=\"".$pricelist_thumb_width."\""; }
	if ($pricelist_thumb_height != 0) { $height = " height=\"".$pricelist_thumb_height."\""; }
	if (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = $product_url."/".$picture.".jpg"; }
	if (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = $product_url."/".$picture.".gif"; }
	if (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = $product_url."/".$picture.".png"; }
	// if the script uses make_thumbs, then search for thumbs
	if ($make_thumbs == 1) {
		if (!empty($default_image) && thumb_exists($product_dir ."/". $default_image)) { $thumb = $product_url."/".$default_image; }
		elseif (thumb_exists($product_dir ."/tn_". $picture . ".jpg")) { $thumb = $product_url."/tn_".$picture.".jpg"; }
		elseif (thumb_exists($product_dir ."/tn_". $picture . ".gif")) { $thumb = $product_url."/tn_".$picture.".gif"; }
		elseif (thumb_exists($product_dir ."/tn_". $picture . ".png")) { $thumb = $product_url."/tn_".$picture.".png"; }
	}

	if ($thumb == "") {
		// use a photo icon instead of a thumb
		$thumb = $gfx_dir."/photo.gif>";
		$thumb = "";
	}
	return $thumb;
}
