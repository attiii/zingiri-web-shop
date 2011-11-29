function wsRemoveFile(id,key,dir) {
	new jQuery.ajax({
		url : aphpsAjaxURL+'removefile&mod=fwkfor',
		type : "post",
		data : { 
			'wsid' : id, 
			'wskey' : key, 
			'wsdir' : dir,
			'cms' : wsCms
		},
		success : function(request) {
			jQuery('#'+jQuery.escape(id)).hide();
		}
	});

}

jQuery(document).ready(function() {
	var key=jQuery('#upload_file_key').attr('value');
	var dir=jQuery('#upload_file_dir').attr('value');
	if (key!=null) {
		new AjaxUpload('upload_file_button', {
			data: { 
				'upload_key' : key,
				'cms' : wsCms,
				'wsdir' : dir
				},
			responseType: 'json',
			action: aphpsAjaxURL+'uploadfile&mod=fwkfor',
			onComplete: function(file, response) {
				if (response.error == 0) {
					var divTag = jQuery(document.createElement("li"));
					divTag.css('position',"relative");
					divTag.css('clear',"both");
					divTag.attr('id',response.target_file);

					var pTag = jQuery(document.createElement("p"));
					pTag.html(response.target_file);
			
					var aTag = jQuery(document.createElement("a"));
					aTag.attr('href','javascript:wsRemoveFile(\''+response.target_file+'\',\''+key+'\',\''+dir+'\');');
					aTag.html('<img style="position:absolute;right:-16px;top:0px;" src="'+aphpsURL+'images/delete.png" height="16px" width="16px" />');
			
					var newTag = jQuery(document.createElement("input"));
					newTag.attr('type','hidden');
					newTag.attr('name','new_images[]');
					newTag.attr('value',response.target_file);

					divTag.append(pTag);
					divTag.append(aTag);
					divTag.append(newTag);
					jQuery('#uploaded_files').append(divTag);
				} else {
					alert(response.error);
				}
			}
		});
	}
});


function wsDeleteFile(id) {
	var inputTag = jQuery(document.createElement("input"));
	inputTag.attr('name','delimage[]');
	inputTag.attr('value',id);
	inputTag.attr('type','hidden');
	
	jQuery('#upload_file_key').parent().append(inputTag);
	jQuery('#'+jQuery.escape(id)).hide();
}