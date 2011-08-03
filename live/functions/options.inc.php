<?php
function zing_options($zing_ws_options) {
	?>
<table class="optiontable">

<?php if ($zing_ws_options) foreach ($zing_ws_options as $value) {

	if ($value['type'] == "text") { ?>

	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
			type="<?php echo $value['type']; ?>"
			value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>"
			size="80"
		/></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small> <br />
		<br />
		</td>
	</tr>

	<?php } elseif ($value['type'] == "hidden") { ?>

	<tr align="left" style="display:none">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
			type="<?php echo $value['type']; ?>"
			value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>"
			size="80"
		/></td>

	</tr>
	<tr style="display:none">
		<td colspan=2><small><?php echo $value['desc']; ?> </small> <br />
		<br />
		</td>
	</tr>

	<?php } elseif ($value['type'] == "textarea") { ?>
	<tr align="left">
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td><textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="50"
			rows="8"
		/>
		<?php if ( get_option( $value['id'] ) != "") { echo stripslashes (get_option( $value['id'] )); }
		else { echo $value['std'];
		} ?>
</textarea></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small>
		<hr />
		</td>
	</tr>

	<?php } elseif ($value['type'] == "select") { ?>

	<tr align="left">
		<th scope="top"><?php echo $value['name']; ?>:</th>
		<td><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
		<?php foreach ($value['options'] as $option => $label) { ?>
			<option value="<?php echo $option?>"
			<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; }?>
			><?php echo $label; ?></option>
			<?php } ?>
		</select></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small></td>
	</tr>

	<?php } elseif ($value['type'] == "selectwithkey") { ?>

	<tr align="left">
		<th scope="top"><?php echo $value['name']; ?>:</th>
		<td><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
		<?php foreach ($value['options'] as $key => $option) { ?>
			<option value="<?php echo $key;?>"
			<?php if ( get_option( $value['id'] ) == $key) { echo ' selected="selected"'; }?>
			><?php echo $option; ?></option>
			<?php } ?>
		</select></td>

	</tr>
	<tr>
		<td colspan=2><small><?php echo $value['desc']; ?> </small></td>
	</tr>

	<?php } elseif ($value['type'] == "heading") { ?>
	<tr valign="top">
		<td colspan="2" style="text-align: left;">
		<h3 class="title"><?php echo $value['name']; ?></h3>
		</td>
	</tr>
	<tr>
		<td colspan=2><small>
		<p style="color: red; margin: 0 0;"><?php echo $value['desc']; ?></p>
		</small></td>
	</tr>

	<?php } ?>
	<?php
}
?>
</table>
<?php }
?>