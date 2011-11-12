<?php
class wsTemplate {
	var $content;
	var $row=array();
	var $values=array();

	function wsTemplate($templatename,$lang='') {
		$this->name=$templatename;
		$db=new db();
		if ($db->select("select * from ##template where name=".qs($templatename).' and lang='.qs($lang)) && $db->next()) {
			$this->content=$db->get('content');
		} else {
			$this->content=file_get_contents(ZING_TPL_DIR.$this->name.'.tpl.php');
		}
		$s[]='/\[TEXT:(.*?)\]/';
		$this->content=preg_replace_callback($s,array($this,'txt'),$this->content);
	}

	private function txt($matches) {
		global $txt;
		$i=strtolower($matches[1]);
		return $txt[$i];
	}

	private function cond($matches) {
		$hide=false;
		$style='display:none';

		list($v1,$c,$v2)=explode(' ',$matches[2]);

		if ($c=='eq' && !($v1==$v2)) $hide=true;
		elseif ($c=='neq' && !($v1!=$v2)) $hide=true;
		elseif ($c=='gt' && !($v1>$v2)) $hide=true;

		if (!$hide) {
			return '<'.$matches[1].' '.$matches[3].'>';
		} elseif ($matches[1] != ($r=preg_replace('/style="(.*?)"/','style="$1;'.$style.'"',$matches[1]))) {
			return '<'.$r.' '.$matches[3].'>';
		} elseif ($matches[3] != ($r=preg_replace('/style="(.*?)"/','style="$1;'.$style.'"',$matches[3]))) {
			return '<'.$matches[1].' '.$r.'>';
		} else {
			return '<'.$matches[1].' style="'.$style.'" '.$matches[3].'>';
		}
	}

	function conditions() {
		$s[]='/<(.*)condition\="(.*?)"(.*?)>/';
		$this->content=preg_replace_callback($s,array($this,'cond'),$this->content);

	}

	function parse($field,$value) {
		$this->content=str_replace('['.strtoupper($field).']',$value,$this->content);
		$this->values[$field]=$value;
	}

	function replace($field,$fill) {
		$search='['.$field.']';
		if (trim($fill)=='') {
			while (strstr($this->content,$search)!==false) {
				if (($p=strpos($this->content,$search)) !== false) {
					$i=$p+strlen($search);
					while ($i<=strlen($this->content) && substr($this->content,$i,1)==' ')  {
						$i++;
					}
					//echo substr($this->content,$i,6);
					if (substr($this->content,$i,6)=='<br />') {
						$this->content=substr($this->content,0,$p).substr($this->content,$i+6);
					} else {
						$this->content=substr($this->content,0,$p).substr($this->content,$p+strlen($search));
					}
				}
			}
		}
		$this->content=str_replace($search,$fill,$this->content);
	}

	function repeatRow($fields) {
		$set=md5(implode('',$fields));

		if (!isset($this->row[$set])) {
			$p=false;
			foreach ($fields as $field) {
				if ($p===false) $p=strpos($this->content,$field);
			}
			if (!$p===false) {
				$s=$e=$p;
				while ($s>0 && substr($this->content,$s,3)!='<tr') {
					$s--;
				}
				while ($e<=strlen($this->content) && substr($this->content,$e,5)!='</tr>') {
					$e++;
				}
				$e+=5;

				if ($s>0 && $e<=strlen($this->content)) {
					$this->row[$set]=substr($this->content,$s,$e-$s);
					$this->content=substr($this->content,0,$e).'<!--ws'.$set.'-->'.substr($this->content,$e);
				}
			}
		} else {
			$this->content=str_replace('<!--ws'.$set.'-->',$this->row[$set].'<!--ws'.$set.'-->',$this->content);
		}
	}

	function removeRow($fields) {
		$p=false;
		foreach ($fields as $field) {
			if ($p===false) $p=strpos($this->content,$field);
		}
		if (!$p===false) {
			$s=$e=$p;
			while ($s>0 && substr($this->content,$s,3)!='<tr') {
				$s--;
			}
			while ($e<=strlen($this->content) && substr($this->content,$e,5)!='</tr>') {
				$e++;
			}
			$e+=5;

			if ($s>0 && $e<=strlen($this->content)) {
				$this->content=substr($this->content,0,$s).substr($this->content,$e);
			}
		}
	}

	function replaceTitle($field,$fill) {
		//$this->title=str_replace('['.$field.']',$fill,$this->title);
	}

	function getContent() {
		$content=$this->content;
		if (count($this->row) > 0) {
			foreach ($this->row as $row) {
				$content=str_replace('<!--ws'.$row.'-->','',$content);
			}
		}
		return $content;
	}

}
?>