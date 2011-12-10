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

		$taxtot=0;
		if (!isset($this->taxByCategory[$category])) $this->taxByCategory[$category]=array(); 

		if ($no_vat == 1) {
			$in_vat = $price;
			$ex_vat = $in_vat;
			$taxes=array(); 
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
					$totrate=0;
					$taxesCount=count($taxes);
					foreach ($taxes as $label => $tax) {
						$rate=$tax['RATE']/100;
						$cascading=$tax['CASCADING'];
						if (!$cascading || $taxesCount == 1) $totrate+=$rate;
						else $totrate+=(1+$totrate) * $rate;
					}
					$reprice=$price/(1+$totrate);
					foreach ($taxes as $label => $tax) {
						$rate=$tax['RATE']/100;
						$cascading=$tax['CASCADING'];
						if (!$cascading || $taxesCount == 1) $tax=$reprice * $rate;
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
		$this->inFtd=wsPrice::format($in_vat,false);
		$this->exFtd=wsPrice::format($ex_vat,false);

		if (is_array($taxes) && count($taxes) > 0) {
			foreach ($taxes as $label => $data) {
				$data['TAX']=myNumberRounding($data['TAX']);
				if (!isset($this->taxByCategory[$category][$label])) {
					$this->taxByCategory[$category]=array($label => $data);
					$this->combinations++;
				}
				else $this->taxByCategory[$category][$label]['TAX']+=$data['TAX'];
			}
		}
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

		$taxCategories=array();
		$db=new db();
		if ($db->select("select * FROM `##taxcategory`")) {
			while ($db->next()) {
				$taxCategories[$db->get('ID')]=$db->get('NAME');
			}
		}
		$query="select `ID`,`LABEL`,`CASCADING` FROM `".$dbtablesprefix."taxes` ORDER BY `ID`";
		$sql = mysql_query($query) or die(mysql_error());
		while ($tax = mysql_fetch_array($sql)) {
			$query_rates="select `RATE`,`TAXCATEGORYID` FROM `".$dbtablesprefix."taxrates` WHERE `TAXESID`='".$tax['ID']."' AND (`taxcategoryid`='".$category."' OR `taxcategoryid`='' OR `taxcategoryid` IS NULL) AND (`country`='".$country."' OR `country`='') AND (`state`='".$state."' OR `state`='') ORDER BY `taxcategoryid` DESC,`country` DESC,`state` DESC LIMIT 1";
			$sql_rates = mysql_query($query_rates) or die(mysql_error());
			while ($rates = mysql_fetch_array($sql_rates)) {
				$taxes[$tax['LABEL']]=array('RATE' => $rates['RATE'], 'CASCADING' => $tax['CASCADING'], 'CATEGORY' => $rates['TAXCATEGORYID'] ? $taxCategories[$rates['TAXCATEGORYID']] : '');
			}
		}
		return $taxes;

	}
}

class wsTaxSum extends wsTax {
	function wsTaxSum() {

	}

}

function displayTaxes($atax,$taxheader) {
	global $txt,$currency_symbol_pre,$currency_symbol_post,$taxheader;
	if (empty($taxheader)) $taxheader=$txt['checkout102'];
	$combinations=0;
	foreach ($atax as $tax) {
		if (count($tax->taxByCategory)>0 && $tax->tax!=0) {
			foreach ($tax->taxByCategory as $taxCategory => $taxes) {
				if (count($taxes>0)) {
					foreach ($taxes as $label => $data) {
						$combinations+=$tax->combinations;
					}
				}
			}
		}
	}
	foreach ($atax as $tax) {
		if (count($tax->taxByCategory)>0 && $tax->tax!=0) {
			foreach ($tax->taxByCategory as $taxCategory => $taxes) {
				if (count($taxes>0)) {
					foreach ($taxes as $label => $data) {
						echo '<tr>';
						if ($taxheader) {
							echo '<td rowspan="'.$combinations.'">'.$taxheader.'</td>';
						}
						echo '<td>'.$label.' '.$data['CATEGORY'].' '.$data['RATE'].'%</td><td style="text-align: right">'.wsPrice::currencySymbolPre().wsPrice::format($data['TAX']).wsPrice::currencySymbolPost().'</td>';
						if ($taxheader) {
							echo '<td rowspan="'.$combinations.'"></td>';
						}
						$taxheader=false;
						echo '</tr>';
					}
				}
			}
		}
	}
}
