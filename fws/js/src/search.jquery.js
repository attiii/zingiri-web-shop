wsSearch = {

	init : function() {
		if (jQuery('#searchbar') != null) {
			var e=jQuery('#searchbar');
			e.keyup(function () {
				var tag = jQuery('#searchresults');
				new jQuery.ajax({
					url : wsAjaxURL + "search",
					type : "post",
					data : {
						'searchfor' : jQuery('#searchbar').attr('value'),
						'cms' : wsCms
					},
					success : function(request) {
						tag.html(request);
					}
				});			
			});
		}
	}
};