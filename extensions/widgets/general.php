<?php
/**
 * Sidebar general menu widget
 * @param $args
 * @return unknown_type
 */
class widget_sidebar_general {
	
	function __construct() {
		//die('stop');
	}
	
	function init($args) {
		global $txt;
		zing_main("init");
		extract($args);
		echo $before_widget;
		echo $before_title;
		echo $txt['menu14'];
		echo $after_title;
		echo '<div id="zing-sidebar-general">';
		$this->display();
		echo '</div>';
		echo $after_widget;
	}

	function display() {
		require(ZING_GLOBALS);
		echo "<ul id=\"zing-navlist\">\n";
		echo "<li"; if ($page == "search") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=search")."\">" . $txt['menu4'] . "</a></li>\n";
		if ($new_page == 1) { echo "<li"; if ($page == "browse" && $action=="shownew") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=browse&action=shownew")."\">" . $txt['menu16'] . "</a></li>\n"; }
		echo "<li"; if ($page == "contact") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=contact")."\">" . $txt['menu8'] . "</a></li>\n";
		if (ZING_CMS=='jl' && !LoggedIn()) {
			echo "<li"; if ($page == "my") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=my")."\">" . $txt['menu12'] . "</a></li>\n";
			echo "<li"; if ($page == "customer") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=customer&action=add")."\">" . $txt['menu13'] . "</a></li>\n";
		} else {
			echo "<li"; if ($page == "my") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=my")."\">" . $txt['menu10'] . "</a></li>\n";
			echo "<li"; if ($page == "logout") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=logout")."\">" . $txt['menu11'] . "</a></li>\n";
		}
		echo "</ul>\n";
	}
}

$wsWidgets[]=array('name'=>'Zingiri Web Shop General','class'=>'widget_sidebar_general');

?>