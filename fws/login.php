<?php
global $txt;
$lostlogin = 0;
if (!empty($_GET['lostlogin'])) {
	$lostlogin=$_GET['lostlogin'];
}
$email = "";
if (!empty($_POST['email'])) {
	$email=$_POST['email'];
}
if ($email == "") { $email = "--"; }


if ($lostlogin == 0) {

	if ($_POST['loginname'] == NULL) {
		if (!ZING_LIVE) {
			?>
<html>
<head>
<META HTTP-EQUIV="Refresh" CONTENT="5; URL=index.php?page=my">
</head>
<body>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<h4><?php echo $txt['login1'] ?> <a href="index.php?page=my"><?php echo $txt['login2'] ?></a></h4>
</body>
</html>
			<?php
			exit;
		} else {
			//header('Location:index.php?'.$pagetoload);
			echo $txt['login1'];
			global $saasRet;
			$saasRet['status']='loginfailed';
			$saasRet['redirect']=$_REQUEST['pagetoload'];
		}
	}
	$post_name = $_POST['loginname'];
	$post_pass = $_POST['pass'];
	if ((empty($post_pass)) || ($post_pass == "")) { $post_pass = "no_password_is_never_valid!"; }

	$query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE (`LOGINNAME`=%s OR `EMAIL`=%s) AND `PASSWORD`=%s", quote_smart($post_name), quote_smart($post_name), quote_smart(md5($post_pass)));
	$sql = mysql_query($query) or die(mysql_error());
	$count = mysql_num_rows($sql);
	while ($row = mysql_fetch_row($sql)) {
		$id = $row[0];
		$name = $row[1];
		$pass = $row[2];
		$group = $row[13];
	}

	if ($count == 1) // one customer found, ok
	{
		// if a cookie already exists, then the user was logged in as a guest. so let's check if he has stuff in his cart
		wsGuestToCustomer($id);

		$cookie_data = $name.'#'.$id.'#'.md5($pass); //name userid and encrypted password
			
		// store IP
		$query = "UPDATE `".$dbtablesprefix."customer` SET `IP` = '".GetUserIP()."' WHERE `ID`=".$id;
		$sql = mysql_query($query) or die(mysql_error());
		// make acccesslog entry
		$query = sprintf("INSERT INTO ".$dbtablesprefix."accesslog (login, time, succeeded) VALUES(%s, '".date("F j, Y, g:i a")."', '1')", quote_smart($_POST['loginname']));
		$sql = mysql_query($query) or die(mysql_error());
			
		if(setcookie ("fws_cust",$cookie_data, 0, '/')==TRUE) //time()+3600
		{
			if (!empty($_POST['pagetoload'])) {
				$pagetoload=$_POST['pagetoload'];
			}
			elseif ($group == "ADMIN" && $pagetoload = "page=my") {
				$pagetoload = "page=admin";
			}
			else { $pagetoload = "page=my"; }

			if (!ZING_LIVE) {
				header('Location:index.php?'.$pagetoload);
				die();
			} else {
				echo $txt['login3'];
				global $saasRet;
				$saasRet['status']='loginsuccess';
				$saasRet['redirect']=$pagetoload;
			}
		}
	}
	else
	{
		$query = sprintf("INSERT INTO ".$dbtablesprefix."accesslog (login, time, succeeded) VALUES(%s, '".date("F j, Y, g:i a")."', '0')", quote_smart($_POST['loginname']));
		$sql = mysql_query($query) or die(mysql_error());
		if (ZING_LIVE) {
			echo $txt['login1'];
			global $saasRet;
			$saasRet['status']='loginfailed';
			$saasRet['redirect']=$_REQUEST['pagetoload'];
			//header('Location:'.zurl('index.php?page=my'));
			//echo $txt['login1'].'<a href="index.php?page=my">'.$txt['login2'].'</a>';
		} else {
			?>
<html>
<head>
<META HTTP-EQUIV="Refresh" CONTENT="5; URL=index.php?page=my">
</head>
<body>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<h4><?php echo $txt['login1'] ?> <a href="index.php?page=my"><?php echo $txt['login2'] ?></a></h4>
</body>
</html>
			<?php
			exit;
		}
	}
}
if ($lostlogin == 1) {
	?>
<table width="60%" class="datatable">
	<caption><?php echo $txt['checklogin8'] ?></caption>
	<tr>
		<td><?php echo $txt['checklogin9'] ?><br />
		<br />
		<form method="POST" action="?page=login&lostlogin=2">
		<div style="text-align: center;"><input type="text" name="email" size="30"> <input type="submit"
			value="<?php echo $txt['checklogin10'] ?>" name="sub"
		></div>
		</form>
	
	</tr>
</table>
	<?php
}
if ($lostlogin == 2) {
	// set global variables if not set yet
	foreach ($zingPrompts->vars as $var) { global $$var; }
	$zingPrompts->load(true);

	// lets find the correct data in the database
	$query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE `EMAIL` = %s", quote_smart($email));

	$sql = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($sql) == 0) {
		PutWindow($gfx_dir, $txt['general12'], $txt['checklogin15'], "warning.gif", "50");
		echo "<h4><a href=\"javascript:history.go(-1)\">" . $txt['checklogin18'] . "</a></h4>";
		//exit;
	} elseif ($row = mysql_fetch_row($sql)) {
		$login = $row[1];
		// Make up a random new password, simplest case, a 5-digit number
		$pass = CreateRandomCode(6);
		// Update the database with this new password
		$query = sprintf("UPDATE `".$dbtablesprefix."customer` SET `PASSWORD` = %s WHERE `LOGINNAME` = %s", quote_smart(md5($pass)), quote_smart($login));
		$sql = mysql_query($query) or die(mysql_error());

		mymail($webmaster_mail, $email, $txt['checklogin13'], $txt['checklogin14']."<br /><br />".$txt['checklogin2'].": ".$login."<br />".$txt['checklogin3'].": ".$pass, $charset);
		PutWindow($gfx_dir, $txt['checklogin13'], $txt['checklogin12']. " " . $email, "notify.gif", "50");
	}
}
?>