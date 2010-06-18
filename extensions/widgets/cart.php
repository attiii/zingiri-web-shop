<?php
/**
 * Sidebar cart menu widget
 * @param $args
 * @return unknown_type
 */
class widget_sidebar_cart {
	function init($args) {
		global $txt;
		zing_main("init");
		extract($args);
		echo $before_widget;
		echo $before_title;
		echo $txt['menu2'];
		echo $after_title;
		echo '<div id="zing-sidebar-cart">';
		//zing_main("sidebar","cart");
		$this->display();
		echo '</div>';
		echo $after_widget;
	}

	function display() {
		require(ZING_GLOBALS);
		$countCart=CountCart($customerid);
		echo "<ul>";
		echo "<li"; if ($page == "cart") { echo " id=\"active\""; }; echo "><a href=\"?page=cart&action=show\">".$txt['cart5'].": ".$countCart."<br />";
		echo $txt['cart7'].": ".$currency_symbol_pre.myNumberFormat(CalculateCart($customerid), $number_format).$currency_symbol_post."</a></li>";
		if ($countCart > 0 && (ZING_PROTOTYPE || ZING_JQUERY))
		{
			echo '<li id="showcart"><a href="javascript:void(0);">&#x25BE; ('.z_('show').')</a></li>';
			echo '<li id="hidecart"><a href="javascript:void(0);">&#x25B4; ('.z_('hide').')</a></li>';
		}
		$cart="";
		$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (`CUSTOMERID` = ".$customerid." AND `STATUS` = 0) ORDER BY ID";
		$sql = mysql_query($query) or zfdbexit($query);
		while ($row = mysql_fetch_array($sql)) {
			$query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
			$sql_details = mysql_query($query) or die(mysql_error());
			if ($row_details = mysql_fetch_array($sql_details)) {
				$price=$row['PRICE']+calcFeaturesPrice($row['FEATURES']);
				$cart.='<li>';
				$cart.='<a style="display:inline" href="?page=details&prod='.$row[2].'">';
				$cart.=substr($row_details['PRODUCTID'],0,20).' ';
				$cart.='</a>';
				$cart.='<form style="display:inline" id="cart_update'.$row['ID'].'" method="POST" action="?page=cart&action=update">';
				$cart.='<input type="hidden" name="prodid" value="'.$row_details[0].'"/>';
				$cart.='<input type="hidden" name="basketid" value="'.$row[0].'"/>';
				$cart.='<input type="input" size="2" id="numprod" name="numprod" value="'.$row['QTY'].'" READONLY/> ';
				if (ZING_PROTOTYPE || ZING_JQUERY) {
					$cart.='<a style="display:inline" href="javascript:void(0);" onClick="sidebarcart.updateCart('.$row['ID'].',1);">';
					$cart.='&#x25B4;';
					$cart.='</a>';
					$cart.='<a style="display:inline" href="javascript:void(0);" onClick="sidebarcart.updateCart('.$row['ID'].',-1);">';
					$cart.='&#x25BE;';
					$cart.='</a>';
				}
				$cart.='</form>';
				$cart.=' '.$currency_symbol_pre.myNumberFormat($price).$currency_symbol_post.' ';
				if (ZING_PROTOTYPE || ZING_JQUERY) {
					$cart.='<form style="display:inline" id="cart_remove'.$row['ID'].'" method="POST" action="?page=cart&action=update">';
					$cart.='<input type="hidden" name="prodid" value="'.$row_details[0].'"/>';
					$cart.='<input type="hidden" name="basketid" value="'.$row[0].'"/>';
					$cart.='<input type="hidden" name="numprod" value="0" />';
					$cart.='<a style="display:inline" href="javascript:void(0);" onClick="sidebarcart.removeFromCart('.$row['ID'].');">';
					$cart.='<img align="absmiddle" src="'.ZING_URL.'fws/templates/default/images/warning.gif" height="16px" />';
					$cart.='</a>';
					$cart.='</form>';
				}
				$cart.='</li>';
			}
		}
		echo '</ul>';
		if (!empty($cart)) {
			echo '<div id="shoppingcart"><ul>';
			echo $cart;
			echo '</ul></div>';
		}
		if ($countCart > 0) {
			echo '<ul>';
			echo '<li><a href="?page=conditions&action=checkout">'.$txt['menu3'].'</a></li>';
			echo '</ul>';
		}

		if (ZING_PROTOTYPE) {
			?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	document.observe("dom:loaded", function() {
          sidebarcart=new wsCart();
          sidebarcart.contents();
	});
//]]>
</script>
			<?php } elseif (ZING_JQUERY) {?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	jQuery(document).ready(function() {
          sidebarcart=new wsCart();
          sidebarcart.contents();
	});
	//]]>
	</script>
<?php }		
	}
}
$wsWidgets[]=array('class'=>'widget_sidebar_cart','name'=>'Zingiri Web Shop Cart');

?>