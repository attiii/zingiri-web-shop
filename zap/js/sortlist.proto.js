appsSortList= {
	container : null,
	id : null,
	
	init : function(ajaxUpdateURL) {
		elts=$$('.sortlist');
		
		elts.each(function(item) {
			this.id=item.id;
			this.container=$(item);
			that=this;
			Sortable.create(this.container,{
				'tag':'tr',
				'onChange' : function() {
					new Ajax.Request(
						ajaxUpdateURL,
						{
							method : "post",
							parameters : {
								'sortorder' : Sortable.serialize(that.container)
							},
							onComplete : function(request) {
								 //alert('call:'+request.responseText);
							}.bind(this)
						}
					);
				}
			});
		});
	}
}
