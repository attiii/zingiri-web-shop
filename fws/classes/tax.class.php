<?php
class wsTax {
	var $ex;
	var $in;
	var $exSum=0;
	var $inSum=0;
	var $taxByCategory=array();
	var $combinations=0;
	
	function wsTax($price,$category=0) {
		$this->calculate($price,$category);
	}
	
	function calculate($price,$category=0) {
		global $dbtablesprefix,$no_vat,$db_prices_including_vat;

		//global $vat;
		$taxtot=0;
		if (!isset($this->taxByCategory[$category])) $this->taxByCategory[$category]=0;

		if ($no_vat == 1) {
			$in_vat = $price;
			$ex_vat = $in_vat;
		}
		else {
			$taxes=$this->selectTaxes($category);
			$subtot=$price;
			if (count($taxes) > 0) {
				if (!$db_prices_including_vat) {
					foreach ($taxes as $label => $tax) {
						$rate=$tax['RATE']/100;
						$cascading=$tax['CASCADING'];
						if (!$cascading) $tax=$price * $rate;
						else $tax=$subtot * $rate;
						$taxes[$label]['TAX']=$tax;
						$subtot+=$tax;
						$taxtot+=$tax;
					}
				} else {
					foreach ($taxes as $label => $tax) {
						$rate=$tax['RATE']/100;
						$cascading=$tax['CASCADING'];
						if (!cascading) $totrate+=$rate;
						else $totrate+=(1+$totrate) * $rate;
					}
					$reprice=$price/(1+$totrate);
					foreach ($taxes as $label => $tax) {
						$rate=$tax['RATE']/100;
						$cascading=$tax['CASCADING'];
						if (!$cascading) $tax=$reprice * $rate;
						else $tax=$subtot * $rate;
						$taxes[$label]['TAX']=$tax;
						$subtot+=$tax;
						$taxtot+=$tax;
					}
				}
			}
			if ($db_prices_including_vat == 1) {
				$ex_vat = $price / (1+$totrate);
				$in_vat = $price;
				$taxtot = $in_vat - $ex_vat;
			}
			else {
				$in_vat = $price + $taxtot;
				$ex_vat =$price;
			}
		}
		$this->ex=myNumberRounding($ex_vat);
		$this->in=myNumberRounding($in_vat);
		$this->tax=myNumberRounding($taxtot);
		$this->taxes=$taxes;
		$this->inFtd=myNumberFormat($in_vat);
		$this->exFtd=myNumberFormat($ex_vat);
		
		foreach ($taxes as $label => $data) {
			$data['TAX']=myNumberRounding($data['TAX']);
			if (!isset($this->taxByCategory[$category][$label])) {
				$this->taxByCategory[$category]=array($label => $data);
				$this->combinations++;
			}
			else $this->taxByCategory[$category][$label]['TAX']+=$data['TAX'];
		}
		//$this->taxByCategory[$category]=$this->tax;
		$this->exSum+=$this->ex;
		$this->inSum+=$this->in;
	}

	function selectTaxes($category) {
		global $dbtablesprefix, $customerid;

		$taxes=array();

		$customer=new wsCustomer();
		if (!$customer->loggedin) {
			$country="";
			$state="";
		} else {
			$country=$customer->data['COUNTRY'];
			$state=$customer->data['STATE'];
		}

		$query="select `ID`,`LABEL`,`CASCADING` FROM `".$dbtablesprefix."taxes` ORDER BY `ID`";
		$sql = mysql_query($query) or die(mysql_error());
		while ($tax = mysql_fetch_array($sql)) {
			$query_rates="select `RATE` FROM `".$dbtablesprefix."taxrates` WHERE `TAXESID`='".$tax['ID']."' AND (`taxcategoryid`='".$category."' OR `taxcategoryid`='' OR `taxcategoryid` IS NULL) AND (`country`='".$country."' OR `country`='') AND (`state`='".$state."' OR `state`='') ORDER BY `taxcategoryid` DESC,`country` DESC,`state` DESC LIMIT 1";
			$sql_rates = mysql_query($query_rates) or die(mysql_error());
			while ($rates = mysql_fetch_array($sql_rates)) {
				$taxes[$tax['LABEL']]=array('RATE' => $rates['RATE'], 'CASCADING' => $tax['CASCADING']);
			}
		}
		return $taxes;

	}
}

class wsTaxSum extends wsTax {
	function wsTaxSum() {
		
	}

}

?>