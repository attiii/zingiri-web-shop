<?php

$aSettings = array();

// TargetPay Layout Code
$gSettings[]=$aSettings['LAYOUT_CODE'] = 'MERCHANTID';

// Use TEST/LIVE mode; true=TEST, false=LIVE
$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

// Basic gateway settings
$aSettings['GATEWAY_NAME'] = 'TargetPay - iDEAL';
$aSettings['GATEWAY_WEBSITE'] = 'http://www.targetpay.nl/';
$aSettings['GATEWAY_METHOD'] = 'ideal-targetpay';
$aSettings['GATEWAY_FILE'] = dirname(dirname(__FILE__)) . '/gateways/ideal-targetpay/gateway.cls.php';
$aSettings['GATEWAY_VALIDATION'] = true;

?>