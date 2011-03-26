<?php
$formid=$_GET['formid'];
$formname=$_GET['form'] ? $_GET['form'] : zfGetForm($_GET['formid']);
$zfp=intval($_GET['zfp']);
$zft=$_GET['zft'];
$pos=intval($_GET['pos']);
$mapflat=$_GET['map'];
//$mapflat=stripslashes($_GET['map']);

$json=str_replace("\'",'"',$_GET['map']);
$json=str_replace("'",'"',$_GET['map']);
//echo $json;
$map=zf_json_decode($json,true);
//print_r($map);

if (class_exists('zf'.$formname)) $zfClass='zf'.$formname;
else $zfClass='zfForm';
$zflist=new $zfClass($formname,$formid,'','list','list');
$formname=$zflist->form;
$formid=$zflist->id;
if ($action=='search') {
	$search=$zflist->setSearch($_POST,$map);
}

$stack=new zfStack('list',$formname,$search);

if (ZING_CMS=='gn') echo '<h2 class="zfaces-form-label">'.z_($zflist->label).'</h2>';
elseif (is_admin()) echo '<p class="zfaces-form-label">'.z_($zflist->label).'</p>';
$map=$zflist->filter($map);
if (!$zflist->allowAccess()) {
	if (function_exists('fwktecError')) fwktecError($zflist->errorMessage); else echo $zflist->errorMessage;
	return false;
}
if (file_exists(ZING_APPS_CUSTOM.'apps.'.$formname.'.php')) require(ZING_APPS_CUSTOM.'apps.'.$formname.'.php');
//search fields
echo '<form name="faces" method="POST" action="?page='.$page.'&zfaces=list&form='.$formname.'&action=search';
echo '&zft=form&zfp='.$formid.'">';
echo '<ul id="zfaces" class="zfaces">';
$zflist->Prepare();
$zflist->Render("search");
echo '</ul>';
if ($zflist->searchable) {
	echo '<center><input class="art-button" type="submit" name="search" value="'.z_('Search').'"></center>';
}
echo '</form>';

$tlink=new zfLink($zflist->id,false,'list','T');
$links=$tlink->getLinks();
$topspan='';
if ($links) {
	$topspan='';
	foreach ($links as $i => $link) {
		if ($link['FORMOUTALT']) $topspan.='<a class="art-button" href="'.zurl('?'.$link['FORMOUTALT'].'&id='.$id.'&map='.$link['MAP'].'&orderkeys='.urlencode($_REQUEST['orderkeys']).$search.'&zft=list&zfp='.$formid.'" alt="'.$link['ACTION']).'">'.ucfirst($link['ACTION']).'</a>';
		else $topspan.='<a class="art-button" href="'.zurl('?page='.$page.'&zfaces='.$link['DISPLAYOUT'].'&action='.$link['ACTIONOUT'].'&formid='.$link['FORMOUT'].'&id='.$id.'&map='.$link['MAP'].'&orderkeys='.urlencode($_REQUEST['orderkeys']).$search.'&zft=list&zfp='.$formid.'" alt="'.$link['ACTION']).'">'.ucfirst($link['ACTION']).'</a>';
		$topspan.='&nbsp';
	}
}

$alink=new zfLink($zflist->id,false,'list','R');
?>
<div id="<?php echo $formname;?>">
<div style="float:left;position:relative;padding-left:1%;margin-bottom:10px;">
<?php if ($alink->canAdd) {
//echo '<a class="art-button" href="'.zurl('?page='.$page.'&zfaces=form&form='.$formname.'&action=add&zft=list&zfp='.$formid.'&map='.urlencode($mapflat)).'"><img class="zfimg" src="'.ZING_APPS_PLAYER_URL.'images/add.png"></a>';
echo '<a class="art-button" href="'.zurl('?page='.$page.'&zfaces=form&form='.$formname.'&action=add&zft=list&zfp='.$formid.'&map='.urlencode($mapflat)).'">'.z_('Add').'</a>';
} 
echo $topspan;
?>
</div>
<div style="float:right;position:relative;padding-right:1%;padding-bottom:10px;">
<?php if (defined("ZING_APPS_BUILDER") && ZingAppsIsAdmin()) {?>
<select id="zfheader">
	<option value="none" selected="selected">Add column</option>
	<?php
	foreach ($zflist->allheaders as $key => $value)
	{
		echo '<option value="'.$key.'">'.$value.'</option>';
	}
	?>
</select>
</div>
<div style="clear:both;"></div>
<?php }

if ($zflist)
{

	$h=$zflist->headers;

	if (ZingAppsIsAdmin()) echo '<table id="'.$formname.'" class="datatable sortable draggable">';
	else echo '<table id="'.$formname.'" class="datatable">';
	echo '<thead>';
	echo '<tr>';
	foreach ($h as $key => $value)
	{
		echo '<th id="'.$key.'">'.$value.'</th>';
	}
	echo '</tr>';
	echo '</thead>';
	echo '<tbody id="foo" class="sortlist">';
	
	$altrow="altrow";

	if (isset($_REQUEST['orderkeys'])) {
		$orderKeys='';
		$o=zf_json_decode(str_replace("'",'"',$_REQUEST['orderkeys']),true);
		foreach ($o as $key => $order) {
			if ($orderKeys) $orderKeys.=",";
			$orderKeys.="`".$key."` ".$order;
		}
		if ($orderKeys) $zflist->orderKeys=$orderKeys;
	}
	if ($zflist->SelectRows($map,$pos))
	{
		$rows=$zflist->NextRows();
		$line=1;
		$script="";
		foreach ($rows as $id => $row)
		{
			$links=$alink->getLinks($id);
			$span="";
			if ($links) {
				foreach ($links as $i => $link) {
					if ($span) $span.=" | ";
					if ($link['FORMOUTALT']) $span.='<a href="'.zurl('?'.$link['FORMOUTALT'].'&id='.$id.'&map='.$link['MAP'].$search.'&zft=list&zfp='.$formid.'" alt="'.$link['ACTION']).'">'.ucfirst($link['ACTION']).'</a>';
					else $span.='<a href="'.zurl('?page='.$page.'&zfaces='.$link['DISPLAYOUT'].'&action='.$link['ACTIONOUT'].'&formid='.$link['FORMOUT'].'&id='.$id.'&map='.$link['MAP'].$search.'&zft=list&zfp='.$formid.'" alt="'.$link['ACTION']).'">'.ucfirst($link['ACTION']).'</a>';
				}
			}
			$line=$id;
			echo '<tr class="'.$altrow.'" id="foo_'.$line.'">';
			if ($altrow) $altrow=""; else $altrow="altrow";
			$i=1;
			foreach ($row as $column)
			{
				echo '<td>';
				echo '<div>'.$column.'</div>';
				if ($i==1 && !empty($span)) {
					echo '<span style="filter:alpha(opacity=90);opacity:0.9;padding:4px;display:none;position:absolute;" id="fox_'.$line.'">'.$span.'</span>';
					echo '<br />';
				}
				echo '</td>';
				$i++;
			}
			echo '</td>';
			if (!empty($span)) {
//				if (ZING_PROTOTYPE) {
//					$script.="var zelt = $('foo_".$line."');";
//					$script.="zelt.observe('mouseover', function() { $('fox_".$line."').setStyle({ display : 'block', backgroundColor : '#ccdd4f'}); });";
//					$script.="zelt.observe('mouseout', function() { $('fox_".$line."').setStyle({ display : 'none'});});";
//				} elseif (ZING_JQUERY) {
					$script.="var zelt = jQuery('#foo_".$line."');";
					$script.="zelt.bind('mouseover', this, function() { jQuery('#fox_".$line."').css('display','block');jQuery('#fox_".$line."').css('backgroundColor','#ccdd4f'); });";
					$script.="zelt.bind('mouseout', this, function() { jQuery('#fox_".$line."').css('display','none'); });";
//				}
			}
			$line++;
		}

	}
	else
	{
		echo "<tr><td colspan=".($zflist->headersCount+1)."><center>".z_("No records available")."</center></td></tr>";
	}
	echo '</tbody>';
	echo '</table>';
	echo '<script type="text/javascript">';
//	if (ZING_PROTOTYPE) {
//		echo 'document.observe("dom:loaded", function() {';
//		echo $script;
//	} elseif (ZING_JQUERY) {
		echo 'jQuery(document).ready(function() {';
		echo $script;
//	}
	echo '});';
	echo '</script>';
	if ($stack->getPrevious()) echo '<a href="'.$stack->getPrevious().'">Back</a>';
	if ($zflist->rowsCount > $zflist->maxRows) {
		$countPages=$zflist->rowsCount/$zflist->maxRows;
		
		$s=max(0,$pos-5*$zflist->maxRows);
		$e=min($zflist->rowsCount,$pos+5*$zflist->maxRows);
		//echo $s.'-'.$e.'-'.$zflist->rowsCount.'-'.$zflist->maxRows;
		$i=0;
		if ($s != 0) echo '<a href="'.zurl('?page='.$page.'&zfaces=list&form='.$formname.'&pos='.$i.'&zft=list&zfp='.$zfp.'&map='.urlencode(zf_json_encode($map)).'&orderkeys='.urlencode($_REQUEST['orderkeys']).$search).'">['.$i.']</a> ... '; 
		for ($i=$s;$i<=$e;$i=$i+$zflist->maxRows) {
			echo '<a href="'.zurl('?page='.$page.'&zfaces=list&form='.$formname.'&pos='.$i.'&zft=list&zfp='.$zfp.'&map='.urlencode(zf_json_encode($map)).'&orderkeys='.urlencode($_REQUEST['orderkeys']).$search).'">['.$i.']</a> ';
			$k=$i;
		}
		$i=round($zflist->rowsCount/$zflist->maxRows-0.5,0)*$zflist->maxRows;
		if ($k!=$i) echo ' ... <a href="'.zurl('?page='.$page.'&zfaces=list&form='.$formname.'&pos='.$i.'&zft=list&zfp='.$zfp.'&map='.urlencode(zf_json_encode($map)).'&orderkeys='.urlencode($_REQUEST['orderkeys']).$search).'">['.$i.']</a> ';
		//for ($i=0;$i<=$zflist->rowsCount;$i=$i+$zflist->maxRows) {
		//	echo '<a href="'.zurl('?page='.$page.'&zfaces=list&form='.$formname.'&pos='.$i.'&zft=list&zfp='.$zfp.'&map='.urlencode(zf_json_encode($map)).$search).'">['.$i.']</a> ';
		//}
	}


}


?></div>
<?php 
if (method_exists($zflist,'sortlist')) {
	$zflist->sortlist();
	if (ZING_PROTOTYPE) { 
?>

<script type="text/javascript" language="javascript">
//<![CDATA[
	document.observe("dom:loaded", function() {
	    appsSortList.init('<?php echo $zflist->ajaxUpdateURL;?>');
	});
//]]>
</script>
		<?php } else {?>
<script type="text/javascript" language="javascript">
//<![CDATA[
	jQuery(document).ready(function() {
	    appsSortList.init('<?php echo $zflist->ajaxUpdateURL;?>');
	});
//]]>
</script>
<?php }
		}?>
