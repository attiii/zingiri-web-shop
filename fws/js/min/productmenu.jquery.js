function wsGroupToggle(c,a){var b=jQuery(".zing-product-group");for(i=0;i<b.length;i++){v=jQuery(b[i]).parent("li").children("ul");if(v.attr("id")!="group"+c){v.hide()}else{if(a!=null&&a==false){v.show()}else{v.show("blind",{direction:"vertical"},800)}}}jQuery.cookie("productgroup",c)}jQuery(document).ready(function(){if(!jQuery.cookie("productgroup")){jQuery.cookie("productgroup","0",{expires:1,path:"/"})}else{if((id=jQuery.cookie("productgroup"))>0){wsGroupToggle(id,false)}}});