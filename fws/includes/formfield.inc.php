<?php
function wsIsGatewayField($data) {
	global $zing;
	$ret=array();
	$g=explode('-',$data['value']);
	if (empty($g[0])) {
		$ret['result']=0;
		$ret['error']=0;
	}
	elseif (isset($g[1])) {
		foreach ($zing->paths as $path) {
			$f=dirname($path).'/extensions/gateways/'.$g[0].'/config/'.$g[1].'.php';
			if (file_exists($f)) {
				require($f);
				if (in_array($data['params'],$gSettings)) $ret['result']=1;
				else $ret['result']=0;
				$ret['error']=0;
				break;
			}
		}
	} else {
		foreach ($zing->paths as $path) {
			$f=dirname($path).'/extensions/gateways/'.$g[0].'/config.php';
			if (file_exists($f)) {
				require($f);
				if (in_array($data['params'],$gSettings)) $ret['result']=1;
				else $ret['result']=0;
				$ret['error']=0;
				break;
			}
		}
	}
	return $ret;
}
