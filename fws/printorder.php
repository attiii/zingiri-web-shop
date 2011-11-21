<?php
if ($index_refer <> 1) { exit(); }

if (!LoggedIn()) { exit(); }

if (!empty($_GET['orderid'])) {
	$orderid=intval($_GET['orderid']);
}

// lets check if the order you are trying to read is REALLY your own order
$query = sprintf("SELECT * FROM `".$dbtablesprefix."order` WHERE ID = %s", quote_smart($orderid));
$sql = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_row($sql)) {
	$webid = $row[7];
	$ownerid = $row[5];
}
if ($ownerid != $customerid && IsAdmin() == false) {
	PutWindow($gfx_dir, $txt['general12'], $txt['general1'], "warning.gif", "50"); // access denied
}
else {
	$fp = fopen($orders_dir."/".$webid.".php", "rb") or die("Couldn't open order");
	$ordertext = fread($fp, filesize($orders_dir."/".$webid.".php"));
	list($security, $order) = explode("?>", $ordertext);
	fclose($fp);

	// in version 2.1 we switched to html orders, but included the html header. that is wrong
	// from version 2.2 the orders are saved in html, but without the header. the part below
	// is to be compatible with 2.1
	if (substr ($order, 0, 6) == "<html>") {
		ob_end_clean();
		$order = str_replace("<body>", "<body onLoad=\"javascript:window.print()\">", $order);
		echo $order;
		die();
	} elseif ($_REQUEST['wslive']) {
		$saasRet['print']=1;
		$saasRet['print_title']=$webid;
?><table width="80%" class="datatable">
	<caption><?php echo $webid; ?></caption>
	<tr>
		<td><?php echo $order; ?></td>
	</tr>
</table>
<?php 
	} else {
		ob_end_clean();
		// if there are linebreaks, then we have a new order. if not, then it's an old one that needs nl2br
		$pos = strpos ($order, "<br />");
		if ($pos === false) { $order = nl2br($order); }
		?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>">
<!-- <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri();?>" />-->
<title><?php echo $webid ?></title>
</head>
<body onLoad="javascript:window.print()">
<table width="80%" class="datatable">
	<caption><?php echo $webid; ?></caption>
	<tr>
		<td><?php echo $order; ?></td>
	</tr>
</table>
</body>
</html>
		<?php
		die();
	}
}
?>