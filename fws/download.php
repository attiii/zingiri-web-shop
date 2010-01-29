<?php
if (isset($_POST['abspath'])) $abspath=$_POST['abspath']; else $abspath=$_GET['abspath'];
if (!is_dir($abspath)) die('Error downloading');
if (isset($_POST['basketid'])) $basketid=$_POST['basketid']; else $basketid=$_GET['basketid'];
if (!is_numeric($basketid)) die('Error downloading');

require($abspath.'wp-blog-header.php');

require (ZING_LOC."./zing.readcookie.inc.php");      // read the cookie

$query = "SELECT ORDERID,PRODUCTID FROM `".$dbtablesprefix."basket` WHERE `CUSTOMERID` = ".$customerid." AND `ID` = ".$basketid." ORDER BY ID DESC";
$sql = mysql_query($query) or zfdbexit($query);
if ($row = mysql_fetch_array($sql)) {

	$query = "SELECT STATUS FROM `".$dbtablesprefix."order` where `ID`='" . $row['ORDERID'] . "'";
	$sql_details = mysql_query($query) or die(mysql_error());
	$row_order = mysql_fetch_array($sql_details);
	$query = "SELECT LINK FROM `".$dbtablesprefix."product` where `ID`='" . $row['PRODUCTID'] . "'";
	$sql_details = mysql_query($query) or die(mysql_error());
	$row_details = mysql_fetch_array($sql_details);
	if ($row_details['LINK'] && ($row_order['STATUS']==5 || $row_order['STATUS']==6 || IsAdmin())) {
		send_file(ZING_DIG,$row_details['LINK']);
	}

}
# Send (download) file via pass thru
#-------------------------------------
function send_file($path, $file){

	# Make sure the file exists before sending headers
	#-------------------------------------------------
	$mainpath = "$path/$file";
	$filesize2 = sprintf("%u", filesize($mainpath));

	if(!$fdl=@fopen($mainpath,'r')){
		#include ("$header");
		print "<p><center><font class=\"changed\">ERROR - Invalid Request (Downloadable file Missing or Unreadable)</font></center><br><br>";
		die;
	}else{
		set_time_limit(0);
		# Send the headers then send the file
		#------------------------------------
		header("Cache-Control: ");# leave blank to avoid IE errors
		header("Pragma: ");# leave blank to avoid IE errors
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$file."\"");
		header("Content-length:".(string)($filesize2));
		sleep(1);
		fpassthru($fdl);
	}
	return;
}
?>