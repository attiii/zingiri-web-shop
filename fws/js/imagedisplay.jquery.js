function wsHoverImage(img,height,width) {
	var highlight=jQuery("#highlight_image");
	highlight.attr('src',img);
	highlight.attr('height',height);
	highlight.attr('width',width);
	if (jQuery("#highlight_ref") != null)	jQuery("#highlight_ref").attr('href',img);
}