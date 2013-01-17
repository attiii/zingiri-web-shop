<?php if ($index_refer <> 1) { exit(); } ?>
<?php
ob_start();
if (LoggedIn() == false) {
	$pagetoload = 'page='.$_REQUEST['page'];
	echo '<div>';

	if ($integrator->wpCustomer) { ?>
<div id="wslogin">
	<p>
	<?php echo $txt['checklogin1'] ?>
	</p>
	<form name="loginform" id="loginform" action="<?php echo get_option('siteurl');?>/wp-login.php"
		method="post"
	>
		<table class="borderless" width="100%">
			<tr>
				<td><?php echo $txt['checklogin2'] ?></td>
				<td><input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" />
				</td>
			</tr>
			<tr>
				<td><?php echo $txt['checklogin3'] ?></td>
				<td><input type="password" name="pwd" id="user_pass" class="input" value="" size="20"
					tabindex="20"
				/></td>
			</tr>
		</table>
		<div style="text-align: center;">
			<input type="submit" name="wp-submit" id="wp-submit" class="button-primary"
				value="<?php echo $txt['checklogin4'] ?>" tabindex="100"
			/>
		</div>
		<input type="hidden" name="redirect_to"
			value="<?php echo urlencode(zurl(wsHomePage($pagetoload),true));?>"
		/>  
		<input type="hidden" name="testcookie" value="1" />
	</form>
	<div style="text-align: center">
		<a href="<?php zurl(get_option('siteurl')."/wp-login.php?action=lostpassword",true)?>"><?php echo $txt['checklogin11'] ?>
		</a>
	</div>
</div>

	<?php } else {
		?>
<div id="wslogin">
	<p>
	<?php echo $txt['checklogin1'] ?>
	</p>
	<?php if (defined("ZING")) { ?>
	<form name="login" method="POST" action="<?php echo zurl('index.php');?>">
		<input type="hidden" value="login" name="page">
		<?php } else { ?>
		<form name="login" method="POST" action="login.php">
		<?php } ?>
			<input type="hidden" value="<?php echo $pagetoload; ?>" name="pagetoload">
			<table class="borderless" width="100%">
				<tr>
					<td class="borderless"><?php echo $txt['checklogin2'] ?></td>
					<td class="borderless"><input type="text" name="loginname" size="20"></td>
				</tr>
				<tr>
					<td class="borderless"><?php echo $txt['checklogin3'] ?></td>
					<td class="borderless"><input type="password" name="pass" size="20"></td>
				</tr>
			</table>
			<div style="text-align: center;">
				<input type="submit" value="<?php echo $txt['checklogin4'] ?>" name="sub">
			</div>
			<div style="text-align: center;">
				<a href="<?php zurl('?page=login&lostlogin=1',true);?>"><?php echo $txt['checklogin11'] ?> </a>
			</div>
		</form>

</div>


		<?php
	}
	?>
	<?php if (!wsSetting('require_registration')) {?>
<div id="wsnoregistration">
	<a
		href="<?php zurl("index.php?page=customer&action=new&registration=0&pagetoload=".base64url_encode($pagetoload),true);?>"
	><?php echo $txt['checkout106'] ?> </a>
</div>
	<?php }?>

<div id="wsregister">
	<a
		href="<?php zurl("index.php?page=customer&action=new&registration=1&pagetoload=".base64url_encode($pagetoload),true);?>"
	><?php echo $txt['checklogin5'] ?> </a>
</div>

	<?php

	echo '</div>';
	echo '<div id="wsloginwelcome">';
	PutWindow($gfx_dir, $txt['checklogin6'], $txt['checklogin7'], "personal.jpg", "90");
	echo '</div>';
}
$checkLogin=ob_get_clean();
//ob_end_clean();
$aphps->doAction('loginform',$checkLogin);
echo $checkLogin;
