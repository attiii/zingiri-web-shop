<?php
class wsFeatures {
	var $features=array();
	var $headers;
	var $setid;
	var $sets=1;
	var $prefil=array();
	var $tableClass='borderless';
	var $productid=0;
	var $formulaRule;
	var $formulaType;
	var $index;
	var $basePrice;
	var $productFeatures;
	var $featureString;
	var $definition;
	var $setType;
	var $post;

	function wsFeatures($features=array(),$header='',$set='',$productid=0) {
		if (count($features)>0) $this->setFeatures($features,$header,$set);
		$this->productid=$productid;
		$this->post=$_POST;
	}

	function setFeatures($features,$header='',$set='') {
		if ($set && !$features) {
			$this->features=$this->readDefinition($set);
		} else {
			$this->features=$features;
		}
		if ($header) {
			$this->headers=explode(',',$header);
			$this->sets=count($this->headers);
			$this->tableClass='wsfeaturestable';
		} else {
			$this->tableClass='wsfeaturestable';
		}
	}

	function readDefinition($set) {
		$definition='';
		$db=new db();
		if ($db->select('select * from ##featureset where id='.qs($set))) {
			$db->next();
			$definition=$db->get('definition');
			$this->setType=$db->get('type');
		}
		return $definition;
	}
	
	function setProduct($productid) {
		$this->productid=$productid;
	}

	function setDefinition($definition,$set='') {
		if ($set && !$definition) {
			$this->definition=$this->readDefinition($set);
		} else {
			$this->definition=$definition;
		}
	}

	function prepare($index=0) {
	}

	function calcPrice($index=0,$price,$formulaType='',$formulaRule='') {
		$this->formulaType=$formulaType;
		$this->formulaRule=$formulaRule;
		$this->index=$index;
		$this->basePrice=$price;
		$prodprice=0;
		$productfeatures='';
		if ($formulaType=='custom' && !empty($formulaRule) && method_exists($this,'calcFormula')) {
			$prodprice=$this->calcFormula();
			$productfeatures=$this->featureString;
		} else {
			$prodprice=$price;
			if (!empty($this->features)) {
				$features = explode("|", $this->features);
				$counter1 = 0;
				while (isset($features[$counter1]) && (!$features[$counter1] == NULL)) {
					$feature = explode(":", $features[$counter1]);
					$counter1 += 1;
					$detail = explode("+", aphpsSanitize($this->post["wsfeature".$counter1][$index]));
					$productfeatures .= $feature[0].": ".$detail[0];
					if (isset($detail[1])) $prodprice += wsPrice::price($detail[1]);
					if (!empty($features[$counter1])) {
						$productfeatures .= ", ";
					}
				}
			}
		}
		$this->price=$prodprice;
		$this->featureString=$productfeatures;
		return $prodprice;
	}

	function toString($features='') {
		if ($features) {
			if (substr($features,0,1)=='&') return substr($features,1);
			$this->features=$features;
		}

		if (!empty($this->features) && !empty($this->definition)) {
			$productfeatures='';
			$features = explode(",", $this->features);
			$definitions = explode("|", $this->definition);
			$counter1 = 0;
			while (isset($features[$counter1])){
				$feature = explode(":", $features[$counter1]);
				$definition = explode(":", $definitions[$counter1]);
				$counter1 += 1;
				if (isset($definition[1]) && $definition[1]=='file' && trim($feature[1])) {
					$productfeatures .= $definition[0].': <a href="'.ZING_UPLOADS_URL.'orders/'.trim($feature[1]).'">'.trim($feature[1]).'</a>';
				} else {
					$productfeatures .= $definition[0].":".$feature[1];
				}
				if (!empty($features[$counter1])) {
					$productfeatures .= ", ";
				}
			}
			return $productfeatures;
		} else return $this->features;
	}

	function displayFeatures($display=true) {
		$output='';
		$db=new db();
		$qty=$this->sets;
		$r=0;
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
			while (isset($features[$counter1]) && (!$features[$counter1] == NULL)) {
				$output.= "<tr>";
				$feature = explode(":", $features[$counter1]);
				$counter1 += 1;
				$output.= '<td>'.$feature[0].": </td>";
				for ($i=0;$i<$qty;$i++) {
					$output.= "<td>";
					if (isset($feature[1]) && $feature[1]=='file' && method_exists('wsFeaturesFile','outputFile')){
						$output.=wsFeaturesFile::outputFile($counter1,$i,$r);
					} elseif (!isset($feature[1])){
						$output.= '<input type="text" class="wsfeature" name="wsfeature'.$counter1.'[]" value="'.(isset($this->prefil[$i]['features'][$r]) ? $this->prefil[$i]['features'][$r] : '').'" > ';
					} else {
						$output.= '<select class="wsfeature" name="wsfeature'.$counter1.'[]">';
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
							while (isset($value[$counter2]) && (!$value[$counter2] == NULL)) {

								// optionally you can specify the additional costs: color:red+1.50,green+2.00,blue+3.00 so lets deal with that
								$extracosts = explode("+",$value[$counter2]);
								if (isset($extracosts[1]) && (!$extracosts[1] == NULL)) {
									// there are extra costs
									$printvalue = $extracosts[0]." (+".wsPrice::currencySymbolPre().wsPrice::format(wsPrice::price($extracosts[1])).wsPrice::currencySymbolPost().")";
								}
								else {
									$printvalue = $value[$counter2];
								}

								// print the pulldown menu
								$printvalue = str_replace("+".wsPrice::currencySymbolPre()."-", "-".wsPrice::currencySymbolPre(), $printvalue);
								$option=explode('+',$value[$counter2]);
								$selected='';
								if (isset($this->prefil[$i]['features'][$r])) {
									if (trim($this->prefil[$i]['features'][$r])==trim($option[0])) $selected='selected="selected"';
								} else {
									if ($counter2 == 0) $selected='selected="selected"';
								}
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
						if (isset($values1[1])) {
							$values2=explode('+',$values1[1]);
							$prefil[$r]['features'][]=trim($values2[0]);
						} else $prefil[$r]['features'][]='';
					}
				}
				$prefil[$r]['qty']=$row['QTY'];
				$prefil[$r]['id']=$row['ID'];
				$r++;
			}
		}
		$this->prefil=$prefil;
	}

	function validate($index=0) {
		//not implemented in basic version
	}
}
