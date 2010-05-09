var wsControlPanel = Class.create( {

	initialize : function() {
		var e = $$('.zing_cp_icon');
		//alert('here'+e.length);
		for (i = 0; i < e.length; i++) {
			//e[i].type = 'button';
			e[i].observe('mouseover', this.iconGrow.bindAsEventListener(this));
			e[i].observe('mouseout', this.iconShrink.bindAsEventListener(this));
		//	e[i].observe('click', this.iconClick.bindAsEventListener(this));
		}
		;
	},

	iconGrow : function(v) {
		var e = Event.element(v);
		e.height=48;
		e.width=48;
		var label=$('icon_label');
		var mouseX=Event.pointerX(v);
		label.innerHTML=e.alt;
		label.setStyle({'position' : 'absolute', 'left' : mouseX+'px'});
		label.show();
	},

	iconShrink : function(v) {
		var e = Event.element(v);
		e.height=32;
		e.width=32;
		$('icon_label').hide();
	}

});

document.observe("dom:loaded", function() {
	new wsControlPanel();
});