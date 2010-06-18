<?php
/*  customer.php
 Copyright 2008,2009,2010 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Web Shop.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Zingiri Web Shop; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
if (!empty($_GET['pagetoload'])) {
	$pagetoload=$_GET['pagetoload'];
}
require_once(ZING_APPS_PLAYER_DIR."includes/all.inc.php");
$custForm=appsForm('register','add',"?page=customer&action=add&step=save&pagetoload=".urlencode($pagetoload),true);

if ($action=="add" && $_GET['step']=="save" && $custForm->success && $custForm->showform=="saved") {
	foreach ($zingPrompts->vars as $var) { global $$var; }
	$login=$custForm->rec['LOGINNAME'];
	$tussenvoegsels=$initials=$custForm->rec['INITIALS'];
	$naam=$surname=$custForm->rec['LASTNAME'];
	$email=$custForm->rec['EMAIL'];
	$pass1=$custForm->searchByFieldName('PASSWORD');
	if (LoggedIn() == false) {
		$zingPrompts->load(true);
		$newcustomerid=$custForm->recid;
		if ($integrator->wpCustomer) {
			$integrator->createWpUser(array('user_login'=>$login,'first_name'=>$initials,'last_name'=>$surname,'user_email'=>$email),'subscriber');
			$integrator->loginWpUser($login,$pass1);
		}
		mymail($webmaster_mail, $webmaster_mail, $txt['customer36'], $txt['customer37']."<br /><br />".$txt['customer12'], $charset);
		mymail($webmaster_mail, $email, $txt['customer11'], $txt['customer12'], $charset);
		PutWindow($gfx_dir, $txt['general13'], $txt['customer13'], "notify.gif", "50"); // succesfully saved
		setcookie ("fws_guest", "", time() - 3600, '/');
		$cookie_data = $login.'-'.$newcustomerid.'-'.md5(md5($pass1)); //name userid and encrypted password
		setcookie ("fws_cust",$cookie_data, 0, '/')==TRUE;
		$update_query = "UPDATE `".$dbtablesprefix."customer` SET `IP` = '".GetUserIP()."' WHERE `ID` = '".$newcustomerid."'";
		$update_sql = mysql_query($update_query) or die(mysql_error());
		$update_query = "UPDATE `".$dbtablesprefix."basket` SET `CUSTOMERID` = ".$newcustomerid." WHERE STATUS = 0 AND CUSTOMERID = '".$customerid."'";
		$update_sql = mysql_query($update_query) or die(mysql_error());
		if (!$pagetoload) $pagetoload='page=my';
		header('Location: '.ZING_HOME.'/index.php?'.$pagetoload);
	}
	else {
		// update existing customer
		if ($integrator->wpCustomer) {
			$integrator->updateWpUser(array('user_pass'=>$pass1,'user_login'=>$login,'first_name'=>$initials,'last_name'=>$surname,'user_email'=>$email));
		}
		PutWindow($gfx_dir, $txt['general13'], $txt['customer13'], "notify.gif", "50"); // succesfully saved
	}
}
?>