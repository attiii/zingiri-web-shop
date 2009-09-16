<?php
/*  discount.php
    Copyright 2006, 2007, 2008 Elmar Wenners
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
<?php include (ZING_SUB."./includes/checklogin.inc.php"); ?>
<?php
    if (!empty($_POST['shippingid'])) 		{ $shippingid	= intval($_POST['shippingid']); }
    if (!empty($_POST['weightid'])) 		{ $weightid		= intval($_POST['weightid']); }
    if (!empty($_POST['paymentid'])) 		{ $paymentid	= intval($_POST['paymentid']); }
    if (!empty($_POST['notes']))    		{ $notes		= $_POST['notes']; } else { $notes = ""; }
?>
<?php
if (LoggedIn() == True) {
	$error = 0;
	echo "<h4><img src=\"".$gfx_dir."/1_.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/2_.gif\" alt=\"step 2\">&nbsp;<img src=\"".$gfx_dir."/3_.gif\" alt=\"3\">&nbsp;<img src=\"".$gfx_dir."/arrow.gif\" alt=\"arrow\">&nbsp;<img src=\"".$gfx_dir."/4.gif\" alt=\"4\">&nbsp;<img src=\"".$gfx_dir."/5_.gif\" alt=\"5\"></h4><br /><br />";
	
	// if the cart is empty, then you shouldn't be here
	if (CountCart($customerid) == 0) {
		PutWindow($gfx_dir, $txt['general12'], $txt['checkout2'], "warning.gif", "50");
		$error = 1;
	}
	if ($error == 0) {
		echo '<table width="100%" class="datatable">
				<caption>'.$txt['shipping4'].'</caption>
				<tr><td>'.$txt['shipping5'].'
					<form method="post" action="index.php?page=checkout">
						<input type="hidden" name="shippingid" value="'.$shippingid.'">
						<input type="hidden" name="weightid" value="'.$weightid.'">
						<input type="hidden" name="paymentid" value="'.$paymentid.'">
						<input type="hidden" name="notes" value="'.$notes.'">
						<input type="text" name="discount_code" value=""><br />
						<br />
						<div style="text-align:center;"><input type=submit value="'.$txt['shipping9'].' >>"></div>
					</form>
				</td></tr>
			</table>';
	}
}
?>     