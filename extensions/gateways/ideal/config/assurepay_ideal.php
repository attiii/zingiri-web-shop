<?php

$aSettings = array();

// Merchant ID or Email
$gSettings['']=$aSettings['MERCHANT_ID'] = 'MERCHANTID';

// Merchant Key or password
$gSettings[]=$aSettings['MERCHANT_KEY'] = 'SECRET';

// Your SHOP ID (for multiple shops in 1 account)
$gSettings[]=$aSettings['SHOP_ID'] = 'SUBID';

// Use TEST/LIVE mode; true=TEST, false=LIVE
$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

// Path to TEMP folder (This folder should not be accessable for webusers)
$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

// Basic gateway settings
$aSettings['GATEWAY_NAME'] = 'AssurePay - iDEAL';
$aSettings['GATEWAY_WEBSITE'] = 'http://www.assurepay.nl/';
$aSettings['GATEWAY_METHOD'] = 'ideal-assurepay';
$aSettings['GATEWAY_FILE'] = dirname(dirname(__FILE__)) . '/gateways/ideal-assurepay/gateway.cls.php';
$aSettings['GATEWAY_VALIDATION'] = true;

?>