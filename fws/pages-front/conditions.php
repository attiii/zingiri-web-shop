<?php if ($index_refer <> 1) { exit(); } ?>
<?php
if ($action == "checkout") { include (ZING_DIR."/includes/checklogin.inc.php"); }
?>
<?php
if (LoggedIn() == False && $action == "checkout") {
	// do nothing
}
else {
	$count = CountCart(wsCid());
	if ($count == 0 && $action == "checkout") {
		PutWindow($gfx_dir, $txt['cart1'], $txt['cart2'], "carticon.gif", "50");
	}
	else {
		if ($action == "checkout") CheckoutNextStep();
		if ($action == "checkout" && LoggedIn() == True) {
			CheckoutShowProgress();
		}

		// read the conditions file
		$conditions=$zingPrompts->get('conditions');
		
		?>
<table class="borderless" width="100%">
<caption><?php 	  if (IsAdmin() == true && $action == "show") { echo "[<a href=\"".zurladmin('?page=prompts&zfaces=list&form=prompt&action=search&element_4_1=conditions')."\">".$txt['browse7']."</a>]"; }
?>
</caption>
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
	}
}
?>