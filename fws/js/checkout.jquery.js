/*
function wsRefreshPage() {
	alert('hello');
	jQuery('#checkout').action = '?page=onecheckout';
	jQuery('#checkout').submit();
}

jQuery(document).ready(function() {
	jQuery('#paymentid').change(function () {
		jQuery('#checkout').action = '?page=onecheckout';
		jQuery('#checkout').submit()
	});
	jQuery('#shipping').change(function () { 
		jQuery('#checkout').action = '?page=onecheckout';
		jQuery('#checkout').submit()
	;});
	
});
*/

function wsSubmit()
{
	// force load in same page, by default it will open a new page
	var autosubmit = jQuery('#autosubmit');
	if (autosubmit) {
		autosubmit.target = '';
		autosubmit.submit();
	}
}