<?php
/**
 * Sidebar products menu widget
 * @param $args
 * @return unknown_type
 */
class widget_sidebar_products {
	function init($args,$displayTitle=true) {
		global $txt;
		zing_main("init");
		if (is_array($args)) extract($args);
		echo $before_widget;
		echo $before_title;
		if ($displayTitle) echo $txt['menu15'];
		echo $after_title;
		echo '<div id="zing-sidebar-products">';
		//zing_main("sidebar","products");
		$this->display();
		echo "</div>";
		echo $after_widget;
	}
	function display() {
		global $group,$cat;
		
		require(ZING_GLOBALS);
		$wsCatCollapse=wsSetting('catcollapse');
		// if the category is send, then use that to find out the group
		if ($cat != 0 && $group == 0) { $group = TheGroup($cat); }

		$query = "SELECT * FROM `".$dbtablesprefix."group` ORDER BY `SORTORDER`,`NAME` ASC";
		$sql = mysql_query($query) or die(mysql_error());

		if (mysql_num_rows($sql) == 0) {
			echo $txt['menu17']; // no groups found
		}
		else {
			echo "<ul id=\"zing-navlist\">\n";
			while ($row = mysql_fetch_row($sql)) {
				// lets find out how many categories there are in the group
				$query_cat = sprintf("SELECT * FROM `".$dbtablesprefix."category` where `GROUPID`=%s ORDER BY `SORTORDER`,`DESC` ASC", quote_smart($row[0]));
				$sql_cat = mysql_query($query_cat) or die(mysql_error());
				$ahref = "";

				// if there is only 1 category in the group, then jump to the browse list instantly
				if (mysql_num_rows($sql_cat) == 1) {
					$row_cat = mysql_fetch_row($sql_cat);
					$ahref = zurl("index.php?page=browse&action=list&group=".$row[0]."&cat=".$row_cat[0]);
					if ($group != $row[0]) {
						echo "<li><a href=\"".$ahref."\">" . $row[1] . "</a></li>\n";
					}
					else {
						//select/highlight
						echo "<li id=\"active\"><a id=\"current\" href=\"".$ahref."\">" . $row[1] . "</a></li>\n";
					}
				}
				// if there are more categories in the group, then show the category list
				if (mysql_num_rows($sql_cat) > 1) {
					if (SHOWCAT && (ZING_JQUERY)) {
						$ahref = zurl("index.php?page=browse&action=list&orderby=DESCRIPTION&group=".$row[0]);
						if (!$wsCatCollapse) {
							echo '<li>'.$row[1];
							echo '<ul id="group'.$row[0].'">';
						}
						elseif ($wsCatCollapse && $row[0]==$group) {
							echo '<li id="group'.$row[0].'"><a class="zing-product-group" href="javascript:wsGroupToggle('.$row[0].')">'.$row[1].'</a>';
							echo '<ul id="group'.$row[0].'">';
						}
						elseif ($wsCatCollapse) {
							echo '<li id="group'.$row[0].'"><a class="zing-product-group" href="javascript:wsGroupToggle('.$row[0].')">'.$row[1].'</a>';
							echo '<ul style="display:none" id="group'.$row[0].'">';
						} else {
							echo "<li>".$row[1];
							echo '<ul id="group'.$row[0].'">';
						}
						while ($row_cat = mysql_fetch_row($sql_cat)) {
							if ($cat==$row_cat[0]) $active='id="active"'; else $active="";
							$ahref = zurl("index.php?page=browse&action=list&orderby=DESCRIPTION&group=".$row[0]."&kat=".$row_cat[0]);
							echo "<li ".$active."><a href=\"".$ahref."\">" . $row_cat[1] . "</a>";
						}
						echo '</ul>';
						echo '</li>';
					} else {
						if ($row_cat = mysql_fetch_row($sql_cat)) {
							$ahref = zurl("index.php?page=categories&group=".$row[0]);
						}
						// now show the menu link, if ahref is not empty
						if ($ahref != "") {
							if ($group != $row[0]) {
								echo "<li><a href=\"".$ahref."\">" . $row[1] . "</a></li>\n";
							}
							else {
								//select/highlight
								echo "<li id=\"active\"><a id=\"current\" href=\"".$ahref."\">" . $row[1] . "</a></li>\n";
							}
						}
					}
				}
			}
			echo "</ul>\n";
		}
		echo '<script type="text/javascript" src="' . ZING_URL . 'fws/js/'.APHPS_JSDIR.'/productmenu.jquery.js"></script>';
	}
}

$wsWidgets[]=array('class'=>'widget_sidebar_products','name'=>'Zingiri Web Shop Products','title'=>'menu15');

?>