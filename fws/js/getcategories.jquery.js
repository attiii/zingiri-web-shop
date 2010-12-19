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
			url : wsURL + "get_categories.php",
			type : "post",
			data : {'cms' : wsCms, 'wpabspath' : wpabspath, 'wsgroupid' : group },
			success : function(request) {
				//alert(request);
				jQuery('#wsproductcategory').attr('innerHTML',request);
			}
		});

	}
}
jQuery(document).ready(function() {
	wsCategories.init();
});