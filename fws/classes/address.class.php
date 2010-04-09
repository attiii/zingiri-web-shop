<?php
class wsAddress {
	
	function wsAddress($customerid=0) {
		$this->customerid=$customerid;
	}
	
	function getAddresses() {
		$addresses=array();
		$db=new db();
		if ($db->select("select * from ##customer where id=".qs($this->customerid))) {
			$db->next();
			$addresses[0]=array('NAME'=>$db->get('initials').' ' .$db->get('lastname'),'ADDRESS'=>$db->get('address'),'CITY'=>$db->get('city'),'STATE'=>$db->get('state'),'ZIP'=>$db->get('zip'),'COUNTRY'=>$db->get('country'));
		}
		if ($db->select("select * from ##address where customerid=".qs($this->customerid))) {
			while ($db->next()) {
				$addresses[$db->get('id')]=array('NAME'=>$db->get('name_first').' ' .$db->get('name_last'),'ADDRESS'=>$db->get('address_street'),'CITY'=>$db->get('address_city'),'STATE'=>$db->get('address_state'),'ZIP'=>$db->get('address_zip'),'COUNTRY'=>$db->get('address_country'));
			}
		}
		return $addresses;
	}
	
	function getAddress($id=0) {
		$addresses=$this->getAddresses();
		return $addresses[$id];
	}
}
?>