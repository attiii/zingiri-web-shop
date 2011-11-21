<?php
class wsDiscount {
	var $code;
	var $percentage=0;
	var $amount=0;
	var $base=0;
	var $discount=0;
	var $expiryqty=0;

	function wsDiscount($code="") {
		$this->code=trim($code);
	}

	function exists() {
		$error=0;
		$today=date('Y-m-d');
		$db=new db();

		//check if code exists and is valid
		$query="SELECT * FROM `##discount` WHERE `code` = '".$this->code."'";
		if ($db->select($query)) {
			$db->next();
			//check if old style
			if ($db->get('createdate')!='' && $db->get('orderid')!=0) $error=2;
			//or new style
			elseif ($db->get('createdate')=='') {
				if ($db->get('expirydate')!='' && $db->get('expirydate')<$today) $error=3;
				elseif ($db->get('expiryqty')>0 && $this->countUsed()>=$db->get('expiryqty')) $error=4;
			}
		} else $error=1;

		//the code is valid, let's see if we can apply it to the cart
		if ($error==0) {
			$this->base=$this->cartTotal($db->get('catid'),$db->get('productid'));
			if ($this->base==0) $error=5;
			else {
				$this->amount=$db->get('amount');
				$this->percentage=$db->get('percentage');
				$this->expiryqty=$db->get('expiryqty');
			}
		}

		//		echo 'error='.$error;
		if ($error) return false; else return true;
	}

	function countUsed() {
		$db=new db();
		$query="select ID from ##order where discountcode=".qs($this->code);
		$count=$db->select($query);
		return $count;
	}

	function cartTotal($catid,$productid) {
		global $customerid;
		$db=new db();
		if ($catid>0) $query="select sum(qty*##basket.price) as total from ##basket,##product where ##basket.productid=##product.id and ##product.catid=".qs($catid)." and status=0 and customerid=".qs($customerid);
		elseif ($productid>0) $query="select sum(qty*price) as total from ##basket where productid=".qs($productid)." and status=0 and customerid=".qs($customerid);
		else $query="select sum(qty*price) as total from ##basket where status=0 and customerid=".qs($customerid);
		$db->select($query);
		$db->next();
		return $db->get('total');
	}

	function calculate() {
		if ($this->base>0) {
			if ($this->percentage>0) $this->discount=myNumberRounding($this->percentage/100*$this->base);
			else $this->discount=wsPrice::price($this->amount,false);
		}
	}
	
	function countActive() {
		$db=new db();
		$query="SELECT `CODE` FROM `##discount`";
		return $db->select($query);
	}
	
	function consume() {
		if ($this->expiryqty > 0 && ($this->expiryqty - $this->countUsed()) <= 1) {
			$db=new db();
			$db->update("update `##discount` set `status`=9 where `code`=".qs($this->code));
		}
	}
}
?>