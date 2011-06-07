<?php 
function z_($label) {
	global $txt,$txt2;
	
	$label=trim($label);
	if (isset($txt[$label])) return $txt[$label];

	if (count($txt2)==0) {
		$txt2=reorder_txt();
	}

	if (isset($txt2[$label])) {
		$key=$txt2[$label];
		if (isset($txt[$key])) return $txt[$key];
	}
	return $label;
}

function reorder_txt() {
	global $lang_dir,$zing;

	$txt2=array();
	include($lang_dir."/en/lang.txt");
	foreach ($txt as $label => $text) {
		$a=explode('<a href=# class=info>(?)<span>',$text);
		if (count($a) > 1) {
			$txt2[trim($a[0])]=$label;
			$helpLabel='help_'.$label;
			$helpText=trim(str_replace('</span></a>','',$a[1]));
			$txt2[$helpText]=$helpLabel;
		} else {
			$txt2[$text]=$label;
		}
	}
	foreach ($zing->paths as $wsPath) if (file_exists($wsPath.'langs/'.$lang.'/lang.php')) include($wsPath.'langs/'.$lang.'/lang.php');

	return $txt2;
}

function h_($label) {
	global $txt,$txt2;

	$label=trim($label);
	if (isset($txt['help_'.$label])) return 'what:'.$txt['help_'.$label];
	
	if (count($txt2)==0) {
		$txt2=reorder_txt();
	}
	
	if (isset($txt2[$label])) {
		$helpLabel='help_'.$txt2[$label];
		if (isset($txt[$helpLabel])) return $txt[$helpLabel];
	}
	return '';
}