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
					url : wsURL + "dashboard_update.php",
					type : "post",
					data : {
						'data' : Sortable.serialize('zdashboard'),
						'wpabspath' : wpabspath,
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
				url : wsURL+"product_update.php",
				type : "post",
				data : {
					'id' : id,
					'frontpage' : checked,
					'wpabspath' : wpabspath,
					'cms' : wsCms
				},
				success : function(request) {
					//alert(request);
				}
			});
}