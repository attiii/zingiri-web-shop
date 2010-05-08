var wsSearch = Class.create( {

	initialize : function() {
		if ($('searchbar') != null) {
			var e=$('searchbar');
			new Form.Element.Observer(e, 0.8, this.searchBar);
		}
	},

	searchBar : function() {
		var tag = $('searchresults');
		new Ajax.Request(wsURL + "search.php", {
			method : "post",
			parameters : {
				'searchfor' : $('searchbar').value
			},
			onComplete : function(request) {
			tag.innerHTML = request.responseText;

			}.bind(this)
		});
	}

});