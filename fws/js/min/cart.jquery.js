var wsCart={refresh:false,init:function(){if(!jQuery.cookie("showcart")){jQuery.cookie("showcart","n",{expires:1,path:"/"})}},contents:function(){if(jQuery("#shoppingcart")!=null){jQuery("#shoppingcart").hide()}if(jQuery("#hidecart")!=null){jQuery("#hidecart").hide();jQuery("#hidecart").bind("click",this,function(b){b.data.hideCart(b)})}if(jQuery("#showcart")!=null){jQuery("#showcart").bind("click",this,function(b){b.data.showCart(b)})}if(this.refresh===false){this.getCart();this.refresh=true}if(jQuery.cookie("showcart")=="y"){this.showCartWithoutEffects()}},showCartWithoutEffects:function(){if(jQuery("#shoppingcart")!=null){jQuery("#shoppingcart").show()}if(jQuery("#showcart")!=null){jQuery("#showcart").hide()}if(jQuery("#hidecart")!=null){jQuery("#hidecart").show()}},showCart:function(){jQuery("#shoppingcart").show("blind",{direction:"vertical"},1000);jQuery("#showcart").hide();jQuery("#hidecart").show();jQuery.cookie("showcart","y")},hideCart:function(){jQuery("#shoppingcart").hide("blind",{direction:"vertical"},1000);jQuery("#showcart").show();jQuery("#hidecart").hide();jQuery.cookie("showcart","n")},order:function(){var b=jQuery(".addtocart");for(i=0;i<b.length;i++){if(wsAnimateImage==2){jQuery(b[i]).bind("click",this,function(c){c.data.flyToCart(c)})}else{jQuery(b[i]).bind("click",this,function(c){c.data.addToCart(c)})}}b=jQuery(".addtowishlist");for(i=0;i<b.length;i++){jQuery(b[i]).bind("click",this,function(c){c.data.addToWishlist(c)})}b=jQuery(".wsfeatures input");for(i=0;i<b.length;i++){jQuery(b[i]).bind("keyup",this,function(c){c.data.recalculatePrice(c)})}b=jQuery(".wsfeatures select");for(i=0;i<b.length;i++){jQuery(b[i]).bind("change",this,function(c){c.data.recalculatePrice(c)})}},recalculatePrice:function(v){var e=jQuery(v.target);var form=e.closest("form");var priceIn=form.find(".wspricein");var priceEx=form.find(".wspriceex");data=form.serialize(true);data+="&cms="+wsCms;data+="&wsfeature="+e.attr("name").replace("[]","");new jQuery.ajax({url:wsAjaxURL+"recalculate_price",type:"post",data:data,success:function(request){a=eval("("+request+")");f=form.find(".wsfeature");for(i=0;i<f.length;i++){n=jQuery(f[i]).attr("name").replace("[]","");v=a.post[eval("'"+n+"'")][0];jQuery(f[i]).attr("value",v)}priceIn.attr("innerHTML",a.pricein);priceEx.attr("innerHTML",a.priceex)}})},addToCart:function(b){var c=this;var g=jQuery(b.target);if(wsProductDisplayType=="list"){var d=g.closest("tr").find("img")}else{var d=g.closest("td").find("img")}if(wsAnimateImage==2){wsFlyToCart.fly(d)}jQuery("#notificationsLoader").html('<img src="'+wsURL+'fws/templates/default/images/loader2.gif">');form=g.closest("form");data=form.serialize(true);data+="&cms="+wsCms;new jQuery.ajax({url:wsAjaxURL+"addToCart",type:"post",data:data,success:function(e){if(e){alert(e)}else{c.getCart()}jQuery("#notificationsLoader").empty()}});if(wsAnimateImage==1){d.effect("shake",{times:3},300)}},flyToCart:function(m){var q=this;var g=jQuery(m.target);if(wsProductDisplayType=="list"){var b=g.closest("tr").find("img")}else{var b=g.closest("td").find("img")}form=g.closest("form");data=form.serialize(true);data+="&cms="+wsCms;var p=b.offset().left;var o=b.offset().top;var j=jQuery("#zing-sidebar-cart").offset().left;var h=jQuery("#zing-sidebar-cart").offset().top;var l=j-p;var k=h-o;var c=b.width()/3;var d=b.height()/3;b.clone().attr("id","tempimage").prependTo(b.parent()).css({position:"absolute"}).animate({opacity:0.4},100).animate({opacity:0.1,marginLeft:l,marginTop:k,width:c,height:d},1200,function(){new jQuery.ajax({url:wsAjaxURL+"addToCart",type:"post",data:data,success:function(e){if(e){alert(e)}else{q.getCart()}jQuery("#tempimage").remove()}})})},addToWishlist:function(b){var c=this;var g=jQuery(b.target);var d=g.closest("td").find("img");form=g.closest("form");data=form.serialize(true);data+="&cms="+wsCms;new jQuery.ajax({url:wsAjaxURL+"add_to_wishlist",type:"post",data:data,success:function(e){alert("Added to wishlist");if(e){alert(e)}}})},getCart:function(){var c=this;var b=jQuery("#zing-sidebar-cart");new jQuery.ajax({url:wsAjaxURL+"getCartContents",type:"post",data:{cms:wsCms},success:function(d){var e=jQuery.parseJSON(d);b.html(e.data);jQuery(".wscarttotalprice").html(e.total);jQuery(".wscarttotalitems").html(e.count);c.contents();if(jQuery.cookie("showcart")=="n"){jQuery("#shoppingcart").show("blind",{direction:"vertical"},1000).delay(3000).hide("blind",{direction:"vertical"},1000)}}})},removeFromCart:function(d){var b=this;var c=jQuery("#cart_remove"+d);new jQuery.ajax({url:c.attr("action"),type:"post",data:c.serialize(true),success:function(e){b.getCart()}})},updateCart:function(g,e){var b=this;var c=jQuery("#cart_update"+g);var d=c.find("#numprod");d.attr("value",d.attr("value")*1+e);new jQuery.ajax({url:c.attr("action"),type:"post",data:c.serialize(true),success:function(h){b.getCart()}})}};jQuery(document).ready(function(){wsCart.contents()});