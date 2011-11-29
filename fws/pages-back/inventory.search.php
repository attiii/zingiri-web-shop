<?php if ($index_refer <> 1) { exit(); } ?>
<?php 
$wsPageSearch=($page=='search') ? 'browse' : $page;
if (isset($_REQUEST['wsgroup'])) $searchGroup=intval($_REQUEST['wsgroup']);
else $searchGroup='';
if (isset($_REQUEST['wscategory'])) $searchCategory=intval($_REQUEST['wscategory']);
else $searchCategory='';
?>
<div class="datatable">
	<form method="get" action="?page=<?php echo $wsPageSearch;?>">
		<?php echo $txt['search2'] ?>
	    <input type="hidden" name="page" value="<?php echo $wsPageSearch;?>">
	    <input type="text" name="searchfor" size="30">
	    <input type="hidden" name="action" value="search">
	    <input type="hidden" name="includesearch" value="<?php echo $includesearch;?>">
	    <?php echo $txt['search3'] ?>
	    <SELECT NAME="searchmethod">
	    	<OPTION VALUE="AND" SELECTED><?php echo $txt['search4'] ?></OPTION>
	    	<OPTION VALUE="OR"><?php echo $txt['search5'] ?></OPTION>
	    </SELECT>
	    <SELECT id="wsproductgroup" NAME="wsgroup">
    	<OPTION VALUE=""></OPTION>
	    <?php
	    $db=new db();
		if ($db->select("SELECT * FROM `##group` ORDER BY `SORTORDER`,`NAME` ASC")) {
			while ($db->next())	{
				if ($db->get('id')==$searchGroup) $selected='selected="selected"';
				else $selected='';
				echo '<option value="'.$db->get('id').'" '.$selected.'>'.$db->get('name').'</option>';
			}
		}
	    ?>
	    </SELECT>
	    <SELECT id="wsproductcategory" NAME="wscategory">
    	<OPTION VALUE=""></OPTION>
    	<?php
    	if ($searchGroup) {
			if ($db->select("SELECT * FROM `##category` WHERE `GROUPID`=".$searchGroup." ORDER BY `SORTORDER`,`DESC` ASC")) {
				while ($db->next())	{
					if ($db->get('id')==$searchCategory) $selected='selected="selected"';
					else $selected='';
					echo '<option value="'.$db->get('id').'" '.$selected.'>'.$db->get('desc').'</option>';
				}
			}
    	}
    	?>
	    </SELECT>
	    <div style="text-align:center;"><input type="submit" value="<?php echo $txt['search6'] ?>"></div>
	</form>
</div>      
<script type="text/javascript" src="<?php echo ZING_URL.'fws/js/'.APHPS_JSDIR.'/getcategories.jquery.js';?>"></script>'
