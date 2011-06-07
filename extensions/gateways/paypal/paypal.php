<?php if ($index_refer <> 1) { exit(); } ?>
<?php
class paypalGateway {
	function paypalGateway($payment,$customer) {
		$this->payment=$payment;
		$this->customer=$customer;
	}

	function calcHash() {
	}

	function replace($payment_code) {
		global $sales_mail;
		
		if (isset($_REQUEST['wslive'])) {
			$payment_code = str_replace("%ipn%", ZING_HOME.'/index.php?page=ipn&gateway=paypal&wslive=1', $payment_code);
		} else {
			$payment_code = str_replace("%ipn%", ZING_URL.'extensions/gateways/paypal/ipn.php', $payment_code);
		}
		if (!empty($this->payment->merchantid)) $payment_code = str_replace("%paypal_email%", $this->payment->merchantid, $payment_code);
		else $payment_code = str_replace("%paypal_email%", $sales_mail, $payment_code);
		return $payment_code;
	}
}
?>