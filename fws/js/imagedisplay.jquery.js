function wsHoverImage(img,height,width) {
	var highlight=jQuery("#highlight_image");
	highlight.attr('src',img);
	highlight.attr('height',height);
	highlight.attr('width',width);
	
	jQuery("#highlight_ref").attr('href',img);
}