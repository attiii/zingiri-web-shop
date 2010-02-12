<?php
$form=$_GET['form'];
$formid=$_GET['formid'];
$action=$_GET['action'];
$step=$_GET['step'];
$id=$_GET['id'];
$zfp=intval($_GET['zfp']);
$zft=$_GET['zft'];
$json=str_replace("\'",'"',$_POST['map']);
$map=zf_json_decode($json,true);

/*
 $linksin=new zfDB();
 $linksin->select("select * from ##flink where (formin='*' or formin='".$formid."') and displayout='form' and formout='".$formid."' and mapping <> ''");
 while ($l=$linksin->next()) {
 $s=explode(",",$l['MAPPING']);
 foreach ($s as $m) {
 $f=explode(":",$m);
 $map[$f[0]]=$f[1];
 }
 }*/

$zfform=new zfForm($form,$formid,$map);
$form=$zfform->form;
$formid=$zfform->id;
$stack=new zfStack('form',$form);
echo '<p class="zfaces-form-label">'.$zfform->label.'</p>';
if (!AllowAccess('form',$formid,$action)) return false;

$allowed=true;
$success=true;

if (!empty($action) && !ZingAppsIsAdmin() && ($action != 'show')) {
	$linksin=new zfDB();
	//	if ($action == 'addsave') $actioncheck='add';
	//	elseif ($action == 'editsave') $actioncheck='edit';
	//	else $actioncheck=$action;
	$allowed=$linksin->select("select * from ##flink where formout='".$formid."' and displayout='form' and actionout='".$action."'");

	if (!$allowed) $action="not_allowed";
}
if ($allowed) $showform="edit";

if ($action == "not_allowed") {
	echo "Action not allowed";

} elseif ($action == "add" && $step == "") {
	$success=$zfform->Prepare();
	$newstep="save";

} elseif ($action == "add" && $step == "save") {
	if ($zfform->Verify($_POST))
	{
		$zfform->Save();
		$showform="saved";
		if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
		elseif (isset($_GET['redirect'])) $redirect=$_GET['redirect'];
		if ($redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) header('refresh:0; url='.$redirect);

	} else {
		$newstep="save";
	}
} elseif ($action == "edit" && $step == "") {
	$success=$zfform->Prepare($id);
	$newstep="save";
} elseif ($action == "edit" && $step == "save") {
	$newstep="save";
	if ($zfform->Verify($_POST))
	{
		$zfform->Save($id);
		$showform="saved";
		if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
		elseif (isset($_GET['redirect'])) $redirect=$_GET['redirect'];
		if ($redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) header('refresh:0; url='.$redirect);
	}
} elseif ($action == "delete" && $step == "") {
	$success=$zfform->Prepare($id);
	$newstep="save";
} elseif ($action == "delete" && $step == "save") {
	$newstep="save";
	$zfform->Delete($id);
	$showform="saved";
} elseif ($action == "view" && $step == "") {
	$success=$zfform->Prepare($id);
} else {
	$a=explode(".",$action);
	if (count($a) == 2) {
		$c=$a[0]; //class
		$m=$a[1]; //method
		if (class_exists($c)) {
			$o=new $c($id);
			if (method_exists($o,$m)) {
				$r=$o->$m();
				if ($r) $action="view";
				else {
					$action="";
					echo "Error when calling the method";
				}
			}
		}
	}
	if ($id) $success=$zfform->Prepare($id);
}

if (!$success)  echo 'Record not found';

if ($success && $showform == "edit") {
	if (defined("ZING_APPS_BUILDER") && ZingAppsIsAdmin()) {
		echo '<a href="?zfaces=edit&form='.$form.'" >'.z_('Edit form').'</a>';
	}
	echo '<form name="faces" method="POST" action="?zfaces=form&form='.$form.'&action='.$action;
	if (!empty($newstep)) echo '&step='.$newstep;
	if (!empty($id)) echo '&id='.$id;
	echo '&zft=form&zfp='.$formid.'">';
	echo '<ul id="zfaces" class="zfaces">';
	$zfform->Render($action);
	echo '</ul>';
	if (count($_POST) > 0) {
		foreach ($_POST as $name => $value) {
			if (!strstr($name,"element_"))
			echo '<input type="hidden" name="'.$name.'" value="'.str_replace("\'","'",$value).'" />';
		}
	}
	if ($_GET['redirect']) echo '<input type="hidden" name="redirect" value="'.$_GET['redirect'].'" />';

	$alink=new zfLink($formid,true);
	$links=$alink->getLinks($id);
	if ($links) {
		foreach ($links as $i => $link) {
			if (empty($link['ACTIONIN']) or strstr($link['ACTIONIN'],$action)) {
				if ($link['ACTIONOUT'] == 'save') $override_save=true;
				echo '<input type="hidden" name="redirect" value="'.$link['REDIRECT'].'" />';
				if (!empty($id)) $link['URL'].='&id='.$id;
				if ($link['FORMOUTALT']) {
					echo '<input class="art-button" type="submit" name="save" value="'.$link['ACTION'].'" onclick="form.action=\''.$link['URL'].'\'">';
				}
				else {
					echo '<input class="art-button" type="submit" name="save" value="'.$link['ACTION'].'" onclick="form.action=\'?zfaces='.$link['DISPLAYOUT'].'&formid='.$link['FORMOUT'].'&id='.$id.'&map='.$link['MAP'].'\'">';
				}
			}
		}
	}

	if (($action == 'add' or $action == 'edit') && (!$override_save)) {
		echo '<input class="art-button" type="submit" name="save" value="Save">';
	} elseif ($action == 'delete') {
		echo '<input class="art-button" type="submit" name="delete" value="Delete">';
	}
	echo '</form>';
	if ($stack->getPrevious()) echo '<a href="'.$stack->getPrevious().'">Back</a>';
	
} elseif ($showform == "saved" ) {
	$redirect2='?zfaces=list&form='.$form.'&zft='.$zft.'&zfp='.$zfp;
	echo '<a href="'.$redirect2.'" class="button">Back</a>';
	if (!$redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) header('refresh:0; url='.$redirect2);
}
