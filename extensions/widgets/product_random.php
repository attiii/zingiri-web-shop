<?php
/**
 * Sidebar cart menu widget
 * @param $args
 * @return unknown_type
 */
class widget_sidebar_product_random {
	function init($args,$displayTitle=true) {
		if ($row=$this->selectProduct()) {
			global $txt;
			zing_main("init");
			if (is_array($args)) extract($args);
			echo $before_widget;
			echo $before_title;
			echo $row['PRODUCTID'];
			echo $after_title;
			echo '<center><div id="zing-sidebar-random-product">';
			echo $this->display($row);
			echo '</div></center>';
			echo $after_widget;
		}
	}

	function display($row) {
		require(ZING_GLOBALS);
		$href=zurl("index.php?page=details&prod=".$row['ID']."&cat=".$row['CATID']);
		echo '<a href="'.$href.'">';
		list($image_url,$height,$width)=wsDefaultProductImageUrl($row['ID'],$row['DEFAULTIMAGE']);
		echo '<img src="'.$image_url.'" />';
		echo '</a>';
		echo '<br />';
		$tax=new wsTax(wsPrice::price($row['PRICE']),$row['TAXCATEGORYID']);
		echo "<big><strong>". wsPrice::currencySymbolPre().$tax->inFtd.wsPrice::currencySymbolPost()."</strong></big>";
	}

	function selectProduct() {
		$db=new db();
		if ($c=$db->select("select id from ##product")) {
			$r=mt_rand(0,$c-1);
			$db->select("select * from ##product limit ".$r.",1");
			$row=$db->next();
			return $row;
		} else return false;
	}
}

$wsWidgets[]=array('class'=>'widget_sidebar_product_random','name'=>'Zingiri Web Shop Random Product');

?>