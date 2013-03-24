<script type="text/javascript">
jQuery(document).ready(function() {
	var $jx = jQuery.noConflict(); 
	$jx(function() {
 	$jx(".mygallery").jCarouselLite({
 		btnNext: ".next",
        btnPrev: ".prev",
		visible: 1,
		easing: "backout",
	    speed: 1000
	    });
	});
});
</script>

<div id="slidearea">
<div id="gallerycover">
<div class="mygallery">

<ul>
<?php
global $dbtablesprefix;
$row_count=0;
$f_query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `NEW` = '1'";
$f_sql = mysql_query($f_query) or die(mysql_error());
while ($f_row = mysql_fetch_array($f_sql)) {
	$row_count++;
	?>
	<li>
	<div class="mytext">
	<h2><a href="?page=details&prod=<?php echo $f_row['ID']; ?>" rel="bookmark"
		title="Permanent Link to <?php echo $f_row['ID']; ?>"
	><?php echo $f_row['PRODUCTID']; ?></a></h2>
	<?php  
		list($thumb,$height,$width,$resized)=wsDefaultProductImageUrl($f_row['ID'],str_replace('tn_','',$f_row['DEFAULTIMAGE']),false);
	?>
	<p><?php echo $f_row['DESCRIPTION'] ?></p>
	<?php  ?> <?php if (1==1) { ?> <img class="slidim"
		src="<?php echo ZING_URL; ?>fws/addons/timthumb/timthumb2.php?src=<?php echo urlencode(preg_replace('/'.$_SERVER['HTTP_HOST'].'\/[a-z0-9]*\//',$_SERVER['HTTP_HOST'],$thumb)); ?>&dir=<?php echo urlencode(ZING_UPLOADS_DIR.'cache')?>&amp;h=<?php echo $height;?>&amp;w=<?php echo $width?>&amp;zc=1"
		alt=""
	/> <?php } else { ?> <img src="<?php ZING_CAROUSEL_URL; ?>images/place1.jpg" alt="" /> <?php } ?></div>
	</li>
	<?php } ?>
</ul>

<div class="clear"></div>

</div>

</div>

<a href="#" class="prev"></a> <a href="#" class="next"></a></div>
