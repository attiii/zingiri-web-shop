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

	function init($args,$displayTitle=true) {
		global $txt;
		zing_main("init");
		if (is_array($args)) extract($args);
		echo $before_widget;
		echo $before_title;
		if ($displayTitle) echo $txt['menu14'];
		echo $after_title;
		echo '<div id="zing-sidebar-general">';
		$this->display();
		echo '</div>';
		echo $after_widget;
	}

	function display() {
		require(ZING_GLOBALS);
		echo "<ul id=\"zing-navlist\">\n";
		if (ZING_CMS=='dp') { echo "<li"; if ($page == "main") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=main")."\">" . $txt['main1'] . "</a></li>\n"; }
		echo "<li"; if ($page == "search") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=search")."\">" . $txt['menu4'] . "</a></li>\n";
		if ($new_page == 1) { echo "<li"; if ($page == "browse" && $action=="shownew") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=browse&action=shownew")."\">" . $txt['menu16'] . "</a></li>\n"; }
		if ((ZING_CMS=='jl' || ZING_CMS=='dp' || ZING_LIVE)) {
			if (!LoggedIn()) {
				echo "<li"; if ($page == "my") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=my")."\">" . $txt['menu12'] . "</a></li>\n";
				echo "<li"; if ($page == "customer") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=customer&action=add")."\">" . $txt['menu13'] . "</a></li>\n";
			} elseif (LoggedIn()==1) {
				echo "<li"; if ($page == "my") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=my")."\">" . $txt['menu10'] . "</a></li>\n";
				echo "<li"; if ($page == "logout") { echo " id=\"active\""; }; echo "><a href=\"".zurl("index.php?page=logout")."\">" . $txt['menu11'] . "</a></li>\n";
			}
		}
		echo "</ul>\n";
	}
}

$wsWidgets[]=array('name'=>'Zingiri Web Shop General','class'=>'widget_sidebar_general','title'=>'menu14');
