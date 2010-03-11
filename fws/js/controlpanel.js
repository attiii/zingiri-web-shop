var wsControlPanel = Class.create( {

	initialize : function() {
		var e = $$('.zing_cp_icon');
		//alert('here'+e.length);
		for (i = 0; i < e.length; i++) {
			//e[i].type = 'button';
			e[i].observe('mouseover', this.iconGrow.bindAsEventListener(this));
			e[i].observe('mouseout', this.iconShrink.bindAsEventListener(this));
		//	e[i].observe('click', this.iconClick.bindAsEventListener(this));
		}
		;
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

	},

	showCart : function() {
		// $('showcart').hide();
	// $('shoppingcart').show();
	// var e = $$('shoppingcart');
	// for (i=0; i<e.length; i++)
	// {
	Effect.BlindDown('shoppingcart', {
		duration : 1.0
	});
	// }
	// Effect.Fade('showcart', {duration:1.0});
	// Effect.Appear('hidecart', {duration:1.0});
	$('showcart').hide();
	$('hidecart').show();

},

hideCart : function() {
	// $('showcart').show();
	// $('shoppingcart').hide();
	Effect.BlindUp('shoppingcart', {
		duration : 1.0
	});
	// Effect.Appear('showcart', {delay:1.0, duration:0.5});
	$('showcart').show();
	$('hidecart').hide();
},

iconClick : function(v) {
	var e = Event.element(v);
	$(e.id+'_p').show;
},

iconGrow : function(v) {
	var e = Event.element(v);
	e.height=48;
	e.width=48;
	var label=$('icon_label');
	var mouseX=Event.pointerX(v);
	label.innerHTML=e.alt;
	label.setStyle({'position' : 'absolute', 'left' : mouseX+'px'});
	label.show();
},

iconShrink : function(v) {
	var e = Event.element(v);
	e.height=32;
	e.width=32;
	$('icon_label').hide();
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
}

});

new wsControlPanel();