<?php
function wsIsGatewayField($data) {
	$ret=array();
	$g=explode('-',$data['value']);
	if (empty($g[0])) {
		$ret['result']=0;
		$ret['error']=0;
	}
	elseif (isset($g[1])) {
		require(ZING_LOC.'extensions/gateways/'.$g[0].'/config/'.$g[1].'.php');
		if (in_array($data['params'],$gSettings)) $ret['result']=1;
		else $ret['result']=0;
		$ret['error']=0;
	} else {
		require(ZING_LOC.'extensions/gateways/'.$g[0].'/config.php');
		if (in_array($data['params'],$gSettings)) $ret['result']=1;
		else $ret['result']=0;
		$ret['error']=0;
	}
	return $ret;
}