function wsHoverImage(img,height,width) {
	var highlight=$('highlight_image');
	highlight.src=img;
	highlight.height=height;
	highlight.width=width;
	
	if ($('highlight_ref') != null) $('highlight_ref').href=img;
}