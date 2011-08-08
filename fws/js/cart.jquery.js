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
			if (wsAnimateImage==2) jQuery(e[i]).bind('click', this, function(v) {v.data.flyToCart(v); });
			else jQuery(e[i]).bind('click', this, function(v) {v.data.addToCart(v); });
		};
		e = jQuery('.addtowishlist');
		for (i = 0; i < e.length; i++) {
			jQuery(e[i]).bind('click', this, function(v) {v.data.addToWishlist(v); });
		};
		e = jQuery('.wsfeatures input');
		for (i = 0; i < e.length; i++) {
			jQuery(e[i]).bind('keyup', this, function(v) {v.data.recalculatePrice(v); });
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
		data+='&cms='+wsCms+'&wpabspath='+wpabspath;
		data+='&wsfeature='+e.attr('name').replace('[]','');
		new jQuery.ajax({
			url : wsURL + "recalculate_price.php",
			type : "post",
			data : data,
			success : function(request) {
				a=eval("(" + request + ")");
				f=form.find('.wsfeature');
				for (i = 0; i < f.length; i++) {
					n=jQuery(f[i]).attr('name').replace('[]','');
					v=a.post[eval("'"+n+"'")][0];
//					alert(n+'='+i+'='+a.post[eval("'"+n+"'")][0]);
					jQuery(f[i]).attr('value',v);
					//alert(i);
				}
				priceIn.attr('innerHTML',a.pricein);
				priceEx.attr('innerHTML',a.priceex);
			}
		});
	},
	
	addToCart : function(v) {
		var t=this;
		var e = jQuery(v.target);
		if (wsProductDisplayType == 'list') var image = e.closest('tr').find('img');
		else var image = e.closest('td').find('img');
		
		if (wsAnimateImage==2) wsFlyToCart.fly(image);

		jQuery("#notificationsLoader").html('<img src="'+wsURL+'../templates/default/images/loader2.gif">');

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
				jQuery("#notificationsLoader").empty();
			}
		});

		if (wsAnimateImage==1) image.effect("shake", {times : 3 }, 300);
	},

	flyToCart : function(v) {
		var t=this;
		var e = jQuery(v.target);
		if (wsProductDisplayType == 'list') var image = e.closest('tr').find('img');
		else var image = e.closest('td').find('img');

		form = e.closest('form');
		data=form.serialize(true);
		data+='&cms='+wsCms+'&wpabspath='+wpabspath;

		var productX 		= image.offset().left;
		var productY 		= image.offset().top;
		var basketX 		= jQuery("#zing-sidebar-cart").offset().left;
		var basketY 		= jQuery("#zing-sidebar-cart").offset().top;
		var gotoX 			= basketX - productX;
		var gotoY 			= basketY - productY;
		var newImageWidth 	= image.width() / 3;
		var newImageHeight	= image.height() / 3;
//		alert(productX+'-'+productY);
	//	alert(basketX+'-'+basketY);

		image
		.clone()
		.attr('id','tempimage')
		.prependTo(image.parent())
		.css({'position' : 'absolute'})
		.animate({opacity: 0.4}, 100 )
		.animate({opacity: 0.1, marginLeft: gotoX, marginTop: gotoY, width: newImageWidth, height: newImageHeight}, 1200, function() {
			new jQuery.ajax({
				url : wsURL + "addToCart.php",
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
