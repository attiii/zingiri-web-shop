var wsCart = {

	refresh:false,
		
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
		
		if (this.refresh === false) {
			this.getCart();
			this.refresh=true;
		}
		
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
			if (jQuery("#zing-sidebar-cart").length==0) {
				jQuery(e[i]).bind('click', this, function(v) {v.data.goToProduct(v); });
			} else {
				if (wsAnimateImage==2) jQuery(e[i]).bind('click', this, function(v) {v.data.flyToCart(v); });
				else jQuery(e[i]).bind('click', this, function(v) {v.data.addToCart(v); });
			}
		};
		e = jQuery('.addtowishlist');
		for (i = 0; i < e.length; i++) {
			jQuery(e[i]).bind('click', this, function(v) {v.data.addToWishlist(v); });
		};
		e = jQuery('.wsfeatures input');
		for (i = 0; i < e.length; i++) {
			jQuery(e[i]).bindWithDelay('keyup', this, function(v) {v.data.recalculatePrice(v); },500);
		};
		e = jQuery('.wsfeatures select');
		for (i = 0; i < e.length; i++) {
			jQuery(e[i]).bind('change', this, function(v) {v.data.recalculatePrice(v); });
		};
	},

	recalculatePrice : function(v) {
		var e = jQuery(v.target);
		var form = e.closest('form');
		var priceIn=form.find('.wspricein');
		var priceEx=form.find('.wspriceex');
		data=form.serialize(true);
		data+='&cms='+wsCms;
		data+='&wsfeature='+e.attr('name').replace('[]','');
		new jQuery.ajax({
			url : wsAjaxURL + "recalculate_price",
			type : "post",
			data : data,
			success : function(request) {
				a=eval("(" + request + ")");
				f=form.find('.wsfeature');
				for (i = 0; i < f.length; i++) {
					n=jQuery(f[i]).attr('name').replace('[]','');
					v=a.post[eval("'"+n+"'")][0];
					jQuery(f[i]).attr('value',v);
				}
				priceIn.html(a.pricein);
				priceEx.html(a.priceex);
			}
		});
	},

	goToProduct : function(v) {
		var t=this;
		var e = jQuery(v.target);
		if (wsProductDisplayType == 'list') var tag = e.closest('tr').find('form');
		else var tag = e.closest('td').find('form');
		
		tag.submit();
	},

	addToCart : function(v) {
		var t=this;
		var e = jQuery(v.target);
		if (wsProductDisplayType == 'list') var image = e.closest('tr').find('img');
		else var image = e.closest('td').find('img');
		
		if (wsAnimateImage==2) wsFlyToCart.fly(image);

		jQuery("#notificationsLoader").html('<img src="'+wsURL+'fws/templates/default/images/loader2.gif">');

		form = e.closest('form');
		data=form.serialize(true);
		data+='&cms='+wsCms;
		new jQuery.ajax({
			url : wsAjaxURL + "addToCart",
			type : "post",
			data : data,
			success : function(request) {
				if (request) alert(request);
				else t.getCart();
				jQuery("#notificationsLoader").empty();
			}
		});

		if (wsAnimateImage==1) image.effect("shake", {times : 3 }, 300);
	},

	flyToCart : function(v) {
		var cart=jQuery("#zing-sidebar-cart");
		
		var t=this;
		var e = jQuery(v.target);
		if (wsProductDisplayType == 'list') var image = e.closest('tr').find('img');
		else var image = e.closest('td').find('img');

		form = e.closest('form');
		data=form.serialize(true);
		data+='&cms='+wsCms;

		var productX 		= image.offset().left;
		var productY 		= image.offset().top;
		var basketX 		= cart.offset().left;
		var basketY 		= cart.offset().top;
		var gotoX 			= basketX - productX;
		var gotoY 			= basketY - productY;
		var newImageWidth 	= image.width() / 3;
		var newImageHeight	= image.height() / 3;

		image
		.clone()
		.attr('id','tempimage')
		.prependTo(image.parent())
		.css({'position' : 'absolute'})
		.animate({opacity: 0.4}, 100 )
		.animate({opacity: 0.1, marginLeft: gotoX, marginTop: gotoY, width: newImageWidth, height: newImageHeight}, 1200, function() {
			new jQuery.ajax({
				url : wsAjaxURL + "addToCart",
				type : "post",
				data : data,
				success : function(request) {
					if (request) alert(request);
					else t.getCart();
					jQuery("#tempimage").remove();
				}
			});
		});
	},

	addToWishlist : function(v) {
		var t=this;
		var e = jQuery(v.target);
		var image = e.closest('td').find('img');
		form = e.closest('form');
		data=form.serialize(true);
		data+='&cms='+wsCms;
		new jQuery.ajax({
			url : wsAjaxURL + "add_to_wishlist",
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
			url : wsAjaxURL + "getCartContents",
			type : "post",
			data : {
				'cms' : wsCms
			},
			success : function(request) {
				var o=jQuery.parseJSON(request);
				tag.html(o.data);
				jQuery('.wscarttotalprice').html(o.total);
				jQuery('.wscarttotalitems').html(o.count);
			    t.contents();

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

jQuery(document).ready(function() {
    wsCart.contents();
});

(function(jQuery) {
	jQuery.fn.bindWithDelay = function( type, data, fn, timeout, throttle ) {

	if ( jQuery.isFunction( data ) ) {
	throttle = timeout;
	timeout = fn;
	fn = data;
	data = undefined;
	}

	// Allow delayed function to be removed with fn in unbind function
	fn.guid = fn.guid || (jQuery.guid && jQuery.guid++);

	// Bind each separately so that each element has its own delay
	return this.each(function() {
	        
	        var wait = null;
	        
	        function cb() {
	            var e = jQuery.extend(true, { }, arguments[0]);
	            var ctx = this;
	            var throttler = function() {
	             wait = null;
	             fn.apply(ctx, [e]);
	            };
	            
	            if (!throttle) { clearTimeout(wait); wait = null; }
	            if (!wait) { wait = setTimeout(throttler, timeout); }
	        }
	        
	        cb.guid = fn.guid;
	        
	        jQuery(this).bind(type, data, cb);
	});


	}
	})(jQuery);