function wsRemoveImage(id,tag) {
	new jQuery.ajax({
		url : aphpsAjaxURL+'removeimage&mod=fwkfor',
		type : "post",
		data : { 
			'id' : id, 
			'cms' : wsCms
		},
		success : function(request) {
			jQuery('#'+jQuery.escape(tag)).hide();
		}
	});

}

jQuery(document).ready(function() {
	var key=jQuery('#upload_key').attr('value');
	if (key!=null) {
		new AjaxUpload('upload_button', {
			data: { 
				'upload_key' : key,
				'cms' : wsCms
				},
			responseType: 'json',
			action: aphpsAjaxURL+'uploadimage&mod=fwkfor',
			onComplete: function(file, response) {
				var divTag = jQuery(document.createElement("div"));
				divTag.css('position',"relative");
				divTag.css('cssFloat',"left");
				divTag.css('styleFloat',"left");
				divTag.attr('id','tn_'+response.target_file);

				var imgTag = jQuery(document.createElement("img"));
				imgTag.attr('src',response.target_url);
				imgTag.attr('height','48px');
			
				var aTag = jQuery(document.createElement("a"));
				aTag.attr('href','javascript:wsRemoveImage(\''+response.target_file+'\',\'tn_'+response.target_file+'\');');
				aTag.html('<img style="position:absolute;right:0px;top:0px;" src="'+aphpsURL+'images/delete.png" height="16px" width="16px" />');
			
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