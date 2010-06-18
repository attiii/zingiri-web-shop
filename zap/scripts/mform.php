<?php
$action=$_GET['action'];
$step=$_GET['step'];
$zfp=intval($_GET['zfp']);
$zft=$_GET['zft'];
//$stack=new zfStack('form',$form);
//echo '<h3 class="zfaces-form-label">'.$zfform->label.'</h3>';

/*
echo '<br />get:';
print_r($_GET);
echo '<br />post:';
print_r($_POST);
*/

class zfForms {
	var $forms=array();
	var $data=array();

	function zfForms() {
		if (isset($_GET['forms'])) $forms=$_GET['forms'];
		elseif (isset($_POST['forms'])) $forms=$_POST['forms'];
		foreach ($forms as $form => $data) {
			$json=str_replace("\'",'"',$data['map']);
			$map=zf_json_decode($json,true);

			$this->forms[]=new zfForm($form,null,$map);
			$this->data[$form]=$data;
		}
	}
	function allowAccess($action) {
		foreach ($this->forms as $form) {
			if (!AllowAccess('form',$form->id,$action)) return false;
		}
		return true;
	}
	function prepare() {
		$success=true;
		foreach ($this->forms as $form) {
			if ($success) $success=$form->prepare($this->data[$form->form]['id']);
		}
		return $success;
	}
	function render($action) {
		foreach ($this->forms as $form) {
			$form->render($action,$form->form);
		}
	}

	function save() {
		foreach ($this->forms as $form) {
			$form->save($this->data[$form->form]['id']);
		}
	}

	function delete() {
		foreach ($this->forms as $form) {
			$form->delete($this->data[$form->form]['id']);
		}
	}

	function verify() {
		$success=true;
		foreach ($this->forms as $form) {
			$post=array();
			foreach ($_POST as $i => $v) {
				$s=explode('_',$i);
				if ($s[0]==$form->form) {
					unset($s[0]);
					$j=str_replace($form->form.'_','',$i);
					$post[$j]=$v;
				}
			}
			if ($success) $success=$form->verify($post);
		}
		return $success;
	}
}

$showform="edit";

$forms=new zfForms();
$allowed=$forms->allowAccess($action);

if ($action == "add" && $step == "") {
	$success=$forms->prepare();
	$newstep="save";

} elseif ($action == "add" && $step == "save") {
	if ($forms->verify())
	{
		$forms->save();
		$showform="saved";
		if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
		elseif (isset($_GET['redirect'])) $redirect=$_GET['redirect'];
		if ($redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) header('refresh:0; url='.$redirect);

	} else {
		$newstep="save";
	}
} elseif ($action == "edit" && $step == "") {
	$success=$forms->prepare();
	$newstep="save";
} elseif ($action == "edit" && $step == "save") {
	$newstep="save";
	if ($forms->verify())
	{
		$forms->save();
		$showform="saved";
		if (isset($_POST['redirect'])) $redirect=$_POST['redirect'];
		elseif (isset($_GET['redirect'])) $redirect=$_GET['redirect'];
		if ($redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) header('refresh:0; url='.$redirect);
	}

} elseif ($action == "delete" && $step == "") {
	$success=$forms->prepare();
	$newstep="save";
} elseif ($action == "delete" && $step == "save") {
	$newstep="save";
	$forms->delete();
	$showform="saved";
} elseif ($action == "view" && $step == "") {
	$success=$forms->prepare();
} else {
	/*
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
		if ($id) $success=$forms->prepare($id);
		*/
}

if ($showform == "edit") {
	if (defined("ZING_APPS_BUILDER") && ZingAppsIsAdmin()) {
		echo '<a href="?zfaces=edit&form='.$form.'" >'.z_('Edit form').'</a>';
	}
	/*
	$getforms="";
	foreach ($_GET['forms'] as $i => $v) {
		if (count($v) > 0) {
			foreach ($v as $j => $w) {
				if ($getforms) $getforms.='&';
				$getforms.='forms['.$i.']['.$j.']='.urlencode($w);
			}
		}  else {
			if ($getforms) $getforms.='&';
			$getforms.='forms['.$i.']=true';
		}
	}
	*/
	
	echo '<form enctype="multipart/form-data" name="faces" method="POST" action="?zfaces=mform&'.$getforms.'&action='.$action;
	if (!empty($newstep)) echo '&step='.$newstep;
	if (!empty($id)) echo '&id='.$id;
	echo '&zft=form&zfp='.$formid.'">';
	echo '<ul id="zfaces" class="zfaces">';
	$forms->render($action);
	echo '</ul>';
	if (count($_POST) > 0) {
		foreach ($_POST as $name => $value) {
			if (!strstr($name,"element_"))
			echo '<input type="hidden" name="'.$name.'" value="'.str_replace("\'","'",$value).'" />';
		}
	}
	if ($_GET['redirect']) echo '<input type="hidden" name="redirect" value="'.$_GET['redirect'].'" />';
	foreach ($_GET['forms'] as $i => $v) {
		if (count($v) > 0) {
			foreach ($v as $j => $w) {
				echo '<input type="hidden" name="forms['.$i.']['.$j.']" value="'.$w.'" />';
			}
		}  else {
			echo '<input type="hidden" name="forms['.$i.']" value="true" />';
		}
	}
	
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
	if (isset($stack) && $stack->getPrevious()) echo '<a href="'.$stack->getPrevious().'">Back</a>';

} elseif ($showform == "saved" ) {
	$redirect2='?zfaces=list&form='.$form.'&zft='.$zft.'&zfp='.$zfp;
	if (isset($stack) && $stack->getPrevious()) $redirect2=$stack->getPrevious();
	echo '<a href="'.$redirect2.'" class="button">Back</a>';
	if (!$redirect && (!defined("ZING_SAAS") || !ZING_SAAS)) {
		//header('refresh:0; url='.$redirect2);
	}
}
