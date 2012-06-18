<?php
class zfStack {
	var $key;

	function zfStack($type,$form,$extra='') {
		$zfp=isset($_GET['zfp']) ? intval($_GET['zfp']) : 0;
		if (!$zfp) unset($_SESSION['stack']);
		$this->key=$type.'-'.$form;
		if (isset($_SESSION['stack'])) $c=count($_SESSION['stack']); else $c=0;
		if (isset($_SESSION['stack'][$this->key])) {
			$delete=false;
			foreach ($_SESSION['stack'] as $i => $s) {
				if ($delete) unset($_SESSION['stack'][$i]);
				if ($i == $this->key) $delete=true;
			}
		}
		$q="";
		foreach ($_GET as $i => $v) {
			if ($q) $q.="&";
			$q.=$i.'='.urlencode(stripslashes($v));
		}
		$_SESSION['stack'][$this->key]=$q.$extra;
		$c=count($_SESSION['stack']);
		if ($c > 1) $this->previous=(isset($_SESSION['stack'][$c-2])) ? $_SESSION['stack'][$c-2] : '';
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
			if (is_admin()) return '?'.$previous;
			elseif ($home=get_option("home")) return $home.'/index.php?'.$previous;
			else return '?'.$previous;
		}
		else return false;
	}
}