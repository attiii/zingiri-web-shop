<?php
function CheckoutInit() {
	global $shippingid,$weightid,$paymentid,$notes,$discount_code;
	if (isset($_POST['shipping'])) { list($weightid, $shippingid) = explode(":", $_POST['shipping']); }
	if (!empty($_POST['shippingid'])) { $shippingid=intval($_POST['shippingid']); }
	elseif (!empty($_GET['shippingid'])) { $shippingid=intval($_GET['shippingid']); }
	if (!empty($_POST['weightid'])) { $weightid=intval($_POST['weightid']); }
	elseif (!empty($_GET['weightid'])) { $weightid=intval($_GET['weightid']); }
	if (!empty($_POST['paymentid'])) { $paymentid=intval($_POST['paymentid']); }
	elseif (!empty($_GET['paymentid'])) { $paymentid=intval($_GET['paymentid']); }
	if (!empty($_POST['notes']))    { $notes=aphpsSanitize($_POST['notes']); }
	elseif (!empty($_GET['notes']))    { $notes=aphpsSanitize($_GET['notes']); }
	else { $notes = ""; }
	if (!empty($_POST['discount_code']))	{ $discount_code= stripslashes(htmlentities($_POST['discount_code'])); }
	elseif (!empty($_POST['discount_code']))	{ $discount_code= stripslashes(htmlentities($_POST['discount_code'])); }
	else { $discount_code = ""; }
}

function CheckoutSteps() {
	global $page,$action,$step,$dbtablesprefix,$customerid,$conditions_page,$shipping_page;

	if (FASTCHECKOUT) {
		$steps=2;
	} else {
		$steps=5;
		if (!$conditions_page) $steps--;
		if (!$shipping_page) {
			$steps--;
			$query="SELECT * FROM `".$dbtablesprefix."shipping` ORDER BY `id` LIMIT 1";
			$sql = mysql_query($query) or die(mysql_error());
			if ($row = mysql_fetch_row($sql)) {
				$cart_weight = WeighCart($customerid);
				$query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$row[0]."' ORDER BY `paymentid`";
				$sql = mysql_query($query) or die(mysql_error());
				if (mysql_num_rows($sql) <= 1) {
					$steps--;
				}
			}
		}
		if (!ActiveDiscounts()) $steps--;
	}
	return $steps;

}

function CheckoutThisStep() {
	global $page,$action,$step,$dbtablesprefix,$customerid,$conditions_page,$shipping_page;

	if (FASTCHECKOUT) {
		if ($page=="onecheckout" || $page=="checkout1") $step=1;
		//elseif ($page=="checkout" && isset($_GET['status'])) $step=2;
		elseif ($page=="checkout") $step=2;
		$newstep=$step;
	} else {
		if ($page=="conditions") $step=1;

		if ($page=="shipping" && !isset($_GET['step'])) {
			$step=2;
		}

		if ($page=="shipping" && ($_GET['step']==2 || $_POST['step']==2)) {
			$step=3;
		}

		if ($page=="discount") $step=4;

		if ($page=="checkout") $step=5;

		$newstep=$step;
		if ($step>1 && !$conditions_page) $newstep--;
		if ($step>2 && !$shipping_page) $newstep--;
		if ($step>3 && !ActiveDiscounts()) $newstep--;
	}
	return $newstep;
}

function CheckoutShowProgress() {

	global $gfx_dir,$use_discounts;

	$steps=CheckoutSteps();
	$step=CheckoutThisStep();
	
	echo '<h4>';
	for ($i=1; $i<=$steps; $i++) {
		if ($step == $i) {
			echo '<img src="'.$gfx_dir.'/arrow.gif" alt="1">';
			echo '&nbsp;<img src="'.$gfx_dir.'/'.$i.'.gif" alt="'.$i.'">';
		} else {
			echo '&nbsp;<img src="'.$gfx_dir.'/'.$i.'_.gif" alt="'.$i.'">';
		}
	}
	echo '</h4><br /><br />';

}

function CheckoutNextStep() {
	global $shippingid,$weightid,$paymentid,$notes,$discount_code;
	global $page,$action,$step,$dbtablesprefix,$customerid,$conditions_page,$shipping_page;

	CheckoutInit();

	$redir="";
	if ($page=="conditions" && FASTCHECKOUT) {
		$redir="?page=checkout1";
	}
	elseif ($page=="conditions" && !$conditions_page) {
		$redir="?page=shipping";
	}
	elseif ($page=="shipping" && $action=="" && !$shipping_page && !isset($_GET['step'])) {
		$query="SELECT * FROM `".$dbtablesprefix."shipping` ORDER BY `id` LIMIT 1";
		$sql = mysql_query($query) or die(mysql_error());
		if ($row = mysql_fetch_row($sql)) {
			$shippingid=$row[0];
			$cart_weight = WeighCart($customerid);
			$weight_query = "SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE '".$cart_weight."' >= `FROM` AND '".$cart_weight."' <= `TO` AND `SHIPPINGID` = '".$row[0]."' LIMIT 1";
			$weight_sql = mysql_query($weight_query) or die(mysql_error());
			if ($weight_row = mysql_fetch_row($weight_sql)) {
				$weightid=$row[0];
			}
		}
		$redir="?page=shipping&step=2";
	}
	elseif ($page=="shipping" && $action=="" && !$shipping_page && $_GET['step']==2) {
		$query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$shippingid."' ORDER BY `paymentid`";
		$sql = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($sql) <= 1) {
			$row = mysql_fetch_row($sql);
			$paymentid=$row[1];
			$redir="?page=discount";
		}
	}
	elseif ($page=="discount" && !ActiveDiscounts()) {
		$redir="?page=checkout";
	}
	if ($redir) {
		//default Shop page
		$ps=explode(",",get_option("zing_webshop_pages"));
		$pid=$ps[2];

		if ($shippingid) $redir.="&shippingid=".$shippingid;
		if ($weightid) $redir.="&weightid=".$weightid;
		if ($paymentid) $redir.="&paymentid=".$paymentid;
		if ($notes) $redir.="&notes=".$notes;
		if ($discount_code) $redir.="&discount_code=".$discount_code;
		$redir=ZING_HOME.'/index.php'.$redir.'&page_id='.$pid;
		header("Location: ".zurl($redir));
		exit;
	}
}
?>