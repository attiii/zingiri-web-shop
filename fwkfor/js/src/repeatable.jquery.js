appsRepeatable = {
	j : {},
	n : {},
	html : Array(),
	
	init : function(id) {
		var max=1;
		that=this;
		jQuery('#'+id).children().each(function(i, itemin) {
			if (itemin.id) {
				divTag=jQuery('#'+itemin.id);
				if (divTag.attr('class')=='zfsub') {
					divTag.children('.element').each(function(k,input) {
						inputTag=jQuery('#'+input.id);
						that.html[i]=inputTag.html();
						inputTag.attr('pos',k+1);
						if ((k+1) > max) max=k+1;
						if (inputTag.attr('name').indexOf('[]')<0) inputTag.attr('name',inputTag.attr('name')+'[]');
						inputTag.bind('keydown',that,function(event) {
							return event.data.tab(event);
				        });
					});
				}
			}
		});
		this.j.id=this.n.id=max;
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
				c=divTag.children('.element').size();
				if (divTag.attr('class')=='zfsub') {
					divTag.children('.element').each(function(i,input) {
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
					divTag.children('.element').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('pos')==pos || (pos==1 && i==0)) {
							//type=inputTag.attr('type');
							type=inputTag.get(0).tagName;
							if (type=='TEXTAREA') {
								newInputTag=jQuery("<textarea>");
								newInputTag.attr('rows',inputTag.attr('rows'));
								newInputTag.attr('cols',inputTag.attr('cols'));
							} else if (type=="SELECT") {
								newInputTag=jQuery("<select>");
							} else if (type=="select-one") {
								alert('select-one in repeatable.jquery.js?')
								newInputTag=jQuery("<select>");
							} else if (type=="INPUT"){
								newInputTag=jQuery("<input>");
								newInputTag.attr('type',inputTag.attr('type'));
							} else {
								alert('unidentied tag in repeatable.jquery.js');
							}
							key=divTag.attr('id')+'_'+that.j.id;
							newInputTag.attr('id',divTag.attr('id')+'_'+that.j.id);
							newInputTag.attr('pos',that.j.id);
							newInputTag.attr('class',inputTag.attr('class'));
							newInputTag.html(that.html[k]);
							
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
							if (type=="textarea") {
								//alert ('reloading for '+newInputTag.attr('id'));
								tinyMCE.addMCEControl(document.getElementById(newInputTag.attr('id')),newInputTag.attr('id'));
							}

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

