<?php
/*  link.class.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.aphps.com

 This file is part of APhPS.

 APhPS is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 APhPS is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with APhPS; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
class zfLink {

	var $links=array();
	var $escape_quote;
	var $canAdd=false;
	var $linkAdd=array();

	function zfLink($id,$escape_quote=false,$type='form',$position='R') {
		$this->escape_quote=$escape_quote;
		$a=array();
		$links=new aphpsDb();
		$sql="select * from ##flink where formin='".$id."' and displayin=".qs($type);
		if ($position=='R') $sql.=' and (position="R" or position="" or position is null)';
		else $sql.=' and position='.qs($position);
		if ($links->select($sql)) {
			while ($link=$links->next()) {
				$link['IMAGE']=$link['ICON'] ? $link['ICON'] : "edit.png";
				if ($link['ACTION']=='add') {
					$this->canAdd=true;
					$this->linkAdd=$link;
				}
				$a[]=$link;
			}
		}
		$this->links=$a;
	}

	/*
	 * Show all links except add
	 */
	function getLinks($id='',$data=array()) {
		$a=array();
		foreach ($this->links as $i => $link) {
			if ($link['ACTION']!='add') {
				$map=array();
				$url=$link['FORMOUTALT'];
				if ($link['MAPPING']) {
					$s=explode(",",$link['MAPPING']);
					foreach ($s as $value) {
						$v=explode(":",$value);
						$from=$v[1];
						$to=$v[0];
						if ($$from) $map[$to]=(string)$$from;
						elseif ($_POST[$from]) $map[$to]=$_POST[$from];
						elseif ($_GET[$from]) $map[$to]=$_GET[$from];
						elseif (isset($data[$from])) $map[$to]=$data[$from];
						elseif (isset($data[strtoupper($from)])) $map[$to]=$data[strtoupper($from)];
						elseif (isset($data[strtolower($from)])) $map[$to]=$data[strtolower($from)];
						else $map[$to]=$from;
						$url.="&".$to."=".$map[$to];
					}
					$json=serialize($map);
				}
				$link['MAP']=isset($json) ? $json : '';
				$link['URL']=$url;
				$a[]=$link;
			}
		}
		return $a;
	}
}
?>