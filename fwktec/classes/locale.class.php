<?php
class fwktec_locale {
	static function formatAmount($amount) {
		$moneyFormat=fwktec_locale::getLocale('moneyformat');
		switch ($moneyFormat) {
			case '1.234,00':
				$decPoint=',';
				$thousandsSep='.';
				$precision=2;
				break;
			default:
				$decPoint='.';
				$thousandsSep=',';
				$precision=2;
				break;
		}
		return number_format($amount,$precision,$decPoint,$thousandsSep);
	}

	static function formatMoney($amount) {
		$currencySymbol=fwktec_locale::getLocale('currencysymbol');
		return $currencySymbol.fwktec_locale::formatAmount($amount);
	}

	function getLocale($setting) {
		global $fwktec_settings_cache;
		if (!$fwktec_settings) {
			$db=new aphpsDb();
			$query=sprintf('select * from ##flocale where id=1');
			if ($db->select($query)) $fwktec_settings_cache=$db->next();
		}
		if ($fwktec_settings_cache[$setting]) return $fwktec_settings_cache[$setting];
		elseif ($fwktec_settings_cache[strtolower($setting)]) return $fwktec_settings_cache[strtolower($setting)];
		elseif ($fwktec_settings_cache[strtoupper($setting)]) return $fwktec_settings_cache[strtoupper($setting)];
	}

}

