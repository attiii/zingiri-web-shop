var wsCart = Class.create( {

	initialize : function() {
		Cookie.init( {
			name : 'zing_ws',
			expires : 1,
			path : '/'
		});
	},

	contents : function() {
		if ($('shoppingcart') != null)
			$('shoppingcart').hide();
		if ($('hidecart') != null) {
			$('hidecart').hide();
			$('hidecart').observe('click',
					this.hideCart.bindAsEventListener(this));
		}
		if ($('showcart') != null)
			$('showcart').observe('click',
					this.showCart.bindAsEventListener(this));
		if (Cookie.getData('showcart') == true)
			this.showCartWithoutEffects();
	},

	showCartWithoutEffects : function() {
		$('shoppingcart').show();
		$('showcart').hide();
		$('hidecart').show();
		Cookie.setData('showcart', true);
	},

	showCart : function() {
		Effect.BlindDown('shoppingcart', {
			duration : 1.0
		});
		$('showcart').hide();
		$('hidecart').show();
		Cookie.setData('showcart', true);
	},

	hideCart : function() {
		Effect.BlindUp('shoppingcart', {
			duration : 1.0
		});
		$('showcart').show();
		$('hidecart').hide();
		Cookie.setData('showcart', false);
	},

	order : function() {
		var e = $$('#addtocart');
		for (i = 0; i < e.length; i++) {
			e[i].type = 'button';
			e[i].observe('click', this.addToCart.bindAsEventListener(this));
		}
		;
	},

	addToCart : function(v) {
		var e = Event.element(v);
		var image = e.up('tr').down('img');
		form = e.up('form').id;
		new Ajax.Request($(form).action, {
			method : "post",
			parameters : $(form).serialize(true),
			onComplete : function(request) {
				this.getCart();
			}.bind(this)
		});

		Effect.Shake(image, {
			duration : 1.0
		});
	},

	sendToCart : function() {
		var tag = $('zing-sidebar-cart');
		new Ajax.Request(wsURL + "getCartContents.php", {
			method : "post",
			parameters : {
				'dummy' : '123'
			},
			onComplete : function(request) {
				tag.innerHTML = request.responseText;
				Effect.BlindDown('shoppingcart', {
					duration : 1.0
				});
				Effect.BlindUp('shoppingcart', {
					delay : 3.0,
					duration : 1.0
				});
				this.contents();

			}.bind(this)
		});
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
				if (Cookie.getData('hidecart') == true) {
					Effect.BlindDown('shoppingcart', {
						duration : 1.0
					});
					Effect.BlindUp('shoppingcart', {
						delay : 3.0,
						duration : 1.0
					});
				}
				this.contents();

			}.bind(this)
		});
	}

});