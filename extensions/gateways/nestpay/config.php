<?php
$gSettings[]= 'MERCHANTID';
$gSettings[] = 'SECRET';

// Use TEST/LIVE mode; true=TEST, false=LIVE
$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

// Basic gateway settings
$aSettings['GATEWAY_NAME'] = 'Nestpay';
$aSettings['GATEWAY_VALIDATION'] = false;
