<?php

$aSettings = array();

// Mollie Partner ID
$gSettings[]=$aSettings['PARTNER_ID'] = 'MERCHANTID';

// Mollie Profile ID/KEY
$gSettings[]=$aSettings['PROFILE_KEY'] = 'SECRET'; 

// Basic gateway settings
$aSettings['GATEWAY_NAME'] = 'Mollie - iDEAL';
$aSettings['GATEWAY_WEBSITE'] = 'http://www.mollie.nl/';
$aSettings['GATEWAY_METHOD'] = 'ideal-mollie';
$aSettings['GATEWAY_FILE'] = dirname(dirname(__FILE__)) . '/gateways/ideal-mollie/gateway.cls.php';
$aSettings['GATEWAY_VALIDATION'] = true;

?>