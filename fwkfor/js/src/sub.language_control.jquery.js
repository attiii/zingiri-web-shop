appsLanguageControl={
	i : 0,
	editor : Array(),
	control : Array(),
	textTag : Array(),
	helperTag : Array(),
	node : Array(),
	content : Array(),
	lang : Array(),
	init: function(control,helper,field,editor) {

	//alert(control+'/'+helper+'/'+field+'/');
	//issue to solve is storage of multi lingual fields
		this.i++;
		this.editor[this.i]=editor;
		this.control[this.i]=jQuery('#'+control);
		this.textTag[this.i]=jQuery('#'+field);
		this.helperTag[this.i]=jQuery('#'+helper);
		lang=this.control[this.i].attr('value');
		this.content[this.i]=new Object();

		this.node[this.i]=jQuery('<div></div>');
		this.node[this.i].append(this.textTag[this.i].val());
		
		that=this;
		//alert(this.helperTag[this.i].attr('id'));
		if (this.helperTag[this.i].children('div').size()>0) { //or length()
			this.helperTag[this.i].children('div').each(function(j,lang) {
				that.content[that.i][lang.id]=jQuery('#'+lang.id).html();
				//alert(lang.id+'='+that.content[lang.id]);
			});
		} else {
			this.content[this.i][lang]=this.textTag[this.i].html();
			alert('content='+lang+'/'+this.textTag[this.i].html());
		}
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

		this.refresh(lang,this.i);
		
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
		
		jQuery('#'+control).bind('change',{that:this,i:this.i},function(e) {
			e.data.that.refresh(e.data.that.control[e.data.i].attr('value'),e.data.i); 
		});
	},
	
	refresh: function(newLang,i) {
		that=this;
		if (this.editor[i]==0) newContent=this.textTag[i].val();
		else newContent=this.textTag[i].parent().find('iframe').contents().find('body').html();
		if (this.lang[i] && this.content[i][this.lang[i]]==null) this.content[i][this.lang[i]]='';
		jQuery.each(this.content[i], function (lang,text) {
			if (lang==that.lang[i]) {
				that.content[i][lang]=newContent;
			}
		});
		if (this.editor[i]==1) {
			//alert(i+'/'+this.editor[i]+newLang+that.content[i][newLang]);
			if (that.content[i][newLang]) this.textTag[i].parent().find('iframe').contents().find('body').html(that.content[i][newLang]);
			else this.textTag[i].parent().find('iframe').contents().find('body').html('');
		} else {
			if (that.content[i][newLang]) that.textTag[i].val(that.content[i][newLang]);
			else that.textTag[i].val('');
		}
		this.lang[i]=newLang;
	}
};