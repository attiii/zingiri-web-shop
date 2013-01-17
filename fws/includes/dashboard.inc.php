<?php
function display_table($caption,$headers,$rows=null,$sql=null) {
	global $txt;

	echo '<table class="dashboard" width="100%">';
	echo '<caption>'.$caption.'</caption>';
	foreach ($headers as $header) {
		echo '<th>'.$header.'</th>';
	}

	if ($sql) {
		$db=new db();
		$db->select($sql);
		while ($row=$db->next()) {
			$rows[]=$row;
		}
	}
	if (count($rows) > 0) {
		foreach ($rows as $row) {
			echo '<tr>';
			foreach ($row as $field) {
				echo '<td>'.$field.'</td>';
			}
			echo '</tr>';
		}
	} else {
		echo '<tr><td>'.$txt['dashboard7'].'</td></tr>';
	}
	echo '</table>';

}

class dashboardStats {

	var $stats;
	var $caption;

	function __construct($caption) {
		$this->caption=$caption;
	}

	function add($key,$sql,$href,$format="") {
		global $currency_symbol_pre,$currency_symbol_post;
		$db=new db();
		$db->select($sql);
		$row=$db->next();
		if ($format=="amount") $value=$currency_symbol_pre.myNumberFormat($row['result']).$currency_symbol_post;
		else $value=$row['result'];
		$this->stats[$key]=array('href' => $href, 'value' => $value);
	}

	function display() {
		global $href,$txt;
		echo '<table class="dashboard">';
		echo '<caption>'.$this->caption.'</caption>';
		foreach ($this->stats as $key => $stat) {
			echo '<tr>';
			if ($stat['href']=="#") $h="javascript:void(0);";
			else $h='?page='.$stat['href'];
			echo '<td><a href="'.$h.'">'.$key.'</a></td>';
			echo '<td>'.$stat['value'].'</td>';
			echo '</tr>';
		}
		echo '</table>';
	}


}
