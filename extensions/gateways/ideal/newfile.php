<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = 'TESTiDEALEASY';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'ABN AMRO - iDEAL Easy';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.abnamro.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-easy';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-easy/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = false;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate private key file
		$aSettings['PRIVATE_KEY_PASS'] = '';

		// Name of your PRIVATE-KEY-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_KEY_FILE'] = 'private.key';

		// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'private.cer';

		// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
		$aSettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

		// Path to TEMP folder (This folder should not be accessable for webusers)
		$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'ABN AMRO - iDEAL Zelfbouw';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.abnamro.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-professional';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-professional/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID or Email
		$aSettings['MERCHANT_ID'] = '';

		// Merchant Key or password
		$aSettings['MERCHANT_KEY'] = '';

		// Your SHOP ID (for multiple shops in 1 account)
		$aSettings['SHOP_ID'] = '';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Path to TEMP folder (This folder should not be accessable for webusers)
		$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'AssurePay - iDEAL';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.assurepay.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-assurepay';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-assurepay/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate hash
		$aSettings['HASH_KEY'] = '';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'Fortis Bank - iDEAL Hosted';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.fortisbank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-lite';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-lite/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = false;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate private key file
		$aSettings['PRIVATE_KEY_PASS'] = '';

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
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-professional/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate hash
		$aSettings['HASH_KEY'] = '';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'Friesland Bank - iDEAL Zakelijk';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.frieslandbank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-lite';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-lite/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = false;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate private key file
		$aSettings['PRIVATE_KEY_PASS'] = '';

		// Name of your PRIVATE-KEY-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_KEY_FILE'] = 'private.key';

		// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'private.cer';

		// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
		$aSettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

		// Path to TEMP folder (This folder should not be accessable for webusers)
		$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'Friesland Bank - iDEAL Zakelijk Plus';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.frieslandbank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-professional';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-professional/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '123456789';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate private key file
		$aSettings['PRIVATE_KEY_PASS'] = 'Password';

		// Name of your PRIVATE-KEY-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_KEY_FILE'] = 'private.key';

		// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'private.cer';

		// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
		$aSettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

		// Path to TEMP folder (This folder should not be accessable for webusers)
		$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'iDEAL Simulator - iDEAL Professional';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.ideal-simulator.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-professional';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-professional/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '123456789';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate private key file
		$aSettings['HASH_KEY'] = 'Password';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'iDEAL Simulator - iDEAL Lite';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.ideal-simulator.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-lite';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-lite/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = false;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate private key file
		$aSettings['PRIVATE_KEY_PASS'] = '';

		// Name of your PRIVATE-KEY-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_KEY_FILE'] = 'private.key';

		// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'private.cer';

		// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
		$aSettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

		// Path to TEMP folder (This folder should not be accessable for webusers)
		$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'ING Bank - iDEAL Advanced';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.ingbank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-professional';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-professional/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate hash
		$aSettings['HASH_KEY'] = '';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'ING Bank - iDEAL Basic';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.ingbank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-lite';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-lite/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = false;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Mollie Partner ID
		$aSettings['PARTNER_ID'] = '';

		// Mollie Profile ID/KEY
		$aSettings['PROFILE_KEY'] = '';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'Mollie - iDEAL';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.mollie.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-mollie';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-mollie/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['PSP_ID'] = '';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate/validate hashes
		$aSettings['SHA_1_IN'] = '';
		$aSettings['SHA_1_OUT'] = '';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'Rabobank - iDEAL Internet Kassa';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.rabobank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-internetkassa';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-internetkassa/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = false;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate hash
		$aSettings['HASH_KEY'] = '';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'Rabobank - iDEAL Lite';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.rabobank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-lite';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-lite/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = false;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Password used to generate private key file
		$aSettings['PRIVATE_KEY_PASS'] = '';

		// Name of your PRIVATE-KEY-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_KEY_FILE'] = 'private.key';

		// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'private.cer';

		// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
		$aSettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

		// Path to TEMP folder (This folder should not be accessable for webusers)
		$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'Rabobank - iDEAL Professional';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.rabobank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-professional';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-professional/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>
<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// TargetPay Layout Code
		$aSettings['LAYOUT_CODE'] = '';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$gSettings[]=$aSettings['TEST_MODE'] = 'TESTMODE';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'TargetPay - iDEAL';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.targetpay.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-targetpay';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-targetpay/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>