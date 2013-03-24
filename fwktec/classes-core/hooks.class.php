<?php
class aphpsHooks {
	static function doAction($action) {
		global $aphps_projects;
		$params=func_get_args();
		array_shift($params); //removes $action
		if (count($params)==1) $params=$params[0];
		if (isset($aphps_projects['fwktec']['_hooks'][$action])) {
			$actions=$aphps_projects['fwktec']['_hooks'][$action];
			asort($actions);
			foreach ($actions as $priority => $actionsByPriority) {
				foreach ($actionsByPriority as $action) {
					if (is_array($action)) {
						$o=new $action[0];
						$f=$action[1];
						return $o->$f($params);
					} else {
						$f=$action;
						return $f($params);
					}
				}
			}
			
		}
	}	
	
	static function registerAction($action,$functionOrClass,$priority=100) {
		global $aphps_projects;
		
		$aphps_projects['fwktec']['_hooks'][$action][$priority][]=$functionOrClass;
		
	}
}
