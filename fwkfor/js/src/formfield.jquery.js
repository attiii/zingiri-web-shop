var wsFormField = {
	add : function(rule,field,result) {
		var e=jQuery('#element_'+rule.field+'_'+rule.subField);
		rule.value=e.val();
		this.ajax(rule, field);

		e.bind('change', this, function(v) {v.data.change(v,rule,field); });
	},

	change : function(v,rule,field) {
		var e = jQuery(v.target);
		rule.value=e.val();
		this.ajax(rule, field);
	},
	
	ajax : function(rule,field) {
		var that=this;
		if (jQuery('.aphpsSpinner').length) jQuery('.aphpsSpinner').show();
		new jQuery.ajax({
			url : aphpsAjaxURL + "form_field",
			type : "post",
			data : { 'cms' : wsCms, 'wsData' : rule, 'mod' : 'fwkfor', 'action' : 'aphps_ajax'},
			success : function(request) {
				if (jQuery('.aphpsSpinner').length) jQuery('.aphpsSpinner').hide();
				a=eval("(" + request + ")");
				if (a.error == 0) {
					if (a.result == rule.compare) that.action(rule,field,true);
					else that.action(rule,field,false);
				}
			}
		});
	},
	
	action : function(rule,field,result) {
		if (rule.action=='hide' && result) {
			jQuery('#zf_'+field).hide(); 
		} else if (rule.action=='hide' && !result) {
			jQuery('#zf_'+field).show(); 
		} else if (rule.action=='show' && result) {
			jQuery('#zf_'+field).show(); 
		} else if (rule.action=='show' && !result) {
			jQuery('#zf_'+field).hide(); 
		} else if (rule.action=='showonly' && result) {
			jQuery('#zf_'+field).show(); 
		} else if (rule.action=='hideonly' && result) {
			jQuery('#zf_'+field).hide(); 
		}
	}
}