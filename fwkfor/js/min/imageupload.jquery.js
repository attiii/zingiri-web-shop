function wsRemoveImage(b,a){new jQuery.ajax({url:aphpsAjaxURL+"removeimage&mod=fwkfor",type:"post",data:{id:b,cms:wsCms},success:function(c){jQuery("#"+jQuery.escape(a)).hide()}})}jQuery(document).ready(function(){var a=jQuery("#upload_key").attr("value");if(a!=null){new AjaxUpload("upload_button",{data:{upload_key:a,cms:wsCms},responseType:"json",action:aphpsAjaxURL+"uploadimage&mod=fwkfor",onComplete:function(d,c){var h=jQuery(document.createElement("div"));h.css("position","relative");h.css("cssFloat","left");h.css("styleFloat","left");h.attr("id","tn_"+c.target_file);var g=jQuery(document.createElement("img"));g.attr("src",c.target_url);g.attr("height","48px");var b=jQuery(document.createElement("a"));b.attr("href","javascript:wsRemoveImage('"+c.target_file+"','tn_"+c.target_file+"');");b.html('<img style="position:absolute;right:0px;top:0px;" src="'+aphpsURL+'images/delete.png" height="16px" width="16px" />');var f=jQuery(document.createElement("input"));f.attr("type","radio");f.attr("name","image_default");f.attr("value","tn_"+c.target_file);var e=jQuery(document.createElement("input"));e.attr("type","hidden");e.attr("name","new_images[]");e.attr("value",c.target_file);h.append(g);h.append(b);h.append(f);h.append(e);jQuery("#uploaded_images").append(h)}})}});function wsDeleteImage(b){var a=jQuery(document.createElement("input"));a.attr("name","delimage[]");a.attr("value",b);a.attr("type","hidden");jQuery("#upload_key").parent().append(a);jQuery("#"+jQuery.escape(b)).hide()};