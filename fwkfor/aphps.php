<?php
if (!class_exists('aphps')) {
	class aphps {
		var $actions=array();
		var $bootstrap=false;
		var $form=null;
		var $jsDateFormat='dd-mm-yy';

		function addAction($action,$f) {
			$this->actions[$action]=$f;
		}

		function doAction($action,&$p1='',&$p2='',&$p3='') {
			if (isset($this->actions[$action]) && ($f=$this->actions[$action])) {
				$f($p1,$p2,$p3);
			}
		}

		function bootstrap() {
			if ($this->bootstrap) return;
			global $aphps_projects;
			require_once(ZING_APPS_PLAYER_DIR."includes/all.inc.php");
			$this->bootstrap=true;
		}

		function showList($formIdOrName=null) {
			global $line,$page;
			if (!$page) $page='aphps';
				
			$notitle=true;
			if (is_numeric($formIdOrName)) $formid=$formIdOrName;
			elseif ($formIdOrName) $formname=$formIdOrName;
			if (defined("ZING_APPS_CUSTOM")) { require(ZING_APPS_CUSTOM."globals.php"); }
			require_once(dirname(__FILE__)."/includes/all.inc.php");
			require(dirname(__FILE__).'/scripts/list.php');
		}

		function editForm($formIdOrName,$action) {
			global $page;
			if (!$page) $page='aphps';
			if (is_numeric($formIdOrName)) $formid=$formIdOrName;
			elseif ($formIdOrName) $formname=$formIdOrName;
			$this->bootstrap();
			require(dirname(__FILE__)).'/scripts/form.php';
		}

		function showForm($formIdOrName,$mode,$id=0,$options=array(),$showLabels=true) {
			$this->bootstrap();
			if (!$this->form) {
				if (is_numeric($formIdOrName)) {
					$this->form=new zfForm(null,$formIdOrName,null,$mode,'form',$id);
					
				} else {
					$this->form=new zfForm($formIdOrName,null,null,$mode,'form',$id);
				}
				$this->form->noAlert=true;
			}
			$this->form->Prepare($id);
			if ($this->form->allowAccess()) {
				$allowed=true;
				$newstep="save";
				if (isset($options['input'])) {
					$this->form->output=array_merge($this->form->output,$options['input']);
					$this->form->input=array_merge($this->form->input,$options['input']);
				}
				$this->form->showLabels=$showLabels;
				$this->form->Render($mode);
			}
		}

		function verifyForm($formIdOrName,$mode,$id=0) {
			$this->bootstrap();

			if (!$this->form) {
				if (is_numeric($formIdOrName)) {
					$this->form=new zfForm(null,$formIdOrName,null,$mode,'form',$id);
				} else {
					$this->form=new zfForm($formIdOrName,null,null,$mode,'form',$id);
				}
				$this->form->noAlert=true;
			}
			
			if ($this->form->Verify($_POST,$id)) return true;
			else return false;
		}
		
		function processForm($name,$mode,$id=0) {
			$this->bootstrap();

			if (!$this->form) {
				$this->form=new zfForm($name,null,null,$mode,'form',$id);
				$this->form->noAlert=true;
			}
			if ($mode=='edit') {
				$newstep="save";
				if ($this->form->Verify($_POST,$id))
				{
					if ($this->form->allowAccess()) {
							
						$allowed=true;
						$this->form->Save($id);
						$showform="saved";
							
						return true;
					}
				} else {
					if ($this->form->allowAccess()) {
						$allowed=true;
						//					$this->form->Render($mode);
					}
				}
			} elseif ($mode=='add') {

			}
			return false;

		}
	}
	global $aphps;
	$aphps=new aphps();
}

