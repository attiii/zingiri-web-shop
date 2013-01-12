<?php
$tmp=$content;
$i=0;
while ($i<100 && preg_match('/\[zing-ws-(.*):(.*)\]/',$tmp,$matches)==1) { //[zing-ws-action:parameters]
	$parsed='';
	switch ($matches[1]) {
		case 'show-category':
			$cat=$matches[2];
			if ($prods_per_row = wsSetting('productsperrow')); else $prods_per_row=3;
			$row_count = 0;
			$f_query = "SELECT `##product`.* FROM `##product`,`##category` WHERE `##product`.`CATID`=`##category`.`ID` AND (`##category`.`ID` = ".qs($matches[2])." OR `##category`.`DESC`=".qs($matches[2]).")";
			$f_query = str_replace('##',$dbtablesprefix,$f_query);
			$f_sql = mysql_query($f_query) or die(mysql_error());
			if (mysql_num_rows($f_sql) != 0) {
				$parsed.="<div style=\"text-align:center;\">";
				$parsed.="<br />";
				$parsed.='<table width="100%" class="borderless" style="width:100%">';
				while ($f_row = mysql_fetch_array($f_sql)) {
					$row_count++;
					$parsed.=wsShowProductCell($f_row,$row_count,$prods_per_row);
					if ($row_count == $prods_per_row) { $row_count = 0; }
				}
				$parsed.="</table></div>";
			}
				
			break;
		default:
			$parsed='';
	}

	$tmp=str_replace($matches[0],$parsed,$tmp);
	$i++;
}
echo $tmp;
?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	jQuery(document).ready(function() {
	    wsFrontPage=true;
			wsCart.order();
	});
//]]>
</script>