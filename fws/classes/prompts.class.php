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
	var $activeLanguages=array();

	function zingPrompts($lang='en') {
		$this->lang=$lang;
		$this->setActiveLanguages();
	}

	function checkAllLanguages() {
		foreach($this->activeLanguages as $filex) {
			if (!strstr($filex,"en") && !strstr($filex,".") && !strstr($filex,"..") && !strstr($filex,"index.php")) {
				$this->checkLanguage($filex);
			}
		}
	}

	function checkLanguage($lang) {
		$db=new db();
		$ref=$this->loadLang('en');
		$txt=array();
		$txt=$this->loadLang($lang);
		foreach ($ref as $label => $text) {
			if (!isset($txt[$label])) {
				$db->insertRecord('prompt',"",array('lang' => $lang,'standard' => $text,'label' => $label));
			}
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

	function installAllLanguages() {
		$db=new db();
		$this->convertAllLanguages();
		$this->checkAllLanguages();
	}

	function convertAllLanguages() {
		foreach($this->activeLanguages as $filex) {
			if (!strstr($filex,".") && !strstr($filex,"..") && !strstr($filex,"index.php")) {
				$this->convertLangFile($filex);
			}
		}
	}

	function convertLangFile($lang) {
		$db=new db();

		foreach ($this->vars as $var) {
			$$var='$'.$var;
		}
		require(ZING_DIR.'langs/'.$lang.'/lang.txt');
		foreach ($txt as $label => $text) {
			if ($row=$db->readRecord('prompt',array('lang' => $lang,'label' => $label))) {
				if ($row['STANDARD'] != $text) {
					$db->updateRecord('prompt',array('lang' => $lang,'label' => $label),array('standard' => $text));
				}
			} else {
				$db->insertRecord('prompt',"",array('lang' => $lang,'standard' => $text,'label' => $label));
			}
		}
		foreach (array('main','conditions') as $file) {
			$handle=fopen(ZING_DIR.'langs/'.$lang.'/'.$file.'.txt','r');
			$size=filesize(ZING_DIR.'langs/'.$lang.'/'.$file.'.txt');
			$text=fread($handle, $size);
			if ($row=$db->readRecord('prompt',array('lang' => $lang,'label' => $file))) {
				if ($row['STANDARD'] != $text) {
					$db->updateRecord('prompt',array('lang' => $lang,'label' => $file),array('standard' => $text));
				}
			} else {
				$db->insertRecord('prompt',"",array('lang' => $lang,'standard' => $text,'label' => $file));
			}
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

		//check if this language has been loaded in DB already, otherwise do it now
		if (!$this->isLanguageActive($this->lang)) {
			$this->convertLangFile($this->lang);
			$this->checkLanguage($this->lang);
		}

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

	function isLanguageActive($lang) {
		if (in_array($lang,$activeLanguages)) return true;
		else return false;
	}

	function setActiveLanguages() {
		$langs=array();
		$db=new db();
		$db->select("select distinct(lang) from ##prompt");
		while ($db->next()) {
			$langs[]=$db->get('lang');
		}
		$deflang=$this->currentLanguage();
		if (!in_array($deflang,$langs)) $langs[]=$deflang;
		if (!in_array('en',$langs)) $langs[]='en';
		$this->activeLanguages=$langs;
	}

	function currentLanguage() {
		$db=new db();
		$db->select("select default_lang from ##settings where ID=1");
		if ($db->next()) $lang=$db->get('default_lang');
		else $lang='en';
		return $lang;
	}

}
?>