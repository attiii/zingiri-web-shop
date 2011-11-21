<?php
class wsShipping {
	/*
	 * Get the weight id for the requested shipping id and weight or price
	 */
	function getWeightId($shippingid,$weight,$price,$quantity) {
		if ($w=$this->getWeightRecord($shippingid,$weight,$price,$quantity)) return $w['id'];
		else return null;
	}

	/*
	 * Get the available option for the requested shipping id and weight or price
	 */
	function getWeightOption($shippingid,$weight,$price,$quantity) {
		if ($w=$this->getWeightRecord($shippingid,$weight,$price,$quantity)) {
			return array($w['id'],$w['price']);
		}
		else return array(null,null);
	}

	/*
	 * Get the shipping costs for the selected weight id
	 */
	function getCosts($weightId=0) {
		if ($weightId) {
			$db=new db();
			$query = sprintf("SELECT * FROM `##shipping_weight` WHERE `ID` = %s", quote_smart($weightId));
			if ($db->select($query) && $db->next()) return $db->get('PRICE');
		}
		return 0;
	}

	/*
	 * Get the shipping description
	 */
	function getShippingDescription($shippingid) {
		$db=new db();
		$query = sprintf("SELECT * FROM `##shipping` WHERE `id` = %s", quote_smart($shippingid));
		if ($db->select($query) && $db->next()) return $db->get('DESCRIPTION');
		return '';
	}

	function hasRange($calculator) {
		if (in_array($calculator,array('WR','TR','QR'))) return true;
		else return false;
	}
	
	function hasOptions($calculator) {
		if (in_array($calculator,array('WR','TR','QR','FR'))) return 1;
		else return false;
	}
		
	/*
	 * Get the record for the requested shipping id and weight or price
	 */
	private function getWeightRecord($shippingid,$weight,$price,$quantity) {
		$w=null;
		$db=new db();
		$query = sprintf("SELECT * FROM `##shipping` WHERE `id` = %s", quote_smart($shippingid));
		if ($db->select($query) && $s=$db->next()) {
			$calculator=$db->get('CALCULATOR') ? $db->get('CALCULATOR') : 'WR';
			if ($calculator=='TR') $w=$this->getWeightIdAndPrice($shippingid,$price);
			elseif ($calculator=='WR') $w=$this->getWeightIdAndPrice($shippingid,$weight);
			elseif ($calculator=='QR') $w=$this->getWeightIdAndPrice($shippingid,$quantity);
			elseif ($calculator=='FR') $w=$this->getWeightIdAndPrice($shippingid,1);
			elseif (class_exists('wsShipping'.$calculator)) {
				$c='wsShipping'.$calculator;
				$s=new $c();
				$w=$s->getWeightIdAndPrice($shippingid,$price,$weight);
			} 

		}
		return $w;
	}

	/*
	 * Get weight id for selected shipping id and metric
	 */
	private function getWeightIdAndPrice($shippingid,$metric) {
		$w=null;
		$db=new db();
		$query2 = "SELECT * FROM `##shipping_weight` WHERE '".$metric."' >= `FROM` AND '".$metric."' <= `TO` AND `SHIPPINGID` = '".$shippingid."'";
		if ($db->select($query2) && $db->next()) {
			$w=array('id'=>$db->get('id'),'price'=>$db->get('price'));
		}
		return $w;

	}
}

