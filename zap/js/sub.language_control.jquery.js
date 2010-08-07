appsLanguageControl={
	init: function(control,helper,field,editor) {

		this.editor=editor;
		this.control=jQuery('#'+control);
		this.textTag=jQuery('#'+field);
		this.helperTag=jQuery('#'+helper);
		lang=this.control.attr('value');
		this.content=new Object();

		this.node=jQuery('<div></div>');
		this.node.append(this.textTag.val());
		
		that=this;
		this.helperTag.children('div').each(function(i,lang) {
			that.content[lang.id]=jQuery('#'+lang.id).html();
//			alert(lang.id+'='+that.content[lang.id]);
		});
		//alert(this.content.en);
		//alert(this.node.find('#en').html());
		/*
		try {
			this.content=jQuery.secureEvalJSON(this.textTag.val());
		}
		catch(err) {
			this.content=new Object();
			this.content[lang]=this.textTag.html();
			}
			*/
		//alert(this.textTag.text());

		this.refresh(lang);
		
		jQuery('#appscommit').bind('click',this,function(e) {
			e.data.refresh('');
			//json=jQuery.toJSON(e.data.content);
			//alert(json);
			that.helperTag.html('');
			e.data.textTag.css('color','white');
			
			jQuery()
			jQuery.each(that.content, function (lang,text) {
				divTag=jQuery('<div id="'+lang+'">'+text+'</div>');
				that.helperTag.append(divTag);
			});
			//alert(that.helperTag.html());
			e.data.textTag.val(that.helperTag.html());
			if (e.data.editor==1) e.data.textTag.parent().find('iframe').contents().find('body').html(that.helperTag.html());
		});
		
		jQuery('#'+control).bind('change',this,function(e) {
			e.data.refresh(e.data.control.attr('value')); 
		});
	},
	
	refresh: function(newLang) {
		that=this;
		if (this.editor==0) newContent=this.textTag.val();
		else newContent=this.textTag.parent().find('iframe').contents().find('body').html();
		if (this.lang && that.content[this.lang]==null) that.content[this.lang]='';
		jQuery.each(this.content, function (lang,text) {
			if (lang==that.lang) {
				that.content[lang]=newContent;
			}
		});
		//if (!this.editor) {
			if (that.content[newLang]) that.textTag.val(that.content[newLang]);
			else that.textTag.val('');
		//} 
		if (this.editor==1) {
			//alert('here:'+newLang+'/'+that.content[newLang]);
			if (that.content[newLang]) this.textTag.parent().find('iframe').contents().find('body').html(that.content[newLang]);
			else this.textTag.parent().find('iframe').contents().find('body').html('');
		}
		this.lang=newLang;
	}
};