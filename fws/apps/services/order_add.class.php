<?php
class zforder_add extends zfForm {
	function SaveDB($id=0) {
		$row=$this->makeRow($id);
		$row['WEIGHT']=1234;
		$keys=array();
		$id=InsertRecord($this->entity,$keys,$row,"");
		return $id;
	}
}
?>