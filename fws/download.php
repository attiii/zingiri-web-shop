<?php
if (isset($_POST['abspath'])) $abspath=$_POST['abspath']; else $abspath=$_GET['abspath'];
if (!is_dir($abspath)) die('Error downloading');
if (isset($_POST['basketid'])) $basketid=$_POST['basketid']; else $basketid=$_GET['basketid'];
if (!is_numeric($basketid)) die('Error downloading');
require($abspath.'wp-blog-header.php');
error_reporting(E_ALL ^ E_NOTICE); // ^ E_NOTICE
set_error_handler("user_error_handler");

require (ZING_LOC."./zing.readcookie.inc.php");      // read the cookie

//die($abspath);


//@apache_setenv('no-gzip', 1);
@ini_set('output_buffering', 0);
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);


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
		exit;
	}

}
# Send (download) file via pass thru
#-------------------------------------
function send_file($path, $file){

	$mainpath = "$path/$file";
	$filesize2 = sprintf("%u", filesize($mainpath));
	set_time_limit(0);

	//header("Cache-Control: ");# leave blank to avoid IE errors
	//header("Pragma: ");# leave blank to avoid IE errors
	//header("Content-type: application/octet-stream");
	//header("Content-type: application/exe");

	$handle = @fopen($mainpath,"rb");
	if ($handle) {
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Transfer-Encoding: binary");
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=\"".$file."\"");
		header("Content-Type: application/force-download");
		header("Content-length:".(string)($filesize2));
		while(!feof($handle)) {
			print(fread($handle, 1024*8));
			flush_now();
			if (connection_status()!=0) {
				@fclose($handle);
				die();
			}
		}
		@fclose($handle);
	} else {
		print "<p><center><font class=\"changed\">ERROR - Invalid Request (Downloadable file Missing or Unreadable)</font></center><br><br>";
	}
	return;
}

function flush_now() {
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	return true;
}
?>