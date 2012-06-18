<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$fh=$_REQUEST['fh'];
$error='';
if (isset($_REQUEST['action']) && ($_REQUEST['action']=='save') && isset($_FILES['fh'])) {
	$target=APHPS_TEMP_DIR.$fh.'/'.$_FILES['fh']['name'];
	if (!file_exists(APHPS_TEMP_DIR.$fh)) mkdir(APHPS_TEMP_DIR.$fh);
	if(move_uploaded_file($_FILES['fh']['tmp_name'], $target)) {
		$fhname[]=$_FILES['fh']['name'];
		$link=APHPS_TEMP_DIR.$fh.'/'.$fh;
		if (file_exists($link)) unlink($link);
		symlink ($target,$link);
	} else {
		$error='This file can\'t be uploaded';
	}
} else {
	$link=APHPS_TEMP_DIR.$fh.'/'.$fh;
	if (file_exists($link)) $fhname[]=basename(readlink($link));
}
?>
<html>
<head>
  <script type="text/javascript" src="http://form.clientcentral.info/js/min/jquery.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#fh').bind('change',this,function() {
		var form=jQuery('#fhform');
		form.submit();
	});
});
</script>
</head>
<body>
	<form id="fhform" enctype="multipart/form-data" method="post" action="load.php?action=save&fh=<?php echo $fh?>" />
	<input id="fh" type="file" name="fh" value="fhname"/>
	<div id="fhfile">
	<?php
	if (isset($fhname)) {
		foreach ($fhname as $name) {
			echo $name;			
		}
	} elseif ($error) {
		echo $error;
	}
	?>
	</div>
	</form>
</body>
</html>
