<?php

$aSettings = array();

// Merchant ID
$gSettings[]=$aSettings['MERCHANT_ID'] = 'MERCHANTID';

// Basic gateway settings
$aSettings['GATEWAY_NAME'] = 'ABN AMRO - iDEAL Easy';
$aSettings['GATEWAY_WEBSITE'] = 'http://www.abnamro.nl/';
$aSettings['GATEWAY_METHOD'] = 'ideal-easy';
$aSettings['GATEWAY_FILE'] = dirname(dirname(__FILE__)) . '/gateways/ideal-easy/gateway.cls.php';
$aSettings['GATEWAY_VALIDATION'] = false;

?>