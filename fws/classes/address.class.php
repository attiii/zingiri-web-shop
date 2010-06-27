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
			$addresses[0]=array('MIDDLENAME'=>$db->get('middlename'),'INITIALS'=>$db->get('initials'),'LASTNAME'=>$db->get('lastname'),'NAME'=>$db->get('initials').' ' .$db->get('lastname'),'ADDRESS'=>$db->get('address'),'CITY'=>$db->get('city'),'STATE'=>$db->get('state'),'ZIP'=>$db->get('zip'),'COUNTRY'=>$db->get('country'));
		}
		if ($db->select("select * from ##address where customerid=".qs($this->customerid))) {
			while ($db->next()) {
				$addresses[$db->get('id')]=array('MIDDLENAME'=>'','INITIALS'=>$db->get('name_first'),'LASTNAME'=>$db->get('name_last'),'NAME'=>$db->get('name_first').' ' .$db->get('name_last'),'ADDRESS'=>$db->get('address_street'),'CITY'=>$db->get('address_city'),'STATE'=>$db->get('address_state'),'ZIP'=>$db->get('address_zip'),'COUNTRY'=>$db->get('address_country'));
			}
		}
		return $addresses;
	}

	function getAddress($id=0) {
		if (empty($id)) $id=0;
		$addresses=$this->getAddresses();
		return $addresses[$id];
	}

	function displayAddress($id) {
		$address=$this->getAddress($id);
		$text=$address['NAME'].'<br />';
		$text.=$address['ADDRESS'].'<br />';
		$text.=$address['CITY'].' '.$address['ZIP'].'<br />';
		if (!empty($address['STATE'])) $text.=$address['STATE'].'<br />';
		$text.=$address['COUNTRY'].'<br />';
		return $text;
	}


}
?>