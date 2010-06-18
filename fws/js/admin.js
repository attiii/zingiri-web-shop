var dashboard = Class.create({
	
	initialize : function(name)
	{

	var params = {
	    tag: 'div', 
	    constraint: '', 
	    dropOnEmpty: true,
	    onUpdate: function () { this.save(); }.bind(this)
		};
	Sortable.create($("zdashboard"), params);
	},
	
	save : function()
	{
		new Ajax.Request(
				wsURL+"dashboard_update.php",
				{
					method : "post",
					parameters : {
						'data' : Sortable.serialize('zdashboard'),
						'wpabspath' : wpabspath
					},
					onComplete : function(request) {
						//alert(request.responseText);
					}.bind(this)
				});
				
	}
});

function wsFrontPage(id,checked) {
	new Ajax.Request(
			wsURL+"product_update.php",
			{
				method : "post",
				parameters : {
					'id' : id,
					'frontpage' : checked,
					'wpabspath' : wpabspath
				},
				onComplete : function(request) {
					//alert(request.responseText);
				}.bind(this)
			});
}