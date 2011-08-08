<?php
/*
class wsTheme {
	var $db;

	function wsTheme($keys=null) {
		global $zing_loaded;
		require(ZING_GLOBALS);
		if (!$zing_loaded)
		{
			require (ZING_LOC."./startmodules.inc.php");
			$zing_loaded=TRUE;
		} else {
			require (ZING_DIR."./includes/readvals.inc.php");        // get and post values
		}

	}

	function getCategoryId($category) {
		$this->db=new db();
		if ($this->db->select("select `id` from `##category` where `desc`=".qs($category))) {
			if ($this->db->next()) {
				$catid=$this->db->get('ID');
				$this->db->select("select * from `##product`,`##category` where catid='".$catid."'");
				return true;
			}
		} else return false;
	}

	function getCategories() {
		$cat=array();
		$db=new db();
		if ($db->select("select `##group`.`name`,`##category`.`desc`,`##category`.`id` from `##group`,`##category` where `##category`.`groupid`=`##category`.`id` order by `##group`.`sortorder`,`##group`.`name`,`##category`.`sortorder`,`##category`.`desc`")) {
			while ($row=$db->next()) {
				$cat[$db->get('id')]['name']=$db->get('name').'-'.$db->get('desc');
			}
		}	
		$this->categories=$cat;
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

*/