<?php
class zforderdetail extends zfForm {
	function postSave() {
		$orderid=$this->rec['ORDERID'];
		$db=new db();
		$order=$db->readRecord('order',array('ID'=>$orderid));
		if ($this->action=='edit') $order['TOPAY']-=$this->before['PRICE'];
		$order['TOPAY']+=$this->rec['PRICE'];
		$db->updateRecord('order',array('ID'=>$orderid),$order);
		return true;
	}
	
	function postDelete() {
		$orderid=$this->before['ORDERID'];
		$db=new db();
		$order=$db->readRecord('order',array('ID'=>$orderid));
		$order['TOPAY']-=$this->before['PRICE'];
		$db->updateRecord('order',array('ID'=>$orderid),$order);
		return true;
	}
}
?>