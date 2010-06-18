<?php
add_action("init","zing_carousel_ekologic_init");

function zing_carousel_ekologic_init() {
	wp_enqueue_script('jcarousel', ZING_CAROUSEL_URL.'js/jcarousel.js');
	wp_enqueue_script('jquery.easing.1.1', ZING_CAROUSEL_URL.'js/jquery.easing.1.1.js');
	echo '<link rel="stylesheet" type="text/css" href="'.ZING_CAROUSEL_URL.'glide.css"media="screen" />';
}
?>