<?php if ($index_refer <> 1) { exit(); } ?>
<?php
class idealliteGateway {
	function idealliteGateway($payment,$customer) {
		$this->payment=$payment;
		$this->customer=$customer;
	}

	function calcHash() {
		$this->validity="2016-01-01T12:00:00:0000Z";
		$shastring = $this->payment->secret . $this->payment->merchantid . "0" . $this->payment->total_nodecimals. $this->payment->webid . "ideal" . $this->validity . "12345" . $this->payment->webid . "1". $this->payment->total_nodecimals;
		$clean_shaString = HTML_entity_decode($shastring);
		$not_allowed = array("\t", "\n", "\r", " ");
		$clean_shaString = str_replace($not_allowed, "",$clean_shaString);
		$this->hash = sha1($clean_shaString);
		return $this->hash;
	}
	
	function replace($payment_code) {
		$payment_code = str_replace("%validity%", $this->validity, $payment_code);
		return $payment_code;
	}
}
?>