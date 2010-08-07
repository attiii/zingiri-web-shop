function wsRemoveImage(id,tag) {
	new jQuery.ajax({
		url : wsURL+'removeimage.php',
		type : "post",
		data : { 'id' : id },
		success : function(request) {
			jQuery('#'+jQuery.escape(tag)).hide();
		}
	});

}

jQuery(document).ready(function() {
	var key=jQuery('#upload_key').attr('value');
	if (key!=null) {
		new AjaxUpload('upload_button', {
			data: { 'upload_key': key },
			responseType: 'json',
			action: wsURL+'uploadimage.php',
			onComplete: function(file, response) {
				var divTag = jQuery(document.createElement("div"));
				divTag.css('position',"relative");
				divTag.css('cssFloat',"left");
				divTag.css('styleFloat',"left");
				divTag.attr('id','tn_'+response.target_file);

				var imgTag = jQuery(document.createElement("img"));
				imgTag.attr('src',response.target_url);
			
				var aTag = jQuery(document.createElement("a"));
				aTag.attr('href','javascript:wsRemoveImage(\''+response.target_file+'\',\'tn_'+response.target_file+'\');');
				aTag.attr('innerHTML','<img style="position:absolute;right:0px;top:0px;" src="'+zfAppsUrl+'images/delete.png" height="16px" width="16px" />');
			
				var inputTag = jQuery(document.createElement("input"));
				inputTag.attr('type','radio');
				inputTag.attr('name','image_default');
				inputTag.attr('value','tn_'+response.target_file);

				var newTag = jQuery(document.createElement("input"));
				newTag.attr('type','hidden');
				newTag.attr('name','new_images[]');
				newTag.attr('value',response.target_file);

				divTag.append(imgTag);
				divTag.append(aTag);
				divTag.append(inputTag);
				divTag.append(newTag);
				jQuery('#uploaded_images').append(divTag);

			}
		});
	}
});


function wsDeleteImage(id) {
	var inputTag = jQuery(document.createElement("input"));
	inputTag.attr('name','delimage[]');
	inputTag.attr('value',id);
	inputTag.attr('type','hidden');
	
	jQuery('#upload_key').parent().append(inputTag);
	jQuery('#'+jQuery.escape(id)).hide();
}

//jquery.escape 1.0 - escape strings for use in jQuery selectors
//http://ianloic.com/tag/jquery.escape
//Copyright 2009 Ian McKellar <http://ian.mckellar.org/>
//Just like jQuery you can use it under either the MIT license or the GPL
//(see: http://docs.jquery.com/License)
(function() {
escape_re = /[#;&,\.\+\*~':"!\^\$\[\]\(\)=>|\/\\]/;
jQuery.escape = function jQuery$escape(s) {
var left = s.split(escape_re, 1)[0];
if (left == s) return s;
return left + '\\' + 
 s.substr(left.length, 1) + 
 jQuery.escape(s.substr(left.length+1));
}
})();

