<?php
/*  conditions.php
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
<?php
if ($action == "checkout") { include (dirname(__FILE__)."/includes/checklogin.inc.php"); }
?>
<?php
if (LoggedIn() == False && $action == "checkout") {
	// do nothing
}
else {
	$count = CountCart($customerid);
	if ($count == 0 && $action == "checkout") {
		PutWindow($gfx_dir, $txt['cart1'], $txt['cart2'], "carticon.gif", "50");
	}
	else {
		if ($action == "checkout") CheckoutNextStep();
		if ($action == "checkout" && LoggedIn() == True) {
			CheckoutShowProgress();
			//echo "<h4><img src=\"".$gfx_dir."/arrow.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/1.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/2_.gif\" alt=\"2\">&nbsp;<img src=\"".$gfx_dir."/3_.gif\" alt=\"3\">&nbsp;<img src=\"".$gfx_dir."/4_.gif\" alt=\"4\">&nbsp;<img src=\"".$gfx_dir."/5_.gif\" alt=\"5\"></h4><br /><br />";
		}

		// read the conditions file
		$conditions=$zingPrompts->get('conditions');
		
		?>
<table class="borderless" width="100%">
	<tr>
		<td>
		<?php 
		if ($action=="checkout") {
		?>
		<form method="post" action="?page=shipping"><textarea
			rows="30" cols="65" readonly><?php echo $conditions ?></textarea><br />
			<?php
	  if ($count != 0 && $ordering_enabled == 1) {
		  echo "<input type=\"submit\" value=\"" . $txt['conditions1'] . "\"><br />";
	  }
	  ?>
	  </form>
	  <?php } else {?>
		<textarea
			rows="30" cols="65" readonly><?php echo $conditions ?></textarea><br />
<a href="javascript:history.go(-1)" class="button">
			<?php  echo $txt['general14']; ?>
	  </a>
	  <?php }?>
		</td>
	</tr>
</table>
	  <?php
	  if (IsAdmin() == true && $action == "show") { echo "<h4><a href=\"?page=adminedit&filename=conditions.txt&root=0&wysiwyg=0\">".$txt['browse7']."</a></h4>"; }
	}
}
?>