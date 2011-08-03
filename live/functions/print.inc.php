<?php
function zing_ws_print($title,$message) {
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>">
<title><?php echo $title ?></title>
</head>
<body onLoad="javascript:window.print()">
<?php echo $message; ?>
</body>
</html>

<?php }?>