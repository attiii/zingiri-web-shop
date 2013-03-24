<?php
/*
 * Renders a form via Ajax
 */

ob_start();
require(dirname(__FILE__).'/../includes/all.inc.php');
global $zfform,$zfSuccess;

if (!isset($formname) && isset($_REQUEST['formname'])) $formname=$_REQUEST['formname'];
if (!isset($formid) && isset($_REQUEST['formid'])) $formid=$_REQUEST['formid']; else $formid='';
if (!isset($action) && isset($_REQUEST['action'])) $action=$_REQUEST['action'];
$step=isset($_REQUEST['step']) ? $_REQUEST['step'] : null;
if (isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id='';
$zfp=isset($_REQUEST['zfp']) ? intval($_REQUEST['zfp']) : 0;
$zft=isset($_REQUEST['zft']) ? $_REQUEST['zft'] : '';
if (isset($_REQUEST['search']) && is_array($_REQUEST['search'])) $search=$_REQUEST['search'];
if (!isset($noRedirect)) {
	if (isset($_REQUEST['no_redirect'])) $noRedirect=true; else $noRedirect=false;
}
$noBackLink=isset($_REQUEST['no_back_link']) ? $_REQUEST['no_back_link'] : false;

if (!isset($noForm)) {
	if (isset($_REQUEST['no_form'])) $noForm=true; else $noForm=false;
}
$noLabel=isset($_REQUEST['no_label']) ? $_REQUEST['no_label'] : false; 
if (isset($_REQUEST['map'])) {
	$json=$_REQUEST['map'];
	if (is_array($json)) $map=$json;
	else $map=zf_json_decode($json,true);
} elseif (isset($_REQUEST['mape'])) {
	$map=unserialize(base64_decode($_REQUEST['mape']));
} else $map='';

if (empty($formname)) $formname=zfGetForm($formid);
if (class_exists('zf'.$formname)) $zfClass='zf'.$formname;
else $zfClass='zfForm';

$zfform=new $zfClass($formname,$formid,$map,$action,'form',$id);
$formname=$zfform->form;
$formid=$zfform->id;
$stack=new zfStack('form',$formname);
$zfform->noAlert=isset($_REQUEST['no_alert']) ? $_REQUEST['no_alert'] : false;

$allowed=false;
$success=true;
if (isset($_REQUEST['showform'])) $showform=$_REQUEST['showform']; else $showform="edit";

if ($action == "add" && ($step == "" || $step == "poll")) {
	if ($zfform->allowAccess()) {
		$allowed=true;
		$success=$zfform->Prepare();
		$newstep=empty($zfform->newstep) ? "save" : $zfform->newstep;
	}
} elseif ($action == "add" && ($step == "preload")) {
	$temp=$_REQUEST;
	foreach ($temp as $f => $v) {
		if (strstr($f,'element_')) $zfform->input[$f]=$v;
	}
	if ($zfform->allowAccess()) {
		$allowed=true;
		$success=$zfform->Prepare();
		$newstep=empty($zfform->newstep) ? "save" : $zfform->newstep;
	}
} elseif ($action == "add" && $step == "check") {
	if ($zfform->allowAccess()) {
		$allowed=true;
		if ($success=$zfform->Verify($_POST))
		{
			$showform="saved";
		} else {
			$newstep="save";
		}
	}
} elseif ($action == "add" && $step == "save") {
	if ($zfform->allowAccess()) {
		$allowed=true;
		if ($zfform->Verify($_POST))
		{
			$zfform->Save();
			$showform="saved";
			if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
			elseif (isset($_REQUEST['redirect'])) $redirect=$_REQUEST['redirect'];
			else $redirect=null;
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

} elseif ($action == "edit" && $step == "preload") {
	$success=$zfform->Prepare($id);
	$temp=$_REQUEST;
	foreach ($temp as $f => $v) {
		if (strstr($f,'element_')) $zfform->input[$f]=$v;
	}
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
			elseif (isset($_REQUEST['redirect'])) $redirect=$_REQUEST['redirect'];
			else $redirect=null;
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
		elseif (isset($_REQUEST['redirect'])) $redirect=$_REQUEST['redirect'];
		else $redirect=null;
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
							elseif (isset($_REQUEST['redirect'])) $redirect=$_REQUEST['redirect'];
							else $redirect=null;
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
		}
	}
}

if (!$success || !$allowed) {
	if (!empty($zfform->errorMessage)) {
		if (function_exists('fwktecError')) fwktecError($zfform->errorMessage); else echo z_($zfform->errorMessage);
	}
}

if ($allowed && $success && $showform == "edit") {
	if (!$noLabel && (is_admin() || ZING_CMS=='gn')) echo '<h2 class="zfaces-form-label">'.z_($zfform->label).'</h2>';
	echo '<div class="zfaces-form">';
	aphpsHooks::doAction('form_display',$zfform);
	
	if (defined("APHPS_SHOW_EDIT_LINK") && APHPS_SHOW_EDIT_LINK) {
		echo '<a href="'.zurl('?page=apps_edit&zfaces=edit&form='.$formname).'" >'.z_('Edit form').'</a>';
	}
	if (!$noForm && !isset($formURL)) {
		$aurl='?page='.$page.'&zfaces=form&form='.$formname.'&action='.$action;
		if (!empty($newstep)) $aurl.='&step='.$newstep;
		if (!empty($id)) $aurl.='&id='.$id;

		if (!empty($search)) {

			foreach ($search as $i => $v) {
				$aurl.='&search['.$i.']='.urlencode($v);
			}

		}
		$aurl.='&zft=form&zfp='.$formid;
		echo '<form enctype="multipart/form-data" name="faces" method="POST" id="aphpsform" action="'.zurl($aurl).'">';
	} elseif (!$noForm && isset($formURL)) {
		echo '<form enctype="multipart/form-data" name="faces" method="POST" id="aphpsform" action="'.zurl($formURL).'" >';
	}
	$zfform->Render($action);
	if (count($_POST) > 0) {
		foreach ($_POST as $name => $value) {
			if (!strstr($name,"element_")) {
				if (is_array($value)) {
					foreach ($value as $i => $v) {
						echo '<input type="hidden" name="'.$name.'['.$i.']'.'" value="'.str_replace("\'","'",preg_replace('/[^a-zA-Z0-9_@\-]/','',$v)).'" />';
					}
				} else echo '<input type="hidden" name="'.$name.'" value="'.str_replace("\'","'",preg_replace('/[^a-zA-Z0-9_@\-]/','',$value)).'" />';
			}
		}
	}
	if (isset($_REQUEST['redirect']) && $_REQUEST['redirect']) echo '<input type="hidden" name="redirect" value="'.$_REQUEST['redirect'].'" />';

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
					echo '<input class="art-button" type="submit" name="save" value="'.z_($link['ACTION']).'" onclick="form.action=\'?zfaces='.$link['DISPLAYOUT'].'&formid='.$link['FORMOUT'].'&id='.$id.'&mape='.urlencode(base64_encode($link['MAP'])).'\'">';
				}
			}
		}
		echo '</div>';
	}
	/*
	if (!$noForm) {
		echo '<div class="aphps_form_buttons">';
		if (!$zfform->hasSubmit && ($action == 'add' or $action == 'edit') && (!isset($override_save) || !$override_save)) {
			$saveButtonText=isset($zfform->formAttributes->savebuttontext) && $zfform->formAttributes->savebuttontext ? $zfform->formAttributes->savebuttontext : 'Save';
			echo '<input id="appscommit" class="art-button" type="submit" name="save" value="'.z_($saveButtonText).'">';
		} elseif ($action == 'delete') {
			echo '<input class="art-button" type="submit" name="delete" value="'.z_('Delete').'">';
		} elseif (!$zfform->hasSubmit && !empty($action) && ($action!='view')) {
			echo '<input class="art-button" type="submit" name="other" value="'.z_('Confirm').'">';
		}
		echo '</div>';
	}
	*/
	if (!$noForm) echo '</form>';
	echo '</div>';
	if ($stack->getPrevious()) echo '<a href="'.zurl($stack->getPrevious()).'">'.z_('Back').'</a>';
} elseif ($showform == "saved") {
	aphpsHooks::doAction('form_saved',$zfform);
	
	/*
	if ($stack->getPrevious()) {
		$redirect2=$stack->getPrevious();
	} else {
		$redirect2='?page='.$page.'&zfaces=form&form='.$formname.'&zft='.$zft.'&zfp='.$zfp.'&action='.$action.'&id='.$id;
	}
	if (!$noRedirect && !$redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) {
		header('Location: '.zurl($redirect2.'&zmsg=complete'));
		die();
	} else {
		if (!$noBackLink) echo '<a href="'.zurl($redirect2).'" class="button">'.z_('Back').'</a>';
	}
	*/
}

$html=ob_get_clean();
echo json_encode(array('html'=>$html,'success'=>$success,'output'=>$zfform->output,'input'=>$zfform->input));