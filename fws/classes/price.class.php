<?php
class wsPrice {
	
	function price($price,$nice=true) {
		if (class_exists('wsMultiCurrency')) {
			$mc=new wsMultiCurrency();
			return $mc->price($price,$nice);
		}
		else return $price;
	}

	function currencySymbolPre() {
		global $currency_symbol_pre;
		if (class_exists('wsMultiCurrency')) {
			$mc=new wsMultiCurrency();
			return $mc->currencySymbolPre();
		}
		return $currency_symbol_pre;
	}

	function currencySymbolPost() {
		global $currency_symbol_post;
		if (class_exists('wsMultiCurrency')) {
			$mc=new wsMultiCurrency();
			return $mc->currencySymbolPost();
		}
		return $currency_symbol_post;
	}

	function format($price,$ccy=null) {
		if (class_exists('wsMultiCurrency')) {
			$mc=new wsMultiCurrency();
			return $mc->format($price,$ccy);
		}
		else return myNumberFormat($price);
	}
	
	function formatWithSymbol($price,$ccy=null) {
		global $currency_symbol_post,$currency_symbol_pre;
		if (class_exists('wsMultiCurrency')) {
			$mc=new wsMultiCurrency();
			return $mc->formatWithSymbol($price,$ccy);
		}
		else return $currency_symbol_pre.myNumberFormat($price).$currency_symbol_post;
	}
	
	function ccy() {
		global $currency;
		if (class_exists('wsMultiCurrency')) {
			$mc=new wsMultiCurrency();
			return $mc->ccy();
		}
		else return $currency;
	}
}