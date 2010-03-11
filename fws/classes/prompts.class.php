<?php
class zingPrompts {
	var $vars=array('lastname','shopname','webid','customerid','shopurl','company','initials','middlename','address',
				'zipcode','city','state','country','phone','bankaccount','bankaccountowner','bankcity','bankcountry',
				'bankname','bankiban','bankbic','paymentdays','tussenvoegsels','naam','login','pass1','gfx_dir',
				'gname','cname','send_default_country');

	var $langs= array(
				"en" => "English",
				"nl" => "Dutch",
				"fr" => "French",
				"de" => "German",
				"es" => "Spanish",
				"br" => "Brazilian",
				"dk" => "Danish",
				"ee" => "Estonian",
				"fi" => "Finish",
				"gr" => "Greek",
				"hu" => "Hungarian",
				"it" => "Italian",
				"no" => "Norwegian",
				"pl" => "Polish",
				"pt" => "Portuguese",
				"ro" => "Romanian",
				"ru" => "Russian",
				"se" => "Swedish",
				"th" => "Thai",
				"tr" => "Turkish",
				"yu" => "Serbian");
	
	var $lang;

	function zingPrompts($lang='en') {
		$this->lang=$lang;
	}

	function checkAllLanguages() {
		$ref=$this->loadLang('en');
		$db=new db();		
		if ($handle = opendir(ZING_DIR.'langs')) {
			while (false !== ($filex = readdir($handle))) {
				if (!strstr($filex,"en") && !strstr($filex,".") && !strstr($filex,"..") && !strstr($filex,"index.php")) {
					$txt=array();
					$txt=$this->loadLang($filex);
					foreach ($ref as $label => $text) {
						if (!isset($txt[$label])) {
							//echo $filex.': Label '.$label.' missing<br />';
							$db->insertRecord('prompt',"",array('lang' => $filex,'standard' => $text,'label' => $label));
						}
//						elseif ($txt[$label] == $ref[$label]) echo $filex.': Label '.$label.' not translated<br />';
					}
				}
			}
			closedir($handle);
		}
	}
	
	function loadLang($lang='en') {
		$db=new db();
		$db->select("select * from ##prompt where lang=".qs($lang));
		while ($db->next()) {
			$txt[$db->get('label')]=$db->get('standard');
		}
		return $txt;
	}
	
	function convertAllLanguages() {
		if ($handle = opendir(ZING_DIR.'langs')) {
			while (false !== ($filex = readdir($handle))) {
				if (!strstr($filex,".") && !strstr($filex,"..") && !strstr($filex,"index.php")) {
					$this->convertLangFile($filex);
				}
			}
			closedir($handle);
		}
	}
	
	function convertLangFile($lang) {
		$db=new db();

		$db->update("delete from ##prompt where lang=".qs($lang));
		foreach ($this->vars as $var) {
			$$var='$'.$var;
		}
		require(ZING_DIR.'langs/'.$lang.'/lang.txt');
		foreach ($txt as $label => $text) {
			$db->insertRecord('prompt',"",array('lang' => $lang,'standard' => $text,'label' => $label));
		}
		foreach (array('main','conditions') as $file) {
			$handle=fopen(ZING_DIR.'langs/'.$lang.'/'.$file.'.txt','r');
			$size=filesize(ZING_DIR.'langs/'.$lang.'/'.$file.'.txt');
			$text=fread($handle, $size);
			$db->insertRecord('prompt',"",array('lang' => $lang,'standard' => $text,'label' => $file));
			fclose($handle);
		}
	}

	function get($label) {
		global $txt;
		return $txt[$label];
	}

	function set($label,$text) {
		global $txt;
		$lang=$this->lang;
		$db=new db();
		$db->updateRecord('prompt',array('lang' => $lang,'label' => $label),array('custom' => $text));
		$txt[$label]=$text;
	}

	function load($parse=false) {
		global $txt;

		$old=$this->loadOldLangFile();

		$db=new db();
		$db->select("select * from ##prompt where lang=".qs($this->lang));
		while ($db->next()) {
			$txt[$db->get('label')]=$db->get('standard');
			if (isset($old[$db->get('label')]) && trim($old[$db->get('label')]) != trim($db->get('standard'))) {
				if (trim($db->get('custom')) == "") {
					$this->set($db->get('label'),trim($old[$db->get('label')]));
				}
			}
			if ($db->get('custom') != "") $txt[$db->get('label')]=$db->get('custom');
			else $txt[$db->get('label')]=$db->get('standard');
		}
		
		if ($parse) {
			foreach ($this->vars as $var) {
				global $$var;
				$txt=str_replace('$'.$var,$$var,$txt);
			}
		}
		
		return $txt;
	}


	function loadOldLangFile() {
		$txt=array();
		foreach ($this->vars as $var) {
			$$var='$'.$var;
		}
		require(ZING_DIR.'langs/'.$this->lang.'/lang.txt');
		return $txt;
	}

	function parse($prompt) {
		foreach ($this->vars as $var) {
			global $$var;
			$prompt=str_replace('$'.$var,$$var,$prompt);
		}
		return $prompt;
	}

}
?>