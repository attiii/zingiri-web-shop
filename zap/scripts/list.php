<?php
/*  list.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Apps.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Zingiri Apps; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
$formname=$_GET['form'];
$formid=$_GET['formid'];
$zfp=intval($_GET['zfp']);
$zft=$_GET['zft'];
$pos=$_GET['pos'];
$mapflat=$_GET['map'];
//$json=str_replace("\'",'"',$_GET['map']);
$json=stripslashes($_GET['map']);
$map=zf_json_decode($json,true);
$zflist=new zfForm($formname,$formid);
$formname=$zflist->form;
$formid=$zflist->id;
if ($action=='search') {
	$search=$zflist->setSearch($_POST,$map);
}

$stack=new zfStack('list',$formname,$search);

echo '<p class="zfaces-form-label">'.z_($zflist->label).'</p>';

if (!AllowAccess('list',$formid,$action)) return false;

if (file_exists(ZING_APPS_CUSTOM.'apps.'.$formname.'.php')) require(ZING_APPS_CUSTOM.'apps.'.$formname.'.php');

$linksin=new zfDB();
$linksin->select("select * from ##flink where formin='*' and displayout='list' and formout='".$zflist->id."' and mapping <> ''");
while ($l=$linksin->next()) {
	$s=explode(",",$l['MAPPING']);
	foreach ($s as $m) {
		$f=explode(":",$m);
		$map[$f[0]]=$f[1];
	}
}

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


?>
<div id="<?php echo $formname;?>"><a
	href="?page=<?php echo $page;?>&zfaces=form&form=<?php echo $formname;?>&action=add&zft=list&zfp=<?php echo $formid;?>&map=<?php echo urlencode($mapflat);?>"
><img class="zfimg" src="<?php echo ZING_APPS_PLAYER_URL; ?>images/add.png"></a> <?php if (defined("ZING_APPS_BUILDER") && ZingAppsIsAdmin()) {?>
<select id="zfheader">
	<option value="none" selected="selected">Add column</option>
	<?php
	foreach ($zflist->allheaders as $key => $value)
	{
		echo '<option value="'.$key.'">'.$value.'</option>';
	}
	?>
</select> <?php }

if ($zflist)
{

	$links=new zfDB();
	$links->select("select * from ##flink where formin='".$zflist->id."'");
	while ($l=$links->next()) {
		$alink[]=$l;
	}
	$alink=new zfLink($zflist->id,false,'list');

	$h=$zflist->headers;

	if (ZingAppsIsAdmin()) echo '<table id="'.$formname.'" class="datatable sortable draggable">';
	else echo '<table id="'.$formname.'" class="datatable">';
	echo '<tr>';
	foreach ($h as $key => $value)
	{
		echo '<th id="'.$key.'">'.$value.'</th>';
	}
	//echo '<th>'.'Action'.'</th>';
	echo '</tr>';

	$altrow="altrow";

	if ($zflist->SelectRows($map,$pos))
	{
		$rows=$zflist->NextRows();
		$line=1;
		$script="";
		//$script.="var tableX=Element.cumulativeOffset($('".$formname."')).left;";
		//$script.="var tableX=$('".$formname."').left;";
		//$script.="var mouseX=tableX;";
		//$script.="$('".$formname."').observe('mousemove', function(e) { mouseX=Event.pointerX(e)-tableX; });";
		foreach ($rows as $id => $row)
		{
			$links=$alink->getLinks($id);
			if ($links) {
				$span="";
				foreach ($links as $i => $link) {
					if ($span) $span.=" | ";
					$span.='<a href="?page='.$page.'&zfaces='.$link['DISPLAYOUT'].'&action='.$link['ACTIONOUT'].'&formid='.$link['FORMOUT'].'&id='.$id.'&map='.$link['MAP'].$search.'&zft=list&zfp='.$formid.'" alt="'.$link['ACTION'].'">'.ucfirst($link['ACTION']).'</a>';
				}

			}

			echo '<tr class="'.$altrow.'" id="foo'.$line.'">';
			if ($altrow) $altrow=""; else $altrow="altrow";
			$i=1;
			foreach ($row as $column)
			{
				echo '<td>';
				echo $column;
				if ($i==1 && !empty($span)) {
					echo '<span style="filter:alpha(opacity=90);opacity:0.9;padding:4px;display:none;position:absolute;" id="fox'.$line.'">'.$span.'</span>';
					echo '<br />&nbsp';
				}
				echo '</td>';
				$i++;
			}
			echo '</td>';
			$script.="var zelt = $('foo".$line."');";
			//$script.="var actelt = null;";
			//$script.="var acttable = $('".$formname."');";

			//$script.="zelt.observe('mouseover', function() { if (actelt != null) { $(actelt).setStyle({ display : 'none' }); }; actelt='fox'+".$line."; $('fox".$line."').setStyle({ display : 'block', backgroundColor : '#ccdd4f', leftna : 0 + 'px' }); });";
			$script.="zelt.observe('mouseover', function() { $('fox".$line."').setStyle({ display : 'block', backgroundColor : '#ccdd4f'}); });";
			$script.="zelt.observe('mouseout', function() { $('fox".$line."').setStyle({ display : 'none'});});";
			$line++;
		}

	}
	else
	{
		echo "<tr><td colspan=".($zflist->headersCount+1)."><center>No records available</center></td></tr>";
	}
	echo '</table>';
	echo '<script type="text/javascript">';
	echo $script;
	echo '</script>';
	if ($stack->getPrevious()) echo '<a href="'.$stack->getPrevious().'">Back</a>';
	if ($zflist->rowsCount > ZING_APPS_MAX_ROWS) {
		for ($i=0;$i<=$zflist->rowsCount;$i=$i+ZING_APPS_MAX_ROWS) {
			echo '<a href="?page='.$page.'&zfaces=list&form='.$formname.'&pos='.$i.'&zft=list&zfp='.$zfp.'&map='.urlencode(zf_json_encode($map)).$search.'">['.$i.']</a> ';
		}
	}


}


?></div>
