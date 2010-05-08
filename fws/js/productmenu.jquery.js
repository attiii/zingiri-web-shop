function wsGroupToggle(id) {
	var e = jQuery('.zing-product-group');
	for (i = 0; i < e.length; i++) {
		v=jQuery(e[i]).parent('li').children('ul');
		if (v.attr('id')!='group'+id) {
			v.hide();
		}
		else {
			v.show("blind", {direction: "vertical"}, 800);
		}
	};
}