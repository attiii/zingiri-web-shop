function wsHoverImage(img,height,width) {
	var highlight=$('highlight_image');
	highlight.src=img;
	highlight.height=height;
	highlight.width=width;
	
	$('highlight_ref').href=img;
}