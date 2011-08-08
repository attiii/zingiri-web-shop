<?php
$f=$_REQUEST['file'];
echo '?>';
echo $f;
if (!readfile($f)) {
	echo '<?php die("File not found"); ?>';
} 