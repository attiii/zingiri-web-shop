<?php
/**
 * Sidebar cart menu widget
 * @param $args
 * @return unknown_type
 */
class widget_sidebar_product_random {
	function init($args) {
		if ($row=$this->selectProduct()) {
			global $txt;
			zing_main("init");
			extract($args);
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
		echo '<img src="'.$product_url.'/'.$row['DEFAULTIMAGE'].'" />';
		echo '</a>';
		echo '<br />';
		$tax=new wsTax($row['PRICE']);
		echo "<big><strong>". $currency_symbol_pre.$tax->inFtd.$currency_symbol_post."</strong></big>";

	}

	function selectProduct() {
		$db=new db();
		if ($c=$db->select("select id from ##product where defaultimage is not null")) {
			$r=mt_rand(0,$c-1);
			$db->select("select * from ##product limit ".$r.",1");
			$row=$db->next();
			return $row;
		} else return false;
	}
}

$wsWidgets[]=array('class'=>'widget_sidebar_product_random','name'=>'Zingiri Web Shop Random Product');

?>