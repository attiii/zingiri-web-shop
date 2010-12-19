<?php

$aSettings = array();

// Merchant ID
$gSettings[]=$aSettings['PSP_ID'] = 'MERCHANTID';

// Use TEST/LIVE mode; true=TEST, false=LIVE
$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

// Password used to generate/validate hashes
$gSettings[]=$aSettings['SHA_1_IN'] = 'SECRET';
$gSettings[]=$aSettings['SHA_1_OUT'] = 'SECRET';

// Basic gateway settings
$aSettings['GATEWAY_NAME'] = 'Rabobank - iDEAL Internet Kassa';
$aSettings['GATEWAY_WEBSITE'] = 'http://www.rabobank.nl/';
$aSettings['GATEWAY_METHOD'] = 'ideal-internetkassa';
$aSettings['GATEWAY_FILE'] = dirname(dirname(__FILE__)) . '/gateways/ideal-internetkassa/gateway.cls.php';
$aSettings['GATEWAY_VALIDATION'] = false;

?>