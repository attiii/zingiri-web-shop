var wsCheckout = Class.create( {

	initialize : function() {
	},
	checkout : function() {
		$('shipping').observe('change', this.refreshPage.bindAsEventListener(this));
		$('paymentid').observe('change', this.refreshPage.bindAsEventListener(this));
		$('discount_code').observe('change', this.refreshPage.bindAsEventListener(this));
	},
	refreshPage : function() {
		$('checkout').action = '?page=onecheckout';
		$('checkout').submit();
	}
	
});

var wsSubmit = Class.create( {

	initialize : function() {
		var autosubmit=$('autosubmit');
		if (autosubmit) {
			autosubmit.target='';
			autosubmit.submit();
		}
	}
	
});