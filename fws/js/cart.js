var wsCart = Class.create( {

	initialize : function() {
	},

	contents : function() {
		if ($('shoppingcart') != null) $('shoppingcart').hide();
		if ($('hidecart') != null) {
			$('hidecart').hide();
			$('hidecart').observe('click', this.hideCart.bindAsEventListener(this));
		}
		if ($('showcart') != null) $('showcart').observe('click', this.showCart.bindAsEventListener(this));

	},

	showCart : function() {
//		$('showcart').hide();
//		$('shoppingcart').show();
//		var e = $$('shoppingcart');
//		for (i=0; i<e.length; i++) 
//		{
			Effect.BlindDown('shoppingcart', {duration:1.0});
//		}
		//Effect.Fade('showcart', {duration:1.0});
		//Effect.Appear('hidecart', {duration:1.0});
			$('showcart').hide();
			$('hidecart').show();

	},
	
	hideCart : function() {
//		$('showcart').show();
//		$('shoppingcart').hide();
		Effect.BlindUp('shoppingcart', {duration:1.0});
		//Effect.Appear('showcart', {delay:1.0, duration:0.5});
		$('showcart').show();
		$('hidecart').hide();
	},

	order : function() {
		var e = $$('#addtocart');
		for (i=0; i<e.length; i++) 
		{
			e[i].type = 'button';
			e[i].observe('click', this.addToCart.bindAsEventListener(this));
		};
	},

	addToCart : function(v) {
		var e = Event.element(v);
		var image = e.up('tr').down('img');
		//alert(image.src);
		form=e.up('form').id;
		$(form).request();
		Effect.Shake(image, {duration:1.0});
		this.getCart();
	},
	
	getCart : function() {
		var tag = $('zing-sidebar-cart');
		new Ajax.Request(wsURL + "getCartContents.php", {
			method : "post",
			parameters : {
				'dummy' : '123'
			},
			onComplete : function(request) {
			tag.innerHTML = request.responseText;
			Effect.BlindDown('shoppingcart', {duration:1.0});
			Effect.BlindUp('shoppingcart', {delay:3.0, duration:1.0});
			this.contents();

		}.bind(this)
		});
}

});