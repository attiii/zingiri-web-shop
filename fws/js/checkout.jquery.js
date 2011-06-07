function wsSubmit()
{
	// force load in same page, by default it will open a new page
	var autosubmit = jQuery('#autosubmit');
	if (autosubmit) {
		autosubmit.attr('target','');
		autosubmit.submit();
	}
}