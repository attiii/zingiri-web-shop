<?php
/*  checklogin.php
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

*/
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
	//Check if cookie is set
	if (LoggedIn() == false) {
		$pagetoload = $_SERVER['QUERY_STRING'];
		if ($integrator->wpCustomer) { ?>
		  <table width="60%" class="datatable">
		    <caption><?php echo $txt['checklogin1'] ?></caption>
		    <tr><td>
<form name="loginform" id="loginform" action="<?php echo get_option('siteurl');?>/wp-login.php" method="post">
<table class="borderless" width="100%">
	<tr>
		<td><?php echo $txt['checklogin2'] ?></td>
		<td><input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" /></td>
	</tr>
	<tr>
		<td><?php echo $txt['checklogin3'] ?></td>
		<td><input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></td>
	</tr>
	</table>
	<!--<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> Remember Me</label></p> -->
	<p class="submit">
		<div style="text-align:center;"><input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="<?php echo $txt['checklogin4'] ?>" tabindex="100" /></div>
		<input type="hidden" name="redirect_to" value="<?php zurl(get_option('home').'/index.php?'.$pagetoload,true);?>" />
		<input type="hidden" name="testcookie" value="1" />
	</p>
<br />	
</form>
</td></tr></table>		
		  <div style="position:relative;float:left;width:50%;text-align:left;"><a href="<?php zurl("index.php?page=customer&action=new&pagetoload=".urlencode($pagetoload),true);?>"><?php echo $txt['checklogin5'] ?></a></div>
          <div style="position:relative;float:left;width:50%;text-align:right;"><a href="<?php zurl(get_option('siteurl')."/wp-login.php?action=lostpassword",true)?>"><?php echo $txt['checklogin11'] ?></a></div>
		<?php } else {
        ?>
		  <table width="60%" class="datatable">
		    <caption><?php echo $txt['checklogin1'] ?></caption>
		    <tr><td>
		    <?php if (defined("ZING")) { ?>
		        <form name="login" method="POST" action="<?php echo zurl('index.php',true);?>">
		              <input type="hidden" value="login" name="page">
            <?php } else { ?>
   		        <form name="login" method="POST" action="login.php">
	        <?php } ?>
		              <input type="hidden" value="<?php echo $pagetoload; ?>" name="pagetoload">
			          <table class="borderless" width="100%">
			                 <tr><td class="borderless"><?php echo $txt['checklogin2'] ?></td>
			                     <td class="borderless"><input type="text" name="loginname" size="20"></td>
			                 </tr>
			                 <tr><td class="borderless"><?php echo $txt['checklogin3'] ?></td>
			                     <td class="borderless"><input type="password" name="pass" size="20"></td>
			                 </tr>
			          </table>
			          <br />
			          <div style="text-align:center;"><input type="submit" value="<?php echo $txt['checklogin4'] ?>" name="sub"></div>
			          <br />
			          <div style="text-align:right;"><a href="<?php zurl('?page=login&lostlogin=1',true);?>"><?php echo $txt['checklogin11'] ?></a></div>
		  	    </form>
		  	    </td>
		  	</tr>
		  </table>
		  <br />
		  <div style="text-align:center;"><a href="<?php zurl("index.php?page=customer&action=new&pagetoload=".urlencode($pagetoload),true);?>"><?php echo $txt['checklogin5'] ?></a></div>
	 <?php
		}
		echo '<br /><br /><br />';
		PutWindow($gfx_dir, $txt['checklogin6'], $txt['checklogin7'], "personal.jpg", "90");
	}
?>