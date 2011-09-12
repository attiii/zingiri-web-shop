appsSortList = {
	container : null,
	id : null,

	init : function(ajaxUpdateURL) {
		jQuery('.sortlist').each(function(i, itemin) {
			item = jQuery('#' + itemin.id);
			this.id = item.attr('id');
			this.container = item;
			that = this;
			element = itemin;
			this.container.sortable( {
				'stop' : function() {
					new jQuery.ajax( {
						url : ajaxUpdateURL,
						type : "post",
						data : {
							'cms' : wsCms,
							'sortorder' : that.container.sortable("serialize")
						},
						success : function(request) {
							//alert('call:'+request);
						}
					});
				}
			});
		});
	}
}
