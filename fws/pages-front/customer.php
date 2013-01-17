<?php if ($index_refer <> 1) { exit(); } ?>
<?php
if (!empty($_GET['pagetoload'])) {
	$pagetoload=$_GET['pagetoload'];
} else $pagetoload='';

require_once(ZING_APPS_PLAYER_DIR."includes/all.inc.php");
$custForm=appsForm('register','add',"?page=customer&action=add&step=save&registration=".(isset($_GET['registration']) ? intval($_GET['registration']) : 0)."&pagetoload=".$pagetoload,true);

if ($action=="add" && isset($_GET['step']) && $_GET['step']=="save" && $custForm->success && $custForm->showform=="saved") {
	foreach ($zingPrompts->vars as $var) { global $$var; }
	$registration=wsSetting('require_registration') ? 1 : intval($_GET['registration']);
	$email=$custForm->recValue('EMAIL');
	if (wsSetting('login_with_email')) $login=$email;
	else $login=$custForm->recValue('LOGINNAME');
	$tussenvoegsels=$initials=$custForm->rec['INITIALS'];
	$naam=$surname=$custForm->rec['LASTNAME'];
	if (wsSetting('generate_password')) $pass1=CreateRandomCode(12);
	else $pass1=$custForm->searchByFieldName('PASSWORD');
	if ($registration) { //registration
		if (LoggedIn() == false) {
			$zingPrompts->load(true);
			$newcustomerid=$custForm->recid;
			if ($integrator->wpCustomer) {
				//suppress hook from firing
				if (ZING_CMS=='wp') remove_action('user_register','zing_profile');
				$integrator->createWpUser(array('user_login'=>$login,'first_name'=>$initials,'last_name'=>$surname,'user_email'=>$email,'user_pass'=>$pass1),'subscriber');
				$integrator->loginWpUser($login,$pass1);
			}
			mymail($webmaster_mail, $webmaster_mail, $txt['customer36'], $txt['customer37']."<br /><br />".str_replace($pass1,'********',$txt['customer12']), $charset);
			mymail($webmaster_mail, $email, $txt['customer11'], $txt['customer12'], $charset);
			PutWindow($gfx_dir, $txt['general13'], $txt['customer13'], "notify.gif", "50"); // succesfully saved
			setcookie ("fws_guest", "", time() - 3600, '/');
			$cookie_data = $login.'#'.$newcustomerid.'#'.md5(md5($pass1)); //name userid and encrypted password
			setcookie ("fws_cust",$cookie_data, 0, '/')==TRUE;
			$update_query = "UPDATE `".$dbtablesprefix."customer` SET `IP` = '".GetUserIP()."',`GROUP` = 'CUSTOMER' WHERE `ID` = '".$newcustomerid."'";
			$update_sql = mysql_query($update_query) or die(mysql_error());
			$update_query = "UPDATE `".$dbtablesprefix."basket` SET `CUSTOMERID` = ".$newcustomerid." WHERE STATUS = 0 AND CUSTOMERID = '".$customerid."'";
			$update_sql = mysql_query($update_query) or die(mysql_error());
			if (wsSetting('generate_password')) {
				$update_query = "UPDATE `".$dbtablesprefix."customer` SET `PASSWORD` = ".qs(md5($pass1))." WHERE `ID` = '".$newcustomerid."'";
				$update_sql = mysql_query($update_query) or die(mysql_error());
				
			}
			if (!$pagetoload) $pagetoload='page=my';
			else $pagetoload=base64url_decode($pagetoload);
			header('Location: '.zurl('index.php?'.$pagetoload));
			die();
		}
		else {
			// update existing customer
			if ($integrator->wpCustomer) {
				$integrator->updateWpUser(array('user_pass'=>$pass1,'user_login'=>$login,'first_name'=>$initials,'last_name'=>$surname,'user_email'=>$email));
			}
			PutWindow($gfx_dir, $txt['general13'], $txt['customer13'], "notify.gif", "50"); // succesfully saved
		}
	} else { //no registration
			$login=$email;
			$newcustomerid=$custForm->recid;
			$pass1=CreateRandomCode(15);
			setcookie ("fws_guest", "", time() - 3600, '/');
			$cookie_data = $login.'#'.$newcustomerid.'#'.md5(md5($pass1)); //name userid and encrypted password
			setcookie ("fws_cust",$cookie_data, 0, '/')==TRUE;
			$update_query = "UPDATE `".$dbtablesprefix."customer` SET `IP` = '".GetUserIP()."',`GROUP` = 'GUEST' WHERE `ID` = '".$newcustomerid."'";
			$update_sql = mysql_query($update_query) or die(mysql_error());
			$update_query = "UPDATE `".$dbtablesprefix."basket` SET `CUSTOMERID` = ".$newcustomerid." WHERE STATUS = 0 AND CUSTOMERID = '".$customerid."'";
			$update_sql = mysql_query($update_query) or die(mysql_error());
			if (!$pagetoload) $pagetoload='page=checkout1';
			else $pagetoload=base64url_decode($pagetoload);
			header('Location: '.zurl('index.php?'.$pagetoload));
			die();
	}
}
?>