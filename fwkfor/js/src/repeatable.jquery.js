appsRepeatable = {
	j : {},
	n : {},
	html : Array(),
	
	init : function(x) {
		var id='zf_'+x+'_sf';
		var max=1;
		this.html[x]=new Array();
		that=this;
		jQuery('#'+id).children().each(function(i, itemin) {
			if (itemin.id) {
				divTag=jQuery('#'+itemin.id);
				if (divTag.hasClass('zfsub')) {
					divTag.children('.element').each(function(k,input) {
						inputTag=jQuery('#'+input.id);
						that.html[x][i]=inputTag.html();
						inputTag.attr('data-pos',k+1);
						if ((k+1) > max) max=k+1;
						if (inputTag.attr('name') && inputTag.attr('name').indexOf('[]')<0) inputTag.attr('name',inputTag.attr('name')+'[]');
						inputTag.bind('keydown',that,function(event) {
							return event.data.tab(event);
				        });
					});
				}
			}
		});
		if (this.j.id==null) this.j.id=new Array();
		if (this.n.id==null) this.n.id=new Array();
		this.j.id[x]=this.n.id[x]=max;
	},

	del : function(x,pos) {
		var id='zf_'+x+'_sf';
		if (this.n.id[x]==1) {
			alert('You can\'t remove this element');
			return;
		}
		this.n.id[x]--;
		item=jQuery('#'+id);
		jQuery('#'+id).children().each(function(i, itemin) {
			if (itemin.id) {
				divTag=jQuery('#'+itemin.id);
				c=divTag.children('.element').size();
				if (divTag.hasClass('zfsub')) {
					divTag.children('.element').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('data-pos')==pos || (pos==1 && i==0)) {
							inputTag.remove();
						}
					});
				}
				if (divTag.hasClass('zftablecontrol')) {
					divTag.children('input').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('data-pos')==pos || (pos==1 && i==0)) {
							inputTag.remove();
						}
					});
				}
			}
		});

		
	},
	
	add : function(x,pos) {
		var id='zf_'+x+'_sf';
		this.j.id[x]++;
		this.n.id[x]++;
		that=this;
		var html="";
		
		item=jQuery('#'+id);
		jQuery('#'+id).children().each(function(k, itemin) {
			if (itemin.id) {
				divTag=jQuery('#'+itemin.id);
				if (divTag.hasClass('zfsub')) {
					divTag.children('.element').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('data-pos')==pos || (pos==1 && i==0)) {
							type=inputTag.get(0).tagName;
							if (type=='TEXTAREA') {
								newInputTag=jQuery("<textarea>");
								newInputTag.attr('rows',inputTag.attr('rows'));
								newInputTag.attr('cols',inputTag.attr('cols'));
							} else if (type=="SELECT") {
								newInputTag=jQuery("<select>");
							} else if (type=="select-one") {
								newInputTag=jQuery("<select>");
							} else if (type=="INPUT"){
								newInputTag=jQuery("<input>");
								newInputTag.attr('type',inputTag.attr('type'));
								if (inputTag.attr('type')=='button') newInputTag.val(inputTag.val());
							} else if (type=="DIV"){
								newInputTag=jQuery("<div>");
							} else {
								alert('unidentied tag in repeatable.jquery.js');
							}
							key=divTag.attr('id')+'_'+that.j.id[x];
							if (inputTag.attr('onclick')) newInputTag.attr('onclick',inputTag.attr('onclick'));
							newInputTag.attr('id',divTag.attr('id')+'_'+that.j.id[x]);
							newInputTag.attr('data-pos',that.j.id[x]);
							newInputTag.attr('class',inputTag.attr('class'));
							newInputTag.removeClass('hasDatepicker'); //remove datepicker class
							newInputTag.removeClass('hasTimepicker'); //remove timepicker class
							if (type!="DIV") newInputTag.html(that.html[x][k]);
							
							newInputTag.bind('keydown',that,function(event) {
								return event.data.tab(event);
					        });

							name=inputTag.attr('name');
							newInputTag.attr('name',name);
							if (inputTag.attr('size')>0) newInputTag.attr('size',inputTag.attr('size'));
							if (inputTag.attr('maxlength')>0) newInputTag.attr('maxlength',inputTag.attr('maxlength'));
							inputTag.after(newInputTag);
							if (inputTag.attr('data-js')) { 
								var evalStr=inputTag.attr('data-js');
								eval(evalStr);
							}
							if (inputTag.attr('data-jq')) { 
								var evalStr='newInputTag.'+inputTag.attr('data-jq');
								eval(evalStr);
							}
							if (k==0) newInputTag.focus();
							if (type=="textarea") {
								tinyMCE.addMCEControl(document.getElementById(newInputTag.attr('id')),newInputTag.attr('id'));
							}
						}
					});
				}
				if (divTag.hasClass('zftablecontrol')) {
					divTag.children('input').each(function(i,input) {
						inputTag=jQuery('#'+input.id);
						if (inputTag.attr('data-pos')==pos || (pos==1 && i==0)) {
							newInputTag=jQuery("<input>");
							newInputTag.attr('id',divTag.attr('id')+'_'+that.j.id[x]);
							if (inputTag.attr('value')=="+") action='appsRepeatable.add(\''+x+'\','+that.j.id[x]+')';
							else action='appsRepeatable.del(\''+x+'\','+that.j.id[x]+')';
							newInputTag.attr('onclick',action);
							newInputTag.attr('data-pos',that.j.id[x]);
							newInputTag.attr('class',inputTag.attr('class'));
							newInputTag.attr('value',inputTag.attr('value'));
							newInputTag.attr('type',inputTag.attr('type'));
							inputTag.after(newInputTag);
							//newInputTag.before(jQuery('<br />'));
						}
					});
				}
			}
		});
	},
	
	tab: function(event) {

		var t = jQuery(event.target);
		pos=t.attr('data-pos');
    	 if (event.keyCode == '9' && !event.shiftKey) {
    		 p=t.parent();
    		 s=p.next();
    		 if (s.hasClass('zfsub')) {
    			 s.children('input').each(function(i,input) {
    				 c=jQuery('#'+input.id);
    				 if (c.attr('data-pos')==pos) {
    					 c.focus();
    				 }
    			 });
    		 } else {
    			 s=p.parent().children().eq(0);
    			 s.children('input').each(function(i,input) {
    				 c=jQuery('#'+input.id);
    				 if (c.attr('data-pos')==pos) {
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
    		 if (s.hasClass('zfsub')) {
    			 s.children('input').each(function(i,input) {
    				 c=jQuery('#'+input.id);
    				 if (c.attr('data-pos')==pos) {
    					 c.focus();
    				 }
    			 });
    		 } else {
    			 s=p.parent().children().eq(-4);
    			 s.children('input').each(function(i,input) {
    				 c=jQuery('#'+input.id);
    				 if (c.attr('data-pos')==pos) {
    					 if (c.next() && c.prev().prev()) c.prev().prev().focus();
    					 else return true;
    				 }
    			 });
    		 }
        	 return false;
    	 }
	}
};

