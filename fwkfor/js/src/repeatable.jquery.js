appsRepeatable = {
	j : {},
	n : {},
	
	init : function(id) {
		this.j.id=this.n.id=jQuery('#'+id).children().eq(0).children('input').size();
		that=this;
		jQuery('#'+id).children().each(function(i, itemin) {
			if (itemin.id) {
				divTag=jQuery('#'+itemin.id);
				if (divTag.attr('class')=='zfsub') {
					divTag.children('input').each(function(k,input) {
						inputTag=jQuery('#'+input.id);
						inputTag.attr('pos',k+1);
						if (inputTag.attr('name').indexOf('[]')<0) inputTag.attr('name',inputTag.attr('name')+'[]');
						inputTag.bind('keydown',that,function(event) {
							return event.data.tab(event);
				        });
					});
				}
			}
		});
	},

	del : function(id,pos) {
		if (this.n.id==1) {
			alert('You can\'t remove this element');
			return;
		}
		this.n.id--;
		item=jQuery('#'+id);
		jQuery('#'+id).children().each(function(i, itemin) {
			if (itemin.id) {
				divTag=jQuery('#'+itemin.id);
				c=divTag.children('input').size();
				if (divTag.attr('class')=='zfsub') {
					divTag.children('input').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('pos')==pos || (pos==1 && i==0)) {
							if (i>0) inputTag.prev().remove();
							else inputTag.next().remove();
							inputTag.remove();
						}
					});
				}
				if (divTag.attr('class')=='zftablecontrol') {
					divTag.children('input').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('pos')==pos || (pos==1 && i==0)) {
							if (i>0) inputTag.prev().remove();
							else inputTag.next().remove();
							inputTag.remove();
						}
					});
				}
			}
		});

		
	},
	
	add : function(id,pos) {
		this.j.id++;
		this.n.id++;
		that=this;
		var html="";
		item=jQuery('#'+id);
		jQuery('#'+id).children().each(function(k, itemin) {
			if (itemin.id) {
				divTag=jQuery('#'+itemin.id);
				if (divTag.attr('class')=='zfsub') {
					divTag.children('input').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('pos')==pos || (pos==1 && i==0)) {
							newInputTag=jQuery("<input>");
							key=divTag.attr('id')+'_'+that.j.id;
							newInputTag.attr('id',divTag.attr('id')+'_'+that.j.id);
							newInputTag.attr('pos',that.j.id);
							newInputTag.attr('class',inputTag.attr('class'));
							newInputTag.attr('type',inputTag.attr('type'));
							newInputTag.bind('keydown',that,function(event) {
								return event.data.tab(event);
					        });

							name=inputTag.attr('name');
							newInputTag.attr('name',name);
							if (inputTag.attr('size')>0) newInputTag.attr('size',inputTag.attr('size'));
							if (inputTag.attr('maxlength')>0) newInputTag.attr('maxlength',inputTag.attr('maxlength'));
							inputTag.after(newInputTag);
							newInputTag.before(jQuery('<br />'));
							if (k==0) newInputTag.focus();
						}
					});
				}
				if (divTag.attr('class')=='zftablecontrol') {
					divTag.children('input').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('pos')==pos || (pos==1 && i==0)) {
							newInputTag=jQuery("<input>");
							newInputTag.attr('id',divTag.attr('id')+'_'+that.j.id);
							if (inputTag.attr('value')=="+") action='appsRepeatable.add(\''+id+'\','+that.j.id+')';
							else action='appsRepeatable.del(\''+id+'\','+that.j.id+')';
							newInputTag.attr('onclick',action);
							newInputTag.attr('pos',that.j.id);
							newInputTag.attr('class',inputTag.attr('class'));
							newInputTag.attr('value',inputTag.attr('value'));
							newInputTag.attr('type',inputTag.attr('type'));
							inputTag.after(newInputTag);
							newInputTag.before(jQuery('<br />'));
						}
					});
				}
			}
		});
	},
	
	tab: function(event) {

		var t = jQuery(event.target);
		pos=t.attr('pos');
    	 if (event.keyCode == '9' && !event.shiftKey) {
    		 p=t.parent();
    		 s=p.next();
    		 if (s.attr('class')=='zfsub') {
    			 s.children('input').each(function(i,input) {
    				 c=jQuery('#'+input.id);
    				 if (c.attr('pos')==pos) {
    					 c.focus();
    				 }
    			 });
    		 } else {
    			 s=p.parent().children().eq(0);
    			 s.children('input').each(function(i,input) {
    				 c=jQuery('#'+input.id);
    				 if (c.attr('pos')==pos) {
    					 if (c.next() && c.next().next()) c.next().next().focus();
    					 else return true;
    				 }
    			 });
    		 }
        	 return false;
    	 }
    	 if (event.keyCode == '9' && event.shiftKey) {
    		 p=t.parent();
    		 s=p.prev();
    		 if (s.attr('class')=='zfsub') {
    			 s.children('input').each(function(i,input) {
    				 c=jQuery('#'+input.id);
    				 if (c.attr('pos')==pos) {
    					 c.focus();
    				 }
    			 });
    		 } else {
    			 s=p.parent().children().eq(-4);
    			 s.children('input').each(function(i,input) {
    				 c=jQuery('#'+input.id);
    				 if (c.attr('pos')==pos) {
    					 if (c.next() && c.prev().prev()) c.prev().prev().focus();
    					 else return true;
    				 }
    			 });
    		 }
        	 return false;
    	 }
	}
};

