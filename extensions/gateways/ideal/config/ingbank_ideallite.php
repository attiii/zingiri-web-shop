<?php

$aSettings = array();

// Merchant ID
$gSettings[]=$aSettings['MERCHANT_ID'] = 'MERCHANTID';

// Your iDEAL Sub ID
$gSettings[]=$aSettings['SUB_ID'] = 'SUBID';

// Use TEST/LIVE mode; true=TEST, false=LIVE
$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

// Password used to generate hash
$gSettings[]=$aSettings['HASH_KEY'] = 'SECRET';

// Basic gateway settings
$aSettings['GATEWAY_NAME'] = 'ING Bank - iDEAL Basic';
$aSettings['GATEWAY_WEBSITE'] = 'http://www.ingbank.nl/';
$aSettings['GATEWAY_METHOD'] = 'ideal-lite';
$aSettings['GATEWAY_FILE'] = dirname(dirname(__FILE__)) . '/gateways/ideal-lite/gateway.cls.php';
$aSettings['GATEWAY_VALIDATION'] = false;

?>