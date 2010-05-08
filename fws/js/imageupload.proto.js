function wsRemoveImage(id,tag) {
	new Ajax.Request(wsURL+'removeimage.php', {
		method : "post",
		parameters : { 'id' : id },
		onComplete : function(request) {
			//alert(request.responseText);
			$(tag).hide();
		}.bind(this)
	});

}

document.observe("dom:loaded", function() {
	var key=$('upload_key').value;
	new AjaxUpload('upload_button', {
		data: { 'upload_key': key },
		responseType: 'json',
		action: wsURL+'uploadimage.php',
		onComplete: function(file, response) {
			var divTag = document.createElement("div");
			divTag.style.position = "relative";
			divTag.style.cssFloat = divTag.style.styleFloat = "left";
			divTag.id='tn_'+response.target_file;

			var imgTag = document.createElement("img");
			imgTag.src=response.target_url;
			
			var aTag = document.createElement("a");
			aTag.href='javascript:wsRemoveImage(\''+response.target_file+'\',\'tn_'+response.target_file+'\');';
			aTag.innerHTML='<img style="position:absolute;right:0px;top:0px;" src="'+wsURL+'../templates/default/images/delete.gif" height="16px" width="16px" />';
			
			var inputTag = document.createElement("input");
			inputTag.type='radio';
			inputTag.name='image_default';
			inputTag.value='tn_'+response.target_file;
			
			divTag.appendChild(imgTag);
			divTag.appendChild(aTag);
			divTag.appendChild(inputTag);
			$('uploaded_images').appendChild(divTag);

			}
	});
});

function wsDeleteImage(id) {
	var inputTag = document.createElement("input");
	inputTag.name='delimage[]';
	inputTag.value=id;
	inputTag.type='hidden';
	$('wsproduct').appendChild(inputTag);
	$(id).hide();
}