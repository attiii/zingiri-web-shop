<?php
if (!class_exists('aphps')) {
	class aphps {
		var $actions=array();
		var $bootstrap=false;
		var $form=null;

		
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

		function showForm($name,$mode,$id=0,$options=array()) {
			$this->bootstrap();
			if (!$this->form) {
				$this->form=new zfForm($name,null,null,$mode,'form',$id);
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
				$this->form->Render($mode);
			}
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
