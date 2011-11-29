function wsGroupToggle(id,withEffect) {
	var e = jQuery('.zing-product-group');
	for (i = 0; i < e.length; i++) {
		v=jQuery(e[i]).parent('li').children('ul');
		if (v.attr('id')!='group'+id) {
			v.hide();
		}
		else {
			if (withEffect!=null && withEffect==false) v.show();
			else v.show("blind", {direction: "vertical"}, 800);
		}
	};
	jQuery.cookie('productgroup', id);
}

jQuery(document).ready(function() {
	if (!jQuery.cookie('productgroup')) jQuery.cookie('productgroup','0',{expires : 1,path : '/'});
	else if ((id=jQuery.cookie('productgroup')) > 0) wsGroupToggle(id,false);
	
});
