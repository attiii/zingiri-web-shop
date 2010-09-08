<?php
//uploadadmin.php

class wsFeatures {
	var $features=array();
	var $headers;
	var $setid;
	var $sets=1;
	var $prefil=array();
	var $tableClass='borderless';
	var $productid=0;

	function wsFeatures($features=array(),$header='',$productid=0) {
		if (count($features)>0) $this->setFeatures($features,$header);
		$this->productid=$productid;
	}

	function setFeatures($features,$header='') {
		$this->features=$features;
		/*
		 if (substr($features,0,1)=='&') {
			$tmp1=explode('|',$features);
			$tmp2=explode(':',$tmp1[0]);
			$tmp3=explode(',',$tmp2[1]);
			$this->sets=count($tmp3);
			$this->tableClass='wsfeaturestable';
			}
			*/
		if ($header) {
			$this->headers=explode(',',$header);
			$this->sets=count($this->headers);
			$this->tableClass='wsfeaturestable';
		} else {
			$this->tableClass='wsfeaturestable';
		}
	}

	function setProduct($productid) {
		$this->productid=$productid;
	}

	function calcPrice($index=0) {
		$prodprice=0;
		$productfeatures='';

		if (!empty($this->features)) {
			$features = explode("|", $this->features);
			$counter1 = 0;
			while (!$features[$counter1] == NULL){
				$feature = explode(":", $features[$counter1]);
				$counter1 += 1;
				if (!empty($_POST["$feature[0]"][$index])) {
					$detail = explode("+", $_POST["$feature[0]"][$index]);
					$productfeatures .= $feature[0].": ".$detail[0];
					$prodprice += $detail[1];
				}
				if (!empty($features[$counter1])) {
					$productfeatures .= ", ";
				}
			}
		}
		$this->price=$prodprice;
		$this->featureString=$productfeatures;
	}

	function toString($features) {
		if (substr($features,0,1)=='&') return substr($features,1);
		return $features;
	}

	function displayFeatures($display=true) {
		global $currency_symbol_pre,$number_format,$currency_symbol_post;
		$output='';
		$db=new db();
		$qty=$this->sets;
		$r=0;
		//echo $this->features;
		//print_r($this->headers);
		if (count($this->headers) > 0) {
			$output.= '<tr><th></th>';
			foreach ($this->headers as $header) {
				$output.= '<th>'.$header.'</th>';
			}
			$output.= '</tr>';
		}
		if (!empty($this->features)) {
			$features = explode("|", $this->features);
			$counter1 = 0;
			while (!$features[$counter1] == NULL){
				$output.= "<tr>";
				$feature = explode(":", $features[$counter1]);
				$counter1 += 1;
				$output.= '<td>'.$feature[0].": </td>";
				for ($i=0;$i<$qty;$i++) {
					$output.= "<td>";
					if (!isset($feature[1])){
						$output.= "<input type=\"text\" name=\"".$feature[0]."[]\" value=\"".$this->prefil[$i]['features'][$r]."\" > ";
					} else {
						$output.= '<select name="'.$feature[0].'[]">';
						if ($feature[1]=='?') {
							$counter2 = 0;
							$field="features_f".sprintf('%02d',$r+1);
							$query="select distinct(`".$field."`) from `##productfeatures` where `productid`=".qs($this->productid);
							if ($db->select($query)) {
								while ($db->next()) {
									$selected='';
									if (isset($this->prefil[$i]['features'][$r])) {
										if (trim($this->prefil[$i]['features'][$r])==trim($db->get($field))) $selected='selected="selected"';
									} else {
										if ($counter2 == 0) $selected='selected="selected"';
									}
									$output.= '<option value="'.$db->get($field).'" '.$selected.'>'.$db->get($field).'</option>';
									$counter2++;
								}
							}
						} else {
							$value = explode(",", $feature[1]);
							$counter2 = 0;
							while (!$value[$counter2] == NULL){

								// optionally you can specify the additional costs: color:red+1.50,green+2.00,blue+3.00 so lets deal with that
								$extracosts = explode("+",$value[$counter2]);
								if (!$extracosts[1] == NULL) {
									// there are extra costs
									$printvalue = $extracosts[0]." (+".$currency_symbol_pre.myNumberFormat($extracosts[1],$number_format).$currency_symbol_post.")";
								}
								else {
									$printvalue = $value[$counter2];
								}

								// print the pulldown menu
								$printvalue = str_replace("+".$currency_symbol_pre."-", "-".$currency_symbol_pre, $printvalue);
								$option=explode('+',$value[$counter2]);
								$selected='';
								if (isset($this->prefil[$i]['features'][$r])) {
									if (trim($this->prefil[$i]['features'][$r])==trim($option[0])) $selected='selected="selected"';
								} else {
									if ($counter2 == 0) $selected='selected="selected"';
								}
								//$printvalue.='+'.$option[0].'='.$this->prefil[$i]['features'][$r].'/'.$selected;
								$output.= '<option value="'.$value[$counter2].'" '.$selected.'>'.$printvalue.'</option>';
								$counter2 += 1;
							}
						}
						$output.= "</select>";
					}
					$output.= "</td>";
				}

				$output.= '</tr>';
				$r++;
			}
		}
		if ($display) echo $output;
		return $output;
	}

	function setFeaturesFromBasketId($basketid=0) {
		global $dbtablesprefix,$customerid;

		$prefil=array();

		if ($basketid) {
			$r=0;
			//read basket details
			$query = sprintf("SELECT `SET` FROM `".$dbtablesprefix."basket` where `CUSTOMERID`=%s AND `ID`=%s", qs($customerid),qs($basketid));
			$sql = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_array($sql);
			$this->setid=$setid=$row['SET'];
			//get features
			$query = sprintf("SELECT `FEATURES`,`QTY`,`ID` FROM `".$dbtablesprefix."basket` where `CUSTOMERID`=%s AND `SET`=%s ORDER BY `ID`", qs($customerid),qs($setid));
			$sql = mysql_query($query) or die(mysql_error());
			while ($row = mysql_fetch_array($sql)) {
				$features=explode(",",$row['FEATURES']);
				if (count($features)>0) {
					foreach ($features as $i => $feature) {
						$values1=explode(':',$feature);
						$values2=explode('+',$values1[1]);
						$prefil[$r]['features'][]=trim($values2[0]);
					}
				}
				$prefil[$r]['qty']=$row['QTY'];
				$prefil[$r]['id']=$row['ID'];
				$r++;
			}
		}
		$this->prefil=$prefil;
	}

	function calcTotalPrice($allfeatures) {
		$prodprice=0;
		return $prodprice;
		//not useful since the cart stores the price including the features

		if (empty($allfeatures)) $allfeatures=$this->features;
		if (!empty($allfeatures)) {
			$features = explode("|", $allfeatures);
			$counter1 = 0;
			while (!$features[$counter1] == NULL){
				$feature = explode(":", $features[$counter1]);
				$counter1 += 1;
				if (!empty($_POST["$feature[0]"])) {
					$detail = explode("+", $_POST["$feature[0]"]);
					$prodprice += $detail[1];
				}
			}
		}
		return $prodprice;
	}
}
