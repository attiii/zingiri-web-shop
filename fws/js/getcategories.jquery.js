var wsCategories = {
	init : function() {
		jQuery('#wsproductgroup').bind('change', this, function(v) {
			v.data.change(v);
		});
	},

	change : function(v) {
		var e = jQuery(v.target);
		var group=e.attr('value');
		
		new jQuery.ajax({
			url : wsAjaxURL + "get_categories",
			type : "post",
			data : {'cms' : wsCms, 'wsgroupid' : group },
			success : function(request) {
				jQuery('#wsproductcategory').attr('innerHTML',request);
			}
		});

	}
}
jQuery(document).ready(function() {
	wsCategories.init();
});