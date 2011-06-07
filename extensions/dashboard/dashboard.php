<?php
/*  dashboard.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Web Shop.

 Zingiri Web Shop is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Web Shop is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php

//$zing->addToDashboard('projectedRevenue');

function projectedRevenue() {
	global $txt;
	//Projected revenue
	echo '<div id="dashboard_projectedRevenue" class="dashboard" style="height=1000px">';
	$date=array();
	$db=new db();
	$sql="select substr(duedate,1,7) as month,frequency,productid,currency,count(*) as quantity from ##package where status = '20' group by substr(duedate,1,7),frequency,productid,currency order by substr(duedate,1,7)";
	$db->select($sql);
	while ($row=$db->next()) {
		$price=new price($db->get('productid'),$db->get('frequency'),$db->get('currency'));
		$d=date($db->get('month').'-01');
		$enddate=add_date(date('Y-m-01'),12);
		if ($db->get('frequency')) {
			while ($d <= $enddate) {
				$month=substr($d,0,7);
				$data[$db->get('currency')][$month]+=$price->in_vat*$db->get('quantity');
				$d=add_date($d,$db->get('frequency'));
			}
		}

	}
	if (count($data)>0) {
		ksort($data);
		foreach ($data as $ccy => $set) {
			paint_chart($txt['dashboard'].' '.$ccy,"Month",$ccy,$set);
		}
	}
	echo '</div>';

}
?>