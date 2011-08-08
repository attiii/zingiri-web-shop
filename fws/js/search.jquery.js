wsSearch = {

	init : function() {
		if (jQuery('#searchbar') != null) {
			var e=jQuery('#searchbar');
			e.keyup(function () {
				var tag = jQuery('#searchresults');
				new jQuery.ajax({
					url : wsURL + "search.php",
					type : "post",
					data : {
						'searchfor' : jQuery('#searchbar').attr('value'),
						'wpabspath' : wpabspath,
						'cms' : wsCms
					},
					success : function(request) {
						//tag.attr('innerHTML',request);
						tag.html(request);
					}
				});			
			});
		}
	}
};