<script type="text/javascript">
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
</script>

<div id="slidearea">

<div id="gallerycover">
<div class="mygallery">

<ul>
<?php
global $dbtablesprefix;
$f_query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `NEW` = '1'";
$f_sql = mysql_query($f_query) or die(mysql_error());
while ($f_row = mysql_fetch_array($f_sql)) {
$row_count++;
?>
	<li>
	<div class="mytext">
	<h2><a href="?page=details&prod=<?php echo $f_row['ID']; ?>"
		rel="bookmark" title="Permanent Link to <?php echo $f_row['ID']; ?>"><?php echo $f_row['PRODUCTID']; ?></a></h2>
		<?php  ?> <p><?php echo $f_row['DESCRIPTION'] ?></p> <?php  ?> <?php if (1==1) { ?>
	<img class="slidim"
		src="<?php echo ZING_CAROUSEL_URL; ?>timthumb.php?src=<?php echo urlencode(ZING_UPLOADS_URL.'prodgfx/'.$f_row['ID'].'.jpg'); ?>&amp;h=180&amp;w=400&amp;zc=1"
		alt="" /> <?php } else { ?> <img
		src="<?php ZING_CAROUSEL_URL; ?>images/place1.jpg"
		alt="" /> <?php } ?></div>
	</li>
	<?php } ?>
</ul>

<div class="clear"></div>

</div>

</div>

<a href="#" class="prev"></a> <a href="#" class="next"></a></div>