<?php
class zfStack {
	var $key;

	function zfStack($type,$form) {
		$zfp=intval($_GET['zfp']);
		if (!$zfp) unset($_SESSION['stack']);
		$q=$_SERVER['QUERY_STRING'];
		$this->key=$type.'-'.$form;
		if (isset($_SESSION['stack'])) $c=count($_SESSION['stack']); else $c=0;
		if (isset($_SESSION['stack'][$this->key])) {
			$delete=false;
			foreach ($_SESSION['stack'] as $i => $s) {
				if ($delete) unset($_SESSION['stack'][$i]);
				if ($i == $this->key) $delete=true;
			}
		}else {
			$_SESSION['stack'][$this->key]=$q;
		}
		$c=count($_SESSION['stack']);
		if ($c > 1) $this->previous=$_SESSION['stack'][$c-2];
	}

	function getPrevious() {
		$previous="";
		if (count($_SESSION['stack']) > 0) {
			foreach ($_SESSION['stack'] as $i => $s) {
				if ($i == $this->key) break;
				else $previous=$s;
			}
		}
		if (!empty($previous)) {
			return get_option("home").'/index.php?'.$previous;
		}
		else return false;
	}
}