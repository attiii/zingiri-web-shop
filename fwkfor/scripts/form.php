<?php
global $zfform,$zfSuccess;

if (isset($_GET['form'])) $form=$_GET['form'];
if (!isset($formid) && isset($_GET['formid'])) $formid=$_GET['formid']; else $formid='';
if (!isset($action) && isset($_GET['action'])) $action=$_GET['action'];
$step=isset($_GET['step']) ? $_GET['step'] : null;
if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_GET['zfp'])) $zfp=intval($_GET['zfp']);
if (isset($_GET['zft'])) $zft=$_GET['zft'];
if (isset($_GET['search']) && is_array($_GET['search'])) $search=$_GET['search'];
if (!isset($noRedirect)) {
	if (isset($_GET['no_redirect'])) $noRedirect=true; else $noRedirect=false;
}
$noBackLink=isset($_REQUEST['no_back_link']) ? $_REQUEST['no_back_link'] : false;

if (!isset($noForm)) {
	if (isset($_GET['no_form'])) $noForm=true; else $noForm=false;
}
$noLabel=isset($_REQUEST['no_label']) ? $_REQUEST['no_label'] : false; 
if (isset($_POST['map'])) {
	$json=str_replace("\'",'"',$_POST['map']);
	$map=zf_json_decode($json,true);
} elseif (isset($_GET['map'])) {
	$json=str_replace("\'",'"',$_GET['map']);
	$map=zf_json_decode($json,true);
} else $map='';
if (empty($form)) $form=zfGetForm($formid);
if (class_exists('zf'.$form)) $zfClass='zf'.$form;
else $zfClass='zfForm';
$zfform=new $zfClass($form,$formid,$map,$action,'form',$id);
$form=$zfform->form;
$formid=$zfform->id;
$stack=new zfStack('form',$form);
$zfform->noAlert=isset($_REQUEST['no_alert']) ? $_REQUEST['no_alert'] : false;

$allowed=false;
$success=true;
if (isset($_GET['showform'])) $showform=$_GET['showform']; else $showform="edit";

//echo 'action='.$action;die();

if ($action == "add" && ($step == "" || $step == "poll")) {
	if ($zfform->allowAccess()) {
		$allowed=true;
		$success=$zfform->Prepare();
		$newstep=empty($zfform->newstep) ? "save" : $zfform->newstep;
	}

} elseif ($action == "add" && $step == "save") {
	if ($zfform->allowAccess()) {
		$allowed=true;
		if ($zfform->Verify($_POST))
		{
			$zfform->Save();
			$showform="saved";
			if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
			elseif (isset($_GET['redirect'])) $redirect=$_GET['redirect'];
			if ($redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) {
				header('Location:'.zurl($redirect.'&zmsg=add'));
				exit;
			}
		} else {
			$newstep="save";
		}
	}

} elseif ($action == "edit" && $step == "") {
	$success=$zfform->Prepare($id);
	if ($zfform->allowAccess()) {
		$allowed=true;
		$newstep="save";
	}

} elseif ($action == "edit" && $step == "check") {
	$success=$zfform->Verify($_POST,$id);
	if ($zfform->allowAccess()) {
		$allowed=true;
		$newstep="save";
	}

} elseif ($action == "edit" && $step == "save") {
	$newstep="save";
	if ($zfform->Verify($_POST,$id))
	{
		if ($zfform->allowAccess()) {
			$allowed=true;
			$zfform->Save($id);
			$showform="saved";
			if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
			elseif (isset($_GET['redirect'])) $redirect=$_GET['redirect'];
			if ($redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) {
				header('Location:'.zurl($redirect.'&zmsg=edit'));
				exit;
			}
		}
	} else {
		if ($zfform->allowAccess()) {
			$allowed=true;
		}
	}

} elseif ($action == "delete" && $step == "") {
	$success=$zfform->Prepare($id);
	if ($zfform->allowAccess()) {
		$allowed=true;
		$newstep="save";
	}

} elseif ($action == "delete" && $step == "save") {
	if ($zfform->allowAccess()) {
		$allowed=true;
		$newstep="save";
		$zfform->Delete($id);
		$showform="saved";
		if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
		elseif (isset($_GET['redirect'])) $redirect=$_GET['redirect'];
		if ($redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) {
			header('Location:'.zurl($redirect.'&zmsg=delete'));
			exit;
		}
	}
} elseif ($action == "view" && $step == "") {
	$success=$zfform->Prepare($id);
	if ($zfform->allowAccess()) {
		$allowed=true;
	}
} elseif ($action && $step == "") {
	$success=$zfform->Prepare($id);
	if ($zfform->allowAccess()) {
		$allowed=true;
		$newstep="save";
	}
} elseif ($action && $step == "save") {
	$newstep="save";
	if ($zfform->Verify($_POST,$id)) {
		if ($zfform->allowAccess()) {
			$allowed=true;
			$newstep="save";
			$a=explode(".",$action);
			if (count($a) == 2) {
				$c=$a[0]; //class
				$m=$a[1]; //method
				if (class_exists($c)) {
					$o=new $c($id);
					if (method_exists($o,$m)) {
						$r=$o->$m($zfform);
						if ($r) {
							$showform="saved";
							if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
							elseif (isset($_GET['redirect'])) $redirect=$_GET['redirect'];
							if ($redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) {
								header('Location:'.zurl($redirect.'&zmsg=delete'));
								exit;
							}
						} else {
							$action="";
							echo "Error when calling the method";
						}
					}
				}
			}
			//if ($id) $success=$zfform->Prepare($id);
		}
	}
}

if (!$allowed) {
	if (function_exists('fwktecError')) fwktecError($zfform->errorMessage); else echo $zfform->errorMessage; 	
}
if (!$success || !$allowed) {
	if (!empty($zfform->errorMessage)) {
		if (function_exists('fwktecError')) fwktecError($zfform->errorMessage); else echo z_($zfform->errorMessage);
	}
}

if ($allowed && $success && $showform == "edit") {
	if (!$noLabel && (is_admin() || ZING_CMS=='gn')) echo '<h2 class="zfaces-form-label">'.$zfform->label.'</h2>';
	echo '<div class="zfaces-form">';
	if (defined("ZING_APPS_BUILDER") && ZingAppsIsAdmin()) {
		echo '<a href="'.zurl('?page=apps_edit&zfaces=edit&form='.$form).'" >'.z_('Edit form').'</a>';
	}
	if (!$noForm && !isset($formURL)) {
		$aurl='?page='.$page.'&zfaces=form&form='.$form.'&action='.$action;
		if (!empty($newstep)) $aurl.='&step='.$newstep;
		if (!empty($id)) $aurl.='&id='.$id;

		if (!empty($search)) {

			foreach ($search as $i => $v) {
				$aurl.='&search['.$i.']='.urlencode($v);
			}

		}
		$aurl.='&zft=form&zfp='.$formid;
		echo '<form enctype="multipart/form-data" name="faces" method="POST" action="'.zurl($aurl).'">';
	} elseif (!$noForm && isset($formURL)) {
		echo '<form enctype="multipart/form-data" name="faces" method="POST" action="'.zurl($formURL).'" >';
	}
	$zfform->Render($action);
	if (count($_POST) > 0) {
		foreach ($_POST as $name => $value) {
			if (!strstr($name,"element_"))
			echo '<input type="hidden" name="'.$name.'" value="'.str_replace("\'","'",$value).'" />';
		}
	}
	if (isset($_GET['redirect']) && $_GET['redirect']) echo '<input type="hidden" name="redirect" value="'.$_GET['redirect'].'" />';

	$alink=new zfLink($formid,true,'form');
	$links=$alink->getLinks($id);
	if ($links) {
		echo '<div class="aphps_form_buttons">';
		foreach ($links as $i => $link) {
			if (empty($link['ACTIONIN']) or strstr($link['ACTIONIN'],$action)) {
				if ($link['ACTIONOUT'] == 'save') $override_save=true;
				echo '<input type="hidden" name="redirect" value="'.$link['REDIRECT'].'" />';
				if (!empty($id)) $link['URL'].='&id='.$id;
				if ($link['FORMOUTALT']) {
					echo '<input class="art-button" type="submit" name="save" value="'.z_($link['ACTION']).'" onclick="form.action=\''.$link['URL'].'\'">';
				}
				else {
					echo '<input class="art-button" type="submit" name="save" value="'.z_($link['ACTION']).'" onclick="form.action=\'?zfaces='.$link['DISPLAYOUT'].'&formid='.$link['FORMOUT'].'&id='.$id.'&map='.$link['MAP'].'\'">';
				}
			}
		}
		echo '</div>';
	}
	if (!$noForm) {
		echo '<div class="aphps_form_buttons">';
		if (($action == 'add' or $action == 'edit') && (!isset($override_save) || !$override_save)) {
			echo '<input id="appscommit" class="art-button" type="submit" name="save" value="'.z_('Save').'">';
		} elseif ($action == 'delete') {
			echo '<input class="art-button" type="submit" name="delete" value="'.z_('Delete').'">';
		} elseif (!empty($action)) {
			echo '<input class="art-button" type="submit" name="other" value="'.z_('Confirm').'">';
		}
		echo '</div>';
	}
	if (!$noForm) echo '</form>';
	echo '</div>';
	if ($stack->getPrevious()) echo '<a href="'.zurl($stack->getPrevious()).'">'.z_('Back').'</a>';
} elseif ($showform == "saved") {
	if ($stack->getPrevious()) {
		$redirect2=$stack->getPrevious();
	} else {
		$redirect2='?page='.$page.'&zfaces=form&form='.$form.'&zft='.$zft.'&zfp='.$zfp.'&action='.$action.'&id='.$id;
	}
	if (!$noRedirect && !$redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) {
		header('Location: '.zurl($redirect2.'&zmsg=complete'));
		die();
	} else {
		if (!noBackLink) echo '<a href="'.zurl($redirect2).'" class="button">'.z_('Back').'</a>';
	}
}
?>