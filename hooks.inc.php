<?php
if (!function_exists('wsHooks')) {
	function wsHooks($name,$data) {
		global $wsHooks;
		
		if (count($wsHooks[$name]) > 0) {
			foreach ($wsHooks[$name] as $hook) {
				$data=$hook($data);
			}			
		}
		return $data;
	}

	function wsAddHook($name,$function) {
		global $wsHooks;
		
		if (!$wsHooks[$name]) $wsHooks[$name]=array();
		$wsHooks[$name][]=$function;
	}
}
