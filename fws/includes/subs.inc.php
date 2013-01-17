<?php
/*  subs.inc.php
 Copyright 2006, 2007 Elmar Wenners
 Support site: http://www.chaozz.nl

 This file is part of FreeWebshop.org.

 FreeWebshop.org is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FreeWebshop.org is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 $dbtablesprefix
 */
?>
<?php
// general settings
$version = "2.2.9_R2"; // the version of this webshop
$index_refer = 1; // pages of the webshop cannot be opened if this value is unset

function CreateRandomCode($len) {
	$chars = "abcdefghijkmnpqrstuvwxyz23456789";
	srand((double)microtime()*1000000);
	$pass = '' ;
	$len++;

	for ($i=0;$i<=$len; $i++) {
		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$pass = $pass . $tmp;
	}
	return $pass;
}

function InStr($String,$Find,$CaseSensitive = false) {
	$i=0;
	while (strlen($String)>=$i) {
		unset($substring);
		if ($CaseSensitive) {
			$Find=strtolower($Find);
			$String=strtolower($String);
		}
		$substring=substr($String,$i,strlen($Find));
		if ($substring==$Find) return true;
		$i++;
	}
	return false;
}

function user_error_handler($severity, $msg, $filename="", $linenum=0) {
	Global $dbtablesprefix;
	if (is_array($msg)) $msg=print_r($msg,true);
	$query = sprintf("INSERT INTO ".$dbtablesprefix."errorlog (severity, message, filename, linenum, time) VALUES('$severity',%s,'".basename($filename)."',$linenum, '".date("Y-m-d h:i:s")."')", quote_smart($msg));
	if (basename($filename) != "lang.txt" && $severity != 8 && InStr($msg,"date()",false) == false) { $sql = mysql_query($query) or die(mysql_error()); }

	switch($severity) {
		case E_USER_NOTICE:
			break;
		case E_USER_WARNING:
			break;
		case E_USER_ERROR:
			PutWindow ("Fatal Error", $msg." in ".$filename.":".$linenum, "warning.gif", "50");
			break;
		default:
			//PutWindow ("Unknown Error", "Unknown error in ".$filename.":".$linenum, "warning.gif", "50");
			break;
	}
}

function zing_user_debug($msg) {
	global $dbtablesprefix;
	if (is_array($msg)) $msg=print_r($msg,true);
	$query = sprintf("INSERT INTO ".$dbtablesprefix."errorlog (severity, message, filename, linenum, time) VALUES('0',%s,'".__FILE__."',0, '".date("Y-m-d h:i:s")."')", quote_smart($msg));
	$sql = mysql_query($query) or die(mysql_error());
}

function directory($dir,$filters) {
	$handle=opendir($dir);
	$files=array();
	if ($filters == "all"){while(($file = readdir($handle))!==false){$files[] = $file;}}
	if ($filters != "all") {
		$filters=explode(",",$filters);
		while (($file = readdir($handle))!==false) {
			for ($f=0;$f<sizeof($filters);$f++):
			$ext = substr(strrchr($file, '.'), 1);
			if ($ext == $filters[$f]){
				$files[] = $file;
			}
			endfor;
		}
	}
	closedir($handle);
	return $files;
}

function ditchtn($arr,$thumbname) {
	foreach ($arr as $item)	{
		if (!preg_match("/^".$thumbname."/",$item)){$tmparr[]=$item;}
	}
	return $tmparr;
}

function strip_slashes($value) {
	$value = stripslashes($value);
	$value = str_replace("/", "[fw$]", $value);
	$value = str_replace(".", "[fw$]", $value);
	$value = strip_tags($value);
	return $value;
}

function wsEscapeString($value) {
	if (version_compare(phpversion(),"4.3.0")=="-1") {
		return mysql_escape_string($value);
	} else {
		return mysql_real_escape_string($value);
	}
}

function br2nl($text)
{
	$text = preg_replace('/<br\\\\s*?\\/?/i', "\\n", $text);
	return str_replace("<br />","\\n",$text);
}

function mymail($from,$to,$subject,$message,$charset)
{
	Global $use_phpmail;

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset='.$charset."\r\n";
	$headers .= 'From: '.$from.' <'.$from.'>' . "\r\n";

	if ($use_phpmail == 1) {
		mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $headers);
		return true;
	}
	elseif ($use_phpmail == 2) {
		wp_mail($to,$subject,$message, $headers);
		return true;
	}
	else {
		require_once(dirname(__FILE__).'/../addons/email/email.php');
		$email = new Email();
		$email->addRecipient($to);
		$email->setSubject($subject);
		$email->setMessage($message);
		$email->addHeader('MIME-Version', '1.0');
		$email->addHeader('Content-type', 'text/html; charset='.$charset);
		$email->addHeader('From', $from.' <'.$from.'>');
		$email->setAnnounceEmail($from);
		$email->send();
		return true;
	}
}

Function CheckBox($check) {
	// returns 1 if checkbox is checked or 0 if unchecked
	if ($check == "on") { return 1; }
	else { return 0; }
}
// parameter security. not implemented yet.
Function escape_data($data){
	$pattern='-{2,}';
	$data=eregi_replace($pattern,'',$data);
	return $data;
}
// format numbers according to settings
Function myNumberFormat ($aNumber,$format='') {
	global $number_format;
	if (!$format) $format=$number_format;
	if ($format == "1234,56") {
		$aNumber = number_format($aNumber, 2, ',', '');
	}
	if ($format == "1.234,56") {
		$aNumber = number_format($aNumber, 2, ',', '.');
	}
	if ($format == "1234.56") {
		$aNumber = number_format($aNumber, 2, '.', '');
	}
	if ($format == "1,234.56") {
		$aNumber = number_format($aNumber, 2, '.', ',');
	}
	if ($format == "1,234") {
		$aNumber = number_format($aNumber, 0, '.', ',');
	}
	if ($format == "1.234") {
		$aNumber = number_format($aNumber, 0, ',', '.');
	}
	if ($format == "1234") {
		$aNumber = number_format($aNumber, 0, '.', '');
	}
	return $aNumber;
}

Function myStringToNumber($aNumber,$format='') {
	global $number_format;
	if (!$format) $format=$number_format;
	if (in_array($format,array("1234,56","1.234,56","1.234"))) {
		$aNumber=str_replace('.','',$aNumber);
		$aNumber=str_replace(',','.',$aNumber);
	} else {
		$aNumber=str_replace(',','',$aNumber);
	}
	return $aNumber;
}

//rounding of numbers
function myNumberRounding($aNumber) {
	global $number_format;
	switch ($number_format) {
		case "1234,56":
		case "1.234,56":
		case "1234.56":
		case "1,234.56":
			$aNumber = round($aNumber,2);
			break;
		default:
			$aNumber = round($aNumber,0);
	}
	return $aNumber;
}

// is the id of an admin?
Function IsAdmin() {
	Global $dbtablesprefix,$integrator;
	
	if ($integrator->isAdmin()) return true;
	//joomla
	if (wsCurrentCmsUserIsShopAdmin()) return true;

	if (function_exists('wsLiveIsAdmin')) return wsLiveIsAdmin();
	
	if (!isset($_COOKIE['fws_cust'])) { return false; }
	$fws_cust = explode("#", $_COOKIE['fws_cust']);
	$customerid = aphpsSanitize($fws_cust[1]);
	$md5pass = aphpsSanitize($fws_cust[2]);
	if (is_null($customerid)) { return false; }
	$f_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . qs($customerid);
	if ($f_sql = mysql_query($f_query)) {
		while ($f_row = mysql_fetch_row($f_sql)) {
			if ($f_row[13] == "ADMIN" && md5($f_row[2]) == $md5pass)
			{
				if ($f_row[6] == GetUserIP()) {
					return true; }
					else {
						return false; }
			} else
			{
				return false;
			}
		}
	}
	return false;
}
// read the language folder and show the flags
Function ShowFlags($lang_dir,$gfx_dir) {
	if ($dir = @opendir($lang_dir)) {
		while (($file = readdir($dir)) !== false) {
			if ($file != "." && $file != ".." && $file != "index.php") {
				// for redirection to current page after setlang.php
				$redir = $_SERVER['argv'][0];
				if (!empty($redir)){
					$redir = str_replace("=", "**", $redir);
					$redir = str_replace("&", "$$", $redir);
				}
				//added the $redir variable to the lang link
				echo "<a href=\"setlang.php?lang=".$file."&redirect_to=".$redir."\"><img src=\"".$gfx_dir."/flags/".$file.".png\" alt=\"".$file."\" /></a>";
			}
		}
		closedir($dir);
	}
}



// print the username
Function PrintUsername($guestname) {
	if (!isset($_COOKIE['fws_cust'])) {
		echo $guestname;
	}
	else {
		$fws_cust = explode("#", $_COOKIE['fws_cust']);
		echo aphpsSanitize($fws_cust[0]);
	}
}

// if we know the category but not the group, this is how we find out
Function TheGroup($TheCat) {
	Global $dbtablesprefix;
	$g_query = "SELECT * FROM `".$dbtablesprefix."category` WHERE `ID` = ".qs($TheCat);
	$g_sql = mysql_query($g_query) or die(mysql_error());
	while ($g_row = mysql_fetch_row($g_sql)) {
		$FoundIt =  $g_row[2];
	}
	return $FoundIt;
}
// how many items in the cart?
Function CountCart($customerid) {
	Global $dbtablesprefix;
	$num_prod=0;
	$query = "SELECT * FROM `".$dbtablesprefix."basket` WHERE (CUSTOMERID=".$customerid." AND STATUS=0)";
	$sql = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_row($sql)) {
		$num_prod = $num_prod + $row[6];
	}
	return $num_prod;
}
Function CountOrders($customerid) {
	Global $dbtablesprefix;
	$num_orders=0;
	$query = "SELECT * FROM `".$dbtablesprefix."order` WHERE `STATUS`>0 AND `CUSTOMERID`=".$customerid;
	$sql = mysql_query($query) or die(mysql_error());
	$num_orders = mysql_num_rows($sql);
	return $num_orders;
}
Function CountAllOrders() {
	Global $dbtablesprefix;
	$num_tot_orders=0;
	$query = "SELECT * FROM `".$dbtablesprefix."order` WHERE `STATUS`>0";
	$sql = mysql_query($query) or die(mysql_error());
	$num_tot_orders = mysql_num_rows($sql);
	$query = "SELECT * FROM `".$dbtablesprefix."order` WHERE (`STATUS`<5 AND `STATUS`>0)"; // orders that need your attention
	$sql = mysql_query($query) or die(mysql_error());
	$num_att_orders = mysql_num_rows($sql);
	return $num_att_orders."/".$num_tot_orders;
}
Function CountCustomers($group) {
	Global $dbtablesprefix;
	$num_customers=0;
	$query = "SELECT * FROM ".$dbtablesprefix."customer WHERE (`GROUP`='".$group."')";
	$sql = mysql_query($query) or die(mysql_error());
	$num_customers = mysql_num_rows($sql);
	return $num_customers;
}
Function CountProducts() {
	Global $dbtablesprefix;
	$num_products=0;
	$query = "SELECT * FROM ".$dbtablesprefix."product";
	$sql = mysql_query($query) or die(mysql_error());
	$num_products = mysql_num_rows($sql);
	return $num_products;
}
Function StockWarning($stock_warning_level) {
	Global $dbtablesprefix;
	$num = 0;
	$query ="SELECT * FROM ".$dbtablesprefix."product WHERE STOCK < ". $stock_warning_level;
	$sql = mysql_query($query) or die(mysql_error());
	$num = mysql_num_rows($sql);
	return $num;
}
// what is the total cart amount?
Function CalculateCart($customerid) {
	// customer id from cookie
	Global $dbtablesprefix;
	$total=0;
	$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (CUSTOMERID=".$customerid." AND STATUS=0)";
	$sql = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_row($sql)) {
		$productprice = $row[3]; // the price of a product
		$subtotal = $productprice * $row[6];
		$total = $total + $subtotal;
	}
	return $total;
}
// what is the total weight of the cart ?
Function WeighCart($customerid) {
	// customer id from cookie
	Global $dbtablesprefix;
	$total=0;
	$query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (CUSTOMERID=".$customerid." AND STATUS=0)";
	$sql = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_row($sql)) {
		$query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
		$sql_details = mysql_query($query) or die(mysql_error());
		while ($row_details = mysql_fetch_row($sql_details)) {
			$subtotal = $row_details[9] * $row[6];
			$total = $total + $subtotal;
		}
	}
	return $total;
}
// general window to display misc. messages
Function PutWindow($gfx_dir,$title,$message,$picture,$width) {
	echo "<table width=\"".$width."%\" class=\"datatable\">";
	echo "<caption>".$title."</caption>";
	echo "<tr><td><img src=\"".$gfx_dir."/".$picture."\" alt=\"".$picture."\"></td>";
	echo "<td>".$message."</td></tr></table>";
	echo "<br /><br />";
}
// single window to display misc. messages
Function PutSingleWindow($title,$message,$width) {
	echo "<table width=\"".$width."%\" class=\"datatable\">";
	echo "<caption>".$title."</caption>";
	echo "<tr><td>".$message."</td></tr></table>";
	echo "<br /><br />";
}
// is the customer living in the default send country?
Function IsCustomerFromDefaultSendCountry($f_send_default_country) {
	// determine sendcosts depending on the country of origin
	Global $dbtablesprefix;
	$fws_cust = explode("#", $_COOKIE['fws_cust']);
	$customerid = aphpsSanitize($fws_cust[1]);

	$f_query="SELECT * FROM `".$dbtablesprefix."customer` WHERE `ID` = " . qs($customerid);
	$f_sql = mysql_query($f_query) or zfdbexit($f_query);
	while ($f_row = mysql_fetch_row($f_sql)) {
		$country = $f_row[14];
	}
	if ($country == $f_send_default_country) {
		return 1;
	}
	else { return 0; }
}
// split up a string, used by max_description
Function stringsplit($the_string, $the_number)  {
	$startoff_nr = 0;
	$the_output_array = array();
	for($z = 1; $z < ceil(strlen($the_string)/$the_number)+1 ; $z++) {
		$startoff_nr = ($the_number*$z)-$the_number;
		$the_output_array[] = substr($the_string, $startoff_nr, $the_number);
	}
	return($the_output_array);
}
// see if url exists (for picture on remote host as well)
function wsUrlExists($url) {
	$a_url = parse_url($url);
	if (!isset($a_url['port'])) $a_url['port'] = 80;
	$errno = 0;
	$errstr = '';
	$timeout = 5;
	if(isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host'])){
		$fid = @fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
		if (!$fid) return false;
		$page = isset($a_url['path'])  ?$a_url['path']:'';
		$page .= isset($a_url['query'])?'?'.$a_url['query']:'';
		fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
		$head = fread($fid, 4096);
		fclose($fid);
		return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
	} else {
		return false;
	}
}
// check if local or remote picture exists
function thumb_exists($thumbnail) {
	$pos = strpos($thumbnail, "://");
	if ($pos === false) {
		return file_exists($thumbnail);
	}
	else {
		return wsUrlExists($thumbnail);
	}
}
// get user IP
if (!function_exists('GetUserIP')) {
	function GetUserIP() {
		if (isset($_SERVER)) { if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{ $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; }
		elseif(isset($_SERVER["HTTP_CLIENT_IP"]))
		{ $ip = $_SERVER["HTTP_CLIENT_IP"]; }
		else { $ip = $_SERVER["REMOTE_ADDR"]; }
		}
		else { if ( getenv( 'HTTP_X_FORWARDED_FOR' ) )
		{ $ip = getenv( 'HTTP_X_FORWARDED_FOR' ); }
		elseif ( getenv( 'HTTP_CLIENT_IP' ) )
		{ $ip = getenv( 'HTTP_CLIENT_IP' ); }
		else { $ip = getenv( 'REMOTE_ADDR' ); }
		}
		return $ip;
	}
}
// trim a string
function file_trim(&$value, $key){
	$value = trim($value);
}
// check if current user is banned
function IsBanned() {
	// check ip from database
	global $dbtablesprefix;
	if (!isset($_COOKIE['fws_cust'])) { return false; }
	$fws_cust = explode("#", $_COOKIE['fws_cust']);
	$customerid = aphpsSanitize($fws_cust[1]);
	if (is_null($customerid)) { return false; }
	$f_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . qs($customerid);
	$f_sql = mysql_query($f_query) or die(mysql_error());
	if ($f_row = mysql_fetch_row($f_sql)) {
		$userip = $f_row[6];
	} else $userip='';
	// get current computers ip
	$ip = GetUserIP();

	// now check both in the banlist
	$db=new db();
	$query="select * from `##bannedip` where `ip` in (".qs($ip).",".qs($userip).")";
	if ($db->select($query)) return true;
	return false;
}
function isvalid_email_address($email) {
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);

	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
			return false;
		}
	}
	if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false;
			// Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}
function wsIsWritable($path) {
	//will work in despite of Windows ACLs bug
	//NOTE: use a trailing slash for folders!!!
	//see http://bugs.php.net/bug.php?id=27609
	//see http://bugs.php.net/bug.php?id=30931

	if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
	return wsIsWritable($path.uniqid(mt_rand()).'.tmp');
	else if (is_dir($path))
	return wsIsWritable($path.'/'.uniqid(mt_rand()).'.tmp');
	// check tmp file for read/write capabilities
	$rm = file_exists($path);
	$f = @fopen($path, 'a');
	if ($f===false)
	return false;
	fclose($f);
	if (!$rm)
	unlink($path);
	return true;
}
function gen_rand_value($num)
{
	// for random session id >> accepts 1 - 36
	switch($num)
	{
		case "1":
			$rand_value = "a";
			break;
		case "2":
			$rand_value = "b";
			break;
		case "3":
			$rand_value = "c";
			break;
		case "4":
			$rand_value = "d";
			break;
		case "5":
			$rand_value = "e";
			break;
		case "6":
			$rand_value = "f";
			break;
		case "7":
			$rand_value = "g";
			break;
		case "8":
			$rand_value = "h";
			break;
		case "9":
			$rand_value = "i";
			break;
		case "10":
			$rand_value = "j";
			break;
		case "11":
			$rand_value = "k";
			break;
		case "12":
			$rand_value = "l";
			break;
		case "13":
			$rand_value = "m";
			break;
		case "14":
			$rand_value = "n";
			break;
		case "15":
			$rand_value = "o";
			break;
		case "16":
			$rand_value = "p";
			break;
		case "17":
			$rand_value = "q";
			break;
		case "18":
			$rand_value = "r";
			break;
		case "19":
			$rand_value = "s";
			break;
		case "20":
			$rand_value = "t";
			break;
		case "21":
			$rand_value = "u";
			break;
		case "22":
			$rand_value = "v";
			break;
		case "23":
			$rand_value = "w";
			break;
		case "24":
			$rand_value = "x";
			break;
		case "25":
			$rand_value = "y";
			break;
		case "26":
			$rand_value = "z";
			break;
		case "27":
			$rand_value = "1"; // no zeros, because if it starts with a zero, it might get cut off
			break;
		case "28":
			$rand_value = "1";
			break;
		case "29":
			$rand_value = "2";
			break;
		case "30":
			$rand_value = "3";
			break;
		case "31":
			$rand_value = "4";
			break;
		case "32":
			$rand_value = "5";
			break;
		case "33":
			$rand_value = "6";
			break;
		case "34":
			$rand_value = "7";
			break;
		case "35":
			$rand_value = "8";
			break;
		case "36":
			$rand_value = "9";
			break;
	}
	return $rand_value;
}

// new functions
function zing_query_db($query) {
	if ($sql=mysql_query($query)) return $sql;
	zfdbexit($query);
}
function zfdbexit($query="") {
	global $gfx_dir, $txt, $page;
	echo 'info='.mysql_info();
	$error="<strong>A database error occured, please contact the Zingiri support team with the details of this error:</strong><br /><br />";
	$error.="Query: ".$query."<br /><br />";
	$error.="Error: ".mysql_error()."<br />";
	$pageURL = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
	$pageURL .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$error.="URL:   ".$pageURL."<br />";
	$error.="Page:  ".$page."<br />";
	PutWindow($gfx_dir, $txt['general12'], $error, "warning.gif", "100");
	die();
}

// Are there any active discounts? Otherwise skip discount screen
Function ActiveDiscounts() {
	$discount=new wsDiscount();
	return $discount->countActive();
}



function similarProducts($productid,$catid) {
	global $dbtablesprefix;
	$s=array();
	if (wsExtension('ml')) $query=wsMultiLingualQuery('similar');
	else $query = sprintf("SELECT `ID`,`CATID` FROM `".$dbtablesprefix."product` where `PRODUCTID`=%s AND `CATID`!=%s", quote_smart($productid),quote_smart($catid));
	$sql = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_array($sql)) {
		$query = sprintf("SELECT `DESC` FROM `".$dbtablesprefix."category` where `ID`=%s", quote_smart($row['CATID']));
		$sqlcat = mysql_query($query) or die(mysql_error());
		$rowcat = mysql_fetch_array($sqlcat);
		$s[$row['ID']]=$rowcat['DESC'].' - '.$productid;
	}
	return $s;

}

function faces_group() {
	global $dbtablesprefix;
	global $customerid;

	$group="";
	if (wsCurrentCmsUserIsShopAdmin()) $group='ADMIN';
	elseif (function_exists('wsLiveIsAdmin') && wsLiveIsAdmin()) $group='ADMIN';
	elseif (LoggedIn() && $customerid) {
		$f_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . qs($customerid);
		$f_sql = mysql_query($f_query) or die(mysql_error());
		if ($f_row = mysql_fetch_array($f_sql)) {
			$group=$f_row['GROUP'];
		}
	} else $group="GUEST";
	return $group;
}

function getCustomerByLogin($login) {
	global $dbtablesprefix;
	$query = "SELECT ID FROM ".$dbtablesprefix."customer WHERE LOGINNAME = " . qs($login);
	if ($sql = mysql_query($query)) {
		if ($row = mysql_fetch_array($sql)) return $row['ID'];
	}
	return false;
}

function printDescription($productid,$description,$shortDescription) {
	global $max_description,$pricelist_format;

	if ($pricelist_format == 0) { $print_description = $productid; }
	if ($pricelist_format == 1) { $print_description = $description; }
	if ($pricelist_format == 2) { $print_description = $productid." - ".$description; }
	if ($pricelist_format == 3) { $print_description = $productid."<br />".$description; }
	if ($max_description != 0 && ($pricelist_format <= 2)) {
		$description = stringsplit($print_description, $max_description); // so lets only show the first xx characters
		if (strlen($print_description) != strlen($description[0])) { $description[0] = $description[0] . ".."; }
		$print_description = $description[0];
		$print_description = strip_tags($print_description); //remove html because of danger of broken tags
	}
	if ($pricelist_format == 4) { $print_description = strip_tags($shortDescription); }
	if ($pricelist_format == 5) { $print_description = $productid."<br />".strip_tags($shortDescription); }
	return $print_description;
}

function customerId($e='') {
	//global $customerid;
	//return $customerid;
	return wsCid();
}

function wsComments($text) {
	return '<a href=# class=info>(?)<span>'.$text.'</span></a>';
}

function AllowAccess($zfaces,$formid="",$action="") {
	Global $dbtablesprefix;

	if (ZingAppsIsAdmin()) $group="ADMIN";
	elseif (function_exists('faces_group')) $group=faces_group();
	else $group="GUEST";
	switch ($zfaces)
	{
		case "form":
		case "list":
			$role=new db();
			$query="select ##faccess.id from ##frole,##faccess where ##faccess.roleid=##frole.id and ##frole.name=".qs($group)." and (##faccess.formid=0 OR ##faccess.formid=".zfqs($formid).")";
			if ($role->select($query)) return true;
			break;
		case "edit":
		case "summary":
		case "face":
			if (ZingAppsIsAdmin()) return true;
			break;
	}
	echo "You don't have access to this form";
	return false;
}

function faces_map($a) {
	$q='';
	foreach ($a as $id => $value) {
		if (!$q) $q='{';
		else $q.=',';
		$q.="'".$id."':'".$value."'";
	}
	$q.='}';
	return urlencode($q);
}


function zing_carousel() {
	//not used
}

function wsCurrentPageURL($encode=false)
{
	if (isset($_SERVER['HTTPS'])) $pageURL = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
	else $pageURL = 'http://';
	$pageURL .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	if ($encode) return urlencode($pageURL);
	else return $pageURL;
}

if (!function_exists('actionCompleteMessage')) {
	function actionCompleteMessage() {
		global $gfx_dir,$txt;
		$msg='';
		if (isset($_REQUEST['zmsg']) && $_REQUEST['zmsg']) {
			$title=$txt['general13'];
			$message=$txt['adminedit2'];
			$picture="notify.gif";
			$msg ="<table width=\"100%\" class=\"datatable\">";
			$msg.="<tr><td><img src=\"".$gfx_dir."/".$picture."\" alt=\"".$picture."\" height=\"24px\">";
			$msg.='<strong>'.$message.'</strong>'."</td></tr></table>";
			$msg.="<br /><br />";
		}
		return $msg;
	}
}

function wsExtension($ext) {
	if (get_option('zing_ws_extension_'.$ext)) return true;
	else return false;
}


function wsDownloadCheckSum($customerid,$basketid) {
	$s='zingiri--web--shop';
	return md5($s.md5($s.$customerid.$basketid));
}

function base64url_encode($data) {
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
} 

?>