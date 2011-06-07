<?php

$aSettings = array();

// Merchant ID
$gSettings[]=$aSettings['MERCHANT_ID'] = 'MERCHANTID';

// Your iDEAL Sub ID
$gSettings[]=$aSettings['SUB_ID'] = 'SUBID';

// Use TEST/LIVE mode; true=TEST, false=LIVE
$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

// Password used to generate private key file
$gSettings[]=$aSettings['PRIVATE_KEY_PASS'] = 'SECRET';

// Name of your PRIVATE-KEY-FILE (should be located in /certificates/)
$aSettings['PRIVATE_KEY_FILE'] = 'private.key';

// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /certificates/)
$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'private.cer';

// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
$aSettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

// Path to TEMP folder (This folder should not be accessable for webusers)
$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

// Basic gateway settings
$aSettings['GATEWAY_NAME'] = 'Fortis Bank - iDEAL Integrated';
$aSettings['GATEWAY_WEBSITE'] = 'http://www.fortisbank.nl/';
$aSettings['GATEWAY_METHOD'] = 'ideal-professional';
$aSettings['GATEWAY_FILE'] = dirname(dirname(__FILE__)) . '/gateways/ideal-professional/gateway.cls.php';
$aSettings['GATEWAY_VALIDATION'] = true;

?>