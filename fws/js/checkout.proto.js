function wsSubmit()
{
	// force load in same page, by default it will open a new page
	var autosubmit = $('autosubmit');
	if (autosubmit) {
		autosubmit.target = '';
		autosubmit.submit();
	}
}