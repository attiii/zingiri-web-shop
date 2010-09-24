var wsCart = {
	
	init : function() {
		if (!jQuery.cookie('showcart')) jQuery.cookie('showcart','n',{expires : 1,path : '/'});
	},

	contents : function() {
		if (jQuery('#shoppingcart') != null)
			jQuery('#shoppingcart').hide();
		
		if (jQuery('#hidecart') != null) {
			jQuery('#hidecart').hide();
			jQuery('#hidecart').bind('click',this,function(e) {e.data.hideCart(e); });
		}
		
		if (jQuery('#showcart') != null)
			jQuery('#showcart').bind('click',this,function(e) {e.data.showCart(e); });
		
		if (jQuery.cookie('showcart')=='y')
			this.showCartWithoutEffects();
	},

	showCartWithoutEffects : function() {
		if (jQuery('#shoppingcart')!=null) jQuery('#shoppingcart').show();
		if (jQuery('#showcart')!=null) jQuery('#showcart').hide();
		if (jQuery('#hidecart')!=null) jQuery('#hidecart').show();
	},

	showCart : function() {
		jQuery("#shoppingcart").show("blind", {direction: "vertical"}, 1000);
		jQuery('#showcart').hide();
		jQuery('#hidecart').show();
		jQuery.cookie('showcart', 'y');
	},

	hideCart : function() {
		jQuery("#shoppingcart").hide("blind", {direction: "vertical"}, 1000);
		jQuery('#showcart').show();
		jQuery('#hidecart').hide();
		jQuery.cookie('showcart', 'n');
	},

	order : function() {
		var e = jQuery('.addtocart');
		for (i = 0; i < e.length; i++) {
			jQuery(e[i]).bind('click', this, function(v) {v.data.addToCart(v); });
		};
		var e = jQuery('.addtowishlist');
		for (i = 0; i < e.length; i++) {
			jQuery(e[i]).bind('click', this, function(v) {v.data.addToWishlist(v); });
		};
	},

	addToCart : function(v) {
		var t=this;
		var e = jQuery(v.target);
		if (wsFrontPage) var image = e.closest('td').find('img');
		else var image = e.closest('tr').find('img');
		form = e.closest('form');
		data=form.serialize(true);
		data+='&cms='+wsCms+'&wpabspath='+wpabspath;
		new jQuery.ajax({
			url : wsURL + "addToCart.php",
			type : "post",
			data : data,
			success : function(request) {
				if (request) alert(request);
				else t.getCart();
			}
		});

		if (wsAnimateImage==1) image.effect("shake", {times : 3 }, 300);
	},

	addToWishlist : function(v) {
		var t=this;
		var e = jQuery(v.target);
		if (wsFrontPage) var image = e.closest('td').find('img');
		else var image = e.closest('tr').find('img');
		form = e.closest('form');
		data=form.serialize(true);
		data+='&cms='+wsCms+'&wpabspath='+wpabspath;
		new jQuery.ajax({
			url : wsURL + "add_to_wishlist.php",
			type : "post",
			data : data,
			success : function(request) {
				alert('Added to wishlist');
				if (request) alert(request);
				//else t.getCart();
			}
		});
	},

	getCart : function() {
		var t=this;
		var tag = jQuery('#zing-sidebar-cart');
		new jQuery.ajax({
			url : wsURL + "getCartContents.php",
			type : "post",
			data : {
				'wpabspath' : wpabspath,
				'cms' : wsCms,
			},
			success : function(request) {
				tag.html(request);
				if (jQuery.cookie('showcart')=='n') {
					jQuery("#shoppingcart").show("blind", {direction: "vertical"}, 1000).delay(3000).hide("blind", {direction: "vertical"}, 1000);
				}
			}
		});
	},
	
	removeFromCart : function(id) {
		var t=this;
		var form=jQuery('#cart_remove'+id);
		
		new jQuery.ajax({
			url : form.attr('action'),
			type : "post",
			data : form.serialize(true),
			success : function(request) {
				t.getCart();
			}
		});
	},

	updateCart : function(id,delta) {
		var t=this;
		var form=jQuery('#cart_update'+id);
		var q=form.find('#numprod');
		q.attr('value',q.attr('value')*1+delta);
		new jQuery.ajax({
			url : form.attr('action'),
			type : "post",
			data : form.serialize(true),
			success : function(request) {
				t.getCart();
			}
		});
	}
	
};