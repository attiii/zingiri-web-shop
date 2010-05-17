<?php
/*  productadmin.php
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
<?php if ($use_wysiwyg != 3) { include ("addons/tinymce/tinymce.inc"); } ?>

<?php
// admin check
if (IsAdmin() == false) {
	PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
	if (ZING_PROTOTYPE || ZING_JQUERY) echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/ajaxupload.js"></script>';

	if ($action == "edit_product" || $action == "delete_product") {
		if (!empty($_GET['prodid'])) {
			$prodid=$_GET['prodid'];
		}
		if (!empty($_GET['pgroud'])) {
			$pgroup=$_GET['pgroup'];
		}
		if (!empty($_GET['pcat'])) {
			$pcat=$_GET['pcat'];
		}
	}
	if ($action == "add_product") {
		$pnew=0;
		$pgroup=0;
		$pcat=0;
		$pfrontpage=0;
		$price=0;
		$pstock=1; // let's presume that when you add a product it's in stock
		// on special request, it now remembers the group and category of the last product you added
		if (!empty($_GET['pcat'])) {
			$pcat=$_GET['pcat'];
		}
	}
	if ($action == "save_new_product" || $action == "update_product") {
		if (!empty($_POST['pid'])) {
			$pid=$_POST['pid'];
		}
		if (!empty($_POST['pcat'])) {
			$pcat=$_POST['pcat'];
		}
		if (!empty($_POST['text2edit'])) {
			$pdescription=$_POST['text2edit'];
		}
		if (!empty($_POST['pprice'])) {
			$pprice=$_POST['pprice'];
		}
		if (!empty($_POST['pweight'])) {
			$pweight=$_POST['pweight'];
		}
		if (!empty($_POST['pstock'])) {
			$pstock=$_POST['pstock'];
		}
		if (!empty($_POST['pfeatures'])) {
			$pfeatures=$_POST['pfeatures'];
		}
		if (!empty($_POST['image_default'])) {
			$defaultImage=$_POST['image_default'];
		}
		$pfrontpage=CheckBox($_POST['pfrontpage']);
		$pnew=CheckBox($_POST['pnew']);
	}
	if ($action == "update_product") {
		if (!empty($_POST['prodid'])) {
			$prodid=$_POST['prodid'];
		}
	}
	if ($action == "picture_upload_form" || $action == "del_image" || $action == "upload_screenshot") {
		if (!empty($_POST['picid'])) {
			$picid=$_POST['picid'];
		}
		if (!empty($_GET['picid'])) {
			$picid=$_GET['picid'];
		}
	}
	?>
	<?php
	// check for the existance of thumbs for all pictures in the shop
	if ($action == "check_thumbs") {
		createallthumbs($product_dir,$pricelist_thumb_width,$pricelist_thumb_height);
		PutWindow($gfx_dir, $txt['general13'] , $txt['productadmin29'], "notify.gif", "50");
	}

	// delete image
	if ($action == "del_image" || $action == "upload_screenshot") {
		// try to delete every trace of this id, either gif, jpg or png
		if (file_exists($product_dir."/".$picid.".gif")) 	{ unlink($product_dir."/".$picid.".gif"); }
		if (file_exists($product_dir."/tn_".$picid.".gif")) 	{ unlink($product_dir."/tn_".$picid.".gif"); }
		if (file_exists($product_dir."/".$picid.".jpg")) 	{ unlink($product_dir."/".$picid.".jpg"); }
		if (file_exists($product_dir."/tn_".$picid.".jpg")) 	{ unlink($product_dir."/tn_".$picid.".jpg"); }
		if (file_exists($product_dir."/".$picid.".png")) 	{ unlink($product_dir."/".$picid.".png"); }
		if (file_exists($product_dir."/tn_".$picid.".png")) 	{ unlink($product_dir."/tn_".$picid.".png"); }
			
		if ($action == "del_image") { PutWindow($gfx_dir, $txt['general13'] , $txt['productadmin25'], "notify.gif", "50"); }
		$nextlink="<h4><a href=\"?page=browse&action=list&group=".$pgroup."&cat=".$pcat."\">".$txt['productadmin5']."</a></h4>";

	}

	// save new product in database
	if ($action == "save_new_product") {
		if ($file = $_FILES['digitalfile']['name']) {
			$ext = explode(".", $file);
			$ext = strtolower(array_pop($ext));
			$random = CreateRandomCode(15);
			$link=$random.'__'.$file;

			$target_path = ZING_DIG.$link;

			if(move_uploaded_file($_FILES['digitalfile']['tmp_name'], $target_path)) {
				chmod($target_path,0644); // new uploaded file can sometimes have wrong permissions
				// lets try to create a thumbnail of this new image shall we
			}
			else{
				PutWindow($gfx_dir, $txt['general12'], $txt['productadmin2'], "warning.gif", "50");
				echo "debug info:<br />";
				print_r($_FILES);
			}
		} else { $link=""; }

		$query="INSERT INTO `".$dbtablesprefix."product` (`LINK`,`PRODUCTID`,`CATID`,`DESCRIPTION`,`PRICE`,`STOCK`,`FRONTPAGE`,`NEW`,`FEATURES`,`WEIGHT`) VALUES ('".$link."','".$pid."','".$pcat."','".$pdescription."','".$pprice."','".$pstock."','".$pfrontpage."','".$pnew."','".$pfeatures."','".$pweight."')";
		$sql = mysql_query($query) or die(mysql_error());

		// what the picture should be named like depends on settings
		if ($pictureid == 2) {
			$picid = $pid;
		}
		else { $picid = mysql_insert_id(); }
		$prodid=mysql_insert_id();

		PutWindow($gfx_dir, $txt['general13'] , $txt['customer13'], "notify.gif", "50");
		$nextlink="<h4><a href=\"?page=productadmin&action=add_product&pcat=".$pcat."\">".$txt['productadmin4']."</a></h4>";
		//	$action = "picture_upload_form";
	}

	// update product with new values in database
	if ($action == "update_product") {
		// first lets see if the product id has changed. if it has, then the screenshot should be renamed too (if a screenshot is found)
		$query="SELECT * FROM `".$dbtablesprefix."product` WHERE ID=".$prodid;
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_array($sql)) {
			// if the product id has changed and $pictureid (which holds the setting what to use for the picture name) = 2, then rename it to the new id
			if ($row[1] != $pid && $pictureid == 2) {
				if (file_exists($product_dir."/".$row[1].".jpg")) { rename($product_dir."/".$row[1].".jpg", $product_dir."/".$pid.".jpg"); }
				if (file_exists($product_dir."/".$row[1].".gif")) { rename($product_dir."/".$row[1].".gif", $product_dir."/".$pid.".gif"); }
				if (file_exists($product_dir."/".$row[1].".png")) { rename($product_dir."/".$row[1].".png", $product_dir."/".$pid.".png"); }
			}
			// determine how to name the picture
			if ($pictureid == 1) {
				$picid = $row[0];         // pic id is database id
			}
			else { $picid = $row[1]; }    // pic id is product id
		}

		//new digital file
		if ($file = $_FILES['digitalfile']['name']) {
			if ($row['LINK']) unlink(ZING_DIG.$row['LINK']);
			$ext = explode(".", $file);
			$ext = strtolower(array_pop($ext));
			$random = CreateRandomCode(15);
			$link=$random.'__'.$file;

			$target_path = ZING_DIG.$link;

			if(move_uploaded_file($_FILES['digitalfile']['tmp_name'], $target_path)) {
				chmod($target_path,0644); // new uploaded file can sometimes have wrong permissions
				// lets try to create a thumbnail of this new image shall we
			}
			else{
				PutWindow($gfx_dir, $txt['general12'], $txt['productadmin2'], "warning.gif", "50");
				echo "debug info:<br />";
				print_r($_FILES);
			}
		} else { $link=""; }

		// now save new data
		$query="UPDATE `".$dbtablesprefix."product` SET `PRODUCTID`='".$pid."',`CATID`='".$pcat."',`DESCRIPTION`='".$pdescription."',`PRICE`='".$pprice."',`STOCK`='".$pstock."',`FRONTPAGE`='".$pfrontpage."',`NEW`='".$pnew."',`FEATURES`='".$pfeatures."',`WEIGHT`='".$pweight."'";
		if ($link) $query.=",`LINK`='".$link."'";
		$query.=" WHERE ID=".$prodid;
		$sql = mysql_query($query) or die(mysql_error());
		PutWindow($gfx_dir, $txt['general13'] , $txt['customer13'], "notify.gif", "50");
		$nextlink="<h4><a href=\"?page=browse&action=list&group=".$pgroup."&cat=".$pcat."\">".$txt['productadmin5']."</a></h4>";

		//	$action = "picture_upload_form";
	}

	// delete product
	if ($action == "delete_product") {
		// find out the category, so we can beam you back
		$query="SELECT * FROM `".$dbtablesprefix."product` WHERE ID=".$prodid;
		$sql = mysql_query($query) or die(mysql_error());
		while ($row = mysql_fetch_row($sql)) {
			$pcat = $row[2];
		}
		$query="DELETE FROM `".$dbtablesprefix."product` WHERE ID=".$prodid;
		$sql = mysql_query($query) or die(mysql_error());
		PutWindow($gfx_dir, $txt['general13'] , $txt['productadmin26'], "notify.gif", "50");
	}

	// upload the single image to the correct folder
	if ($_FILES['uploadedfile']['name']!='' && ($action == "update_product" || $action == "save_new_product")) {

		$file = $_FILES['uploadedfile']['name'];
		$ext = explode(".", $file);
		$ext = strtolower(array_pop($ext));

		if ($ext == "jpg" || $ext == "gif" || $ext == "png") {
			$target_path = $product_dir."/".$picid.".".$ext;

			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
				chmod($target_path,0644); // new uploaded file can sometimes have wrong permissions
				// lets try to create a thumbnail of this new image shall we
				if ($make_thumbs == 1) {
					createthumb($target_path,$product_dir.'/tn_'.$picid.".".$ext,$pricelist_thumb_width,$pricelist_thumb_height);
				}
				//PutWindow($gfx_dir, $txt['general13'], basename( $_FILES['uploadedfile']['name']).$txt['productadmin1'].$target_path, "notify.gif", "50");
				//echo "<h4><a href=\"?page=productadmin&action=add_product&pcat=".$pcat."\">".$txt['productadmin4']."</a></h4>";
			}
			else{
				PutWindow($gfx_dir, $txt['general12'], $txt['productadmin2'], "warning.gif", "50");
				echo "debug info:<br />";
				print_r($_FILES);
			}
		}
		else { PutWindow($gfx_dir, $txt['general12'], $txt['productadmin3'], "warning.gif", "50"); }
	}

	// move the multiple uploaded images to the correct folder
	if ($_POST['upload_key']!='' && ($action == "update_product" || $action == "save_new_product")) {
		$key=$_POST['upload_key'];
		$handle=opendir($product_dir);
		$imgs=array();
		while (($img = readdir($handle))!==false) {
			if (strstr($img,$key)) {
				$ext = strtolower(substr(strrchr($img, '.'), 1));
				if (strstr($img,'tn_'.$key)) $tn='tn_'; else $tn='';
				$newimg=$tn.$picid.'.'.$ext;
				$i=1;
				while (file_exists($product_dir.'/'.$newimg)) {
					$i++;
					$newimg=$tn.$picid.'__'.sprintf('%03d',$i).'.'.$ext;
				}
				copy($product_dir.'/'.$img,$product_dir.'/'.$newimg);
				unlink($product_dir.'/'.$img);
				if (strstr($img,$defaultImage)) $defaultImage=$newimg;
			}
		}
		closedir($handle);
	}

	//delete images if required
	if (count($_POST['delimage'])>0 && ($action == "update_product" || $action == "save_new_product")) {
		foreach ($_POST['delimage'] as $imageid) {
			unlink($product_dir.'/'.$imageid);
			unlink($product_dir.'/'.str_replace('tn_','',$imageid));
		}
	}

	//set default image
	if (isset($_POST['image_default']) && ($action == "update_product" || $action == "save_new_product")) {
		$img=$defaultImage;
		$query="UPDATE `".$dbtablesprefix."product` SET `DEFAULTIMAGE`='".$defaultImage."'";
		$query.=" WHERE ID=".$prodid;
		$sql = mysql_query($query) or die(mysql_error());
	}

	//display next link if any
	echo $nextlink;

	// read values to show in form
	if ($action == "edit_product") {
		$query = "SELECT * FROM `".$dbtablesprefix."product` WHERE ID=".$prodid;
		$sql = mysql_query($query) or die(mysql_error());
		while ($row = mysql_fetch_array($sql)) {
			$prod = $row[0];
			$pid = $row[1];
			$pcat = $row[2];
			$pdescription = $row[3];
			$pprice = $row[4];
			$pstock = $row[5];
			$pfrontpage = $row[6];
			$pnew = $row[7];
			$pfeatures = $row[8];
			$pweight = $row[9];
			$defaultImage = $row['DEFAULTIMAGE'];
			$link = explode('__',$row['LINK']);
			// determine how to name the picture
			if ($pictureid == 1) {
				$picid = $row[0];         // pic id is database id
			}
			else { $picid = $row[1]; }    // pic id is product id
		}
	}

	// show form with or without values
	if ($action == "add_product" || $action == "edit_product") {

		echo "<table width=\"90%\" class=\"datatable\">";
		echo "<caption>";
		if ($action == "add_product") {
			echo $txt['productadmin6'];
		}
		else {
			echo $txt['productadmin7'];
		}
		echo "</caption>";
		echo "<tr><td>";
		echo "<form id=\"wsproduct\" enctype=\"multipart/form-data\" method=\"POST\" action=\"".zurl('index.php?page=productadmin',false)."\">";
		echo $txt['productadmin18']." <select name=\"pcat\">";

		$error = 0;

		// pull down menu with all groups and their categories
		$query = "SELECT * FROM `".$dbtablesprefix."group` ORDER BY `NAME` ASC";
		$sql = mysql_query($query) or die(mysql_error());

		$groupNum = 0;
		$catNum = 0;

		if (mysql_num_rows($sql) == 0) {
			echo "</select><br /><br />".$txt['productadmin8'];
			$groupNum = 0;
		}
		else {
			$groupNum = $groupNum +1;
			while ($row = mysql_fetch_row($sql)) {

				$query_cat = "SELECT * FROM `".$dbtablesprefix."category` WHERE `GROUPID` = " . $row[0] . " ORDER BY `DESC` ASC";
				$sql_cat = mysql_query($query_cat) or die(mysql_error());

				if (mysql_num_rows($sql_cat) != 0) {
					$catNum = $catNum +1;
					while ($row_cat = mysql_fetch_row($sql_cat)) {
						$selected = "";
						if ($row_cat[0] == $pcat) { $selected = " SELECTED"; }
						echo "<option value=\"".$row_cat[0]."\"".$selected.">". $row[1] . "-->" . $row_cat[1] . "</option>\n";
					}
				}
			}
		}


		mysql_free_result($sql);
		echo "</select><br />";

		if ($groupNum > 0 && $catNum > 0) {

			echo $txt['productadmin9']." <input type=\"text\" name=\"pid\" size=\"60\" maxlength=\"60\" value=\"".$pid."\"><br />";
			echo $txt['productadmin10']."<br /><textarea name=\"text2edit\" rows=\"15\" cols=\"50\">".$pdescription."</textarea><br />";
			echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"50000000\">";
			echo $txt['productadmin100']." <input name=\"digitalfile\" size=\"35\" type=\"file\">  ".$link[1]."<br />";
			echo $txt['productadmin30']." <input type=\"text\" name=\"pfeatures\" size=\"55\" value=\"".$pfeatures."\"><br />";
			echo $txt['productadmin11'];
			if ($no_vat == 0 && $db_prices_including_vat == 0) { echo " (".$txt['general6']." ".$txt['general5'].")"; }
			if ($no_vat == 0 && $db_prices_including_vat == 1) { echo " (".$txt['general7']." ".$txt['general5'].")"; }
			echo " <input type=\"text\" name=\"pprice\" size=\"10\" maxlength=\"10\" value=\"".$pprice."\"><br />";
			echo $txt['productadmin31']." (".$weight_metric.") <input type=\"text\" name=\"pweight\" size=\"10\" maxlength=\"10\" value=\"".$pweight."\"><br />";

			if ($stock_enabled == 1) {
				echo $txt['productadmin12'];
			}
			else {
				echo $txt['productadmin13'];
			}
			echo " <input type=\"text\" name=\"pstock\" size=\"4\" maxlength=\"10\" value=\"".$pstock."\"><br />";
			echo $txt['productadmin14']." <input type=\"checkbox\" name=\"pfrontpage\" "; if ($pfrontpage == 1) { echo "checked"; } echo "><br />";
			echo $txt['productadmin15']." <input type=\"checkbox\" name=\"pnew\" "; if ($pnew == 1) { echo "checked"; } echo "><br />";
			echo "<br />";
			if (ZING_PROTOTYPE || ZING_JQUERY) {
				echo '<input type="button" id="upload_button" value="'.$txt['productadmin21'].'" />';
			} else {
				echo $txt['productadmin21'].' '.wsComments($txt['productadmin19']).'<input name="uploadedfile" type="file"><br />';
			}
			echo "<br /><br />";
			wsShowImage($picid,$defaultImage);

			echo "<br /><div align=center>";

			if ($action == "add_product") {
				echo "<input type=\"hidden\" name=\"action\" value=\"save_new_product\">";
				echo "<input type=\"submit\" value=\"".$txt['productadmin16']."\">";
			}
			else {
				echo "<input type=\"hidden\" name=\"prodid\" value=\"".$prodid."\">";
				echo "<input type=\"hidden\" name=\"action\" value=\"update_product\">";
				echo "<input type=\"submit\" value=\"".$txt['productadmin17']."\">";
			}
		}
		else {
			if ($catNum ==0) { echo "</select><br /><br />".$txt['productadmin22']; }
		}

		echo '<input type="hidden" name="upload_key" id="upload_key" value="'.create_sessionid(16,1,36).'">';
		echo "</div></form>";

		echo "</td></tr></table>";

	}

	//make thumbnail option
	if ($action == "add_product" || $action == "edit_product") {
		echo "<br /><br />";
		echo "<h6>".$txt['productadmin27']."</h6>";
		echo "<ul>";
		echo "<li><a href=\"?page=productadmin&action=check_thumbs\">".$txt['productadmin28']."</a></li>";
		echo "</ul>";
	}
}

function wsShowImage($picid,$defaultImage) {
	global $product_dir,$product_url,$txt,$pricelist_thumb_width,$pricelist_thumb_height;
	echo '<div id="uploaded_images">';
	$handle=opendir($product_dir);
	while (($img = readdir($handle))!==false) {
		if (strstr($img,'tn_'.$picid.'.')) {
			echo '<div id="'.$img.'" style="position:relative;float:left">';
			echo "<img src=\"".$product_url."/".$img."\" class=\"borderimg\" /><br />";
			if (ZING_PROTOTYPE || ZING_JQUERY) echo '<a href="javascript:wsDeleteImage(\''.$img.'\');">';
			else echo "<a href=\"".zurl("index.php?page=productadmin&action=del_image&picid=".$picid)."\">";
			echo '<img style="position:absolute;right:0px;top:0px;" src="'.ZING_URL.'fws/templates/default/images/delete.gif" height="16px" width="16px" />';
			echo "</a>";
			if ($img == $defaultImage) $checked='checked'; else $checked='';
			echo '<input type="radio" name="image_default" value="'.$img.'" '.$checked.' />';
				
			echo '</div>';
		}
	}
	closedir($handle);

	/*
	 if (file_exists($product_dir."/".$picid.".jpg")) { $thumb = $picid.".jpg"; }
	 if (file_exists($product_dir."/".$picid.".gif")) { $thumb = $picid.".gif"; }
	 if (file_exists($product_dir."/".$picid.".png")) { $thumb = $picid.".png"; }
	 if ($thumb != "") {
	 $size = getimagesize($product_dir."/".$thumb);
	 $height = $size[1];
	 $width = $size[0];
	 //$ref=150;
	 if ($height > $pricelist_thumb_height)
	 {
	 $height = $pricelist_thumb_height;
	 $percent = ($size[1] / $height);
	 $width = round($size[0] / $percent);
	 }
	 if ($width > $pricelist_thumb_width)
	 {
	 $width = $pricelist_thumb_width;
	 $percent = ($size[0] / $width);
	 $height = round($size[1] / $percent);
	 }
	 echo '<div style="position:relative;float:left">';
	 echo "<img src=\"".$product_url."/".$thumb."\" class=\"borderimg\" height=".$height." width=".$width."><br />";
	 echo "<a href=\"".zurl("index.php?page=productadmin&action=del_image&picid=".$picid)."\">";
	 echo '<img style="position:absolute;right:0px;top:0px;" src="'.ZING_URL.'fws/templates/default/images/delete.gif" height="16px" width="16px" />';
	 echo "</a>";
	 echo '</div>';
	 }
	 */
	echo '</div><div style="clear:both"></div>';
}
if ($action == "add_product" || $action == "edit_product") {
	if (ZING_PROTOTYPE) {
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/imageupload.proto.js"></script>';
	} elseif (ZING_JQUERY) {
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/imageupload.jquery.js"></script>';
	}
}
?>