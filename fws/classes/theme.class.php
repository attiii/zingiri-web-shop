<?php
class wsTheme {
	var $db;

	function wsTheme($keys=null) {
		global $zing_loaded;
		require(ZING_GLOBALS);
		if (!$zing_loaded)
		{
			require (ZING_LOC."./zing.startmodules.inc.php");
			$zing_loaded=TRUE;
		} else {
			require (ZING_DIR."./includes/readvals.inc.php");        // get and post values
		}

		$this->db=new db();
		if (!is_null($keys)) {
			return $this->db->selectFromArray('product',$keys);
		}
	}

	function getCategoryId($category) {
		$this->db=new db();
		if ($this->db->select("select `id` from `##category` where `desc`=".qs($category))) {
			if ($this->db->next()) return $this->db->get('id');
		}
		return false;
	}

	function have_products() {
		$row=$this->db->next();
		return $row;
	}

	function the_product() {
		return true;
	}

	function the_product_title() {
		return $this->db->get('productid');
	}

	function the_product_permalink() {
		$url=get_option('home').'/index.php?page=details&prod='.$this->db->get('id');
		return $url;
	}

	function the_product_description() {
		return $this->db->get('description');
	}

	function the_product_id() {
		return $this->db->get('id');
	}

	function get_product_meta($id,$key) {
		switch ($key) {
			case 'product_picture':
				return wsProductImage($id,$this->db->get('defaultimage'));
		}
		return true;
	}
	
	function the_product_image() {
		return wsProductImage($this->db->get('id'),$this->db->get('defaultimage'));
	}
	
	function product_is_donation() {
		return false;
	}
	
	function product_on_special() {
		return false;
	}
	
	function product_normal_price() {
		return $this->db->get('price');	
	}
	
	function the_product_price() {
		return $this->db->get('price');	
	}
}
