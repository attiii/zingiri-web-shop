<?php
global $zfform,$zfSuccess;

if (isset($_GET['form'])) $form=$_GET['form'];
if (isset($_GET['formid'])) $formid=$_GET['formid'];
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_GET['step'])) $step=$_GET['step'];
if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_GET['zfp'])) $zfp=intval($_GET['zfp']);
if (isset($_GET['zft'])) $zft=$_GET['zft'];
if (isset($_GET['search']) && is_array($_GET['search'])) $search=$_GET['search'];
if (isset($_GET['no_redirect'])) $noRedirect=true; else $noRedirect=false;
if (isset($_GET['no_form'])) $noForm=true; else $noForm=false;
if (isset($_POST['map'])) {
	$json=str_replace("\'",'"',$_POST['map']);
	$map=zf_json_decode($json,true);
} elseif (isset($_GET['map'])) {
	$json=str_replace("\'",'"',$_GET['map']);
	$map=zf_json_decode($json,true);
}

$zfform=new zfForm($form,$formid,$map);
$form=$zfform->form;
$formid=$zfform->id;
$stack=new zfStack('form',$form);
if (!AllowAccess('form',$formid,$action)) return false;

$allowed=true;
$success=true;

if (!empty($action) && !ZingAppsIsAdmin() && ($action != 'show')) {
	$linksin=new zfDB();
	$allowed=$linksin->select("select * from ##flink where formout='".$formid."' and displayout='form' and actionin='".$action."'");

	if (!$allowed) $action="not_allowed";
}
if (isset($_GET['showform'])) $showform=$_GET['showform']; else $showform="edit";

if ($action == "not_allowed") {
	echo "Action not allowed";
	$success=false;

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
} elseif ($action == "edit" && $step == "check") {
	$zfSuccess=$zfform->Verify($_POST);
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
	echo '<p class="zfaces-form-label">'.$zfform->label.'</p>';
	echo '<div class="zfaces-form">';
	if (defined("ZING_APPS_BUILDER") && ZingAppsIsAdmin()) {
		echo '<a href="'.get_option('home').'/index.php?page=appsbuilder&zfaces=edit&form='.$form.'" >'.z_('Edit form').'</a>';
	}
	if (!$noForm) {

		echo '<form name="faces" method="POST" action="?page='.$page.'&zfaces=form&form='.$form.'&action='.$action;
		if (!empty($newstep)) echo '&step='.$newstep;
		if (!empty($id)) echo '&id='.$id;

		if (!empty($search)) {

			foreach ($search as $i => $v) {
				echo '&search['.$i.']='.urlencode($v);
			}

		}
		echo '&zft=form&zfp='.$formid.'">';
	}
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

	if (!$noForm) {
		if (($action == 'add' or $action == 'edit') && (!$override_save)) {
			echo '<center><input class="art-button" type="submit" name="save" value="Save"></center>';
		} elseif ($action == 'delete') {
			echo '<input class="art-button" type="submit" name="delete" value="Delete">';
		}
		echo '</form><br />';
	}
	echo '</div>';
	if ($stack->getPrevious()) echo '<a href="'.$stack->getPrevious().'">Back</a>';

} elseif ($showform == "saved") {
	if ($stack->getPrevious()) {
		$redirect2=$stack->getPrevious();
	} else {
		$redirect2='?page='.$page.'&zfaces=form&form='.$form.'&zft='.$zft.'&zfp='.$zfp.'&action='.$action.'&id='.$id;
	}
	if (!$noRedirect && !$redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) {
		header('refresh:0; url='.$redirect2);
		exit;
	} else {
		echo '<a href="'.$redirect2.'" class="button">Back</a>';
	}
}
