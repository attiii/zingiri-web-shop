dashboard = {
	
	initialize : function(name)
	{
		that=this;
		jQuery("#zdashboard").sortable({
			'tag': 'div', 
			'constraint': '', 
			'dropOnEmpty': true,
			'stop': function () { that.save(); }
		});
	},
	
	save : function()
	{
		new jQuery.ajax({
					url : wsAjaxURL + "dashboard_update",
					type : "post",
					data : {
						'data' : Sortable.serialize('zdashboard'),
						'cms' : wsCms
					},
					success : function(request) {
						//alert(request);
					}
				});
				
	}
};

function wsFrontPage(id,checked) {
	new jQuery.ajax({
				url : wsAjaxURL+"product_update",
				type : "post",
				data : {
					'id' : id,
					'frontpage' : checked,
					'cms' : wsCms
				},
				success : function(request) {
					//alert(request);
				}
			});
}