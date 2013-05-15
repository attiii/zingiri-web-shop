aphpsFwkFor={};

aphpsFwkFor.editForm = function(formName,recId,reload) {
	var that=this;
	if (jQuery('#popupDialogStep').length==0) {
		jQuery('body').append('<div id="popupDialogStep">');
	}
	if (jQuery('.aphpsSpinner').length) jQuery(".aphpsSpinner").show();

	jQuery('#popupDialogStep').dialog({
		modal: true,
		autoOpen: false,
		width: 600,
		create: function(event,ui) {
			
		},
		buttons: {
		    'Save': function() {
		    	var data=jQuery('#aphpsform').serialize();
		    	that.editFormSave(formName,recId,data,reload);
		    }
		}
	});

	this.editFormLoad(formName,recId);
};

aphpsFwkFor.editFormLoad = function(formName,recId) {
	var data;
	data={};
	data.module='player';
	data.formname=formName;
	data.action='edit';
	data.id=recId;
	new jQuery.ajax({
		url : aphpsAjaxURL+"form",
		type : "post",
		data : data,
		success : function(request) {
			if (jQuery('.aphpsSpinner').length) jQuery(".aphpsSpinner").hide();
			var js = eval("(" + request + ")");
			jQuery('#popupDialogStep').html(js.html);
			jQuery('#popupDialogStep').dialog( "open" );
		}
	});
};

aphpsFwkFor.editFormSave = function(formName,recId,data,reload) {
	var that=this;
	if (jQuery('.aphpsSpinner').length) jQuery(".aphpsSpinner").show();

	new jQuery.ajax({
		url : aphpsAjaxURL+"form",
		type : "post",
		data : data+'&module=player&formname='+formName+'&step=save',
		success : function(request) {
			var js = eval("(" + request + ")");
			if (js.success) {
				if (reload) location.reload();
			} else { 
				if (jQuery('.aphpsSpinner').length) jQuery(".aphpsSpinner").hide();
				jQuery('#popupDialogStep').html(js.html);
			}
		}
	});
};