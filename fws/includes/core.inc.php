<?php
function calcFeaturesPrice($allfeatures) {
	$prodprice=0;

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
?>