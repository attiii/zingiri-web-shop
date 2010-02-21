<?php
/*  link.class.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Apps.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Zingiri Apps; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
class zfLink {

	var $links=array();
	var $escape_quote;

	function zfLink($id,$escape_quote=false,$type='form') {
		$this->escape_quote=$escape_quote;
		$a=array();
		$links=new zfDB();
		$links->select("select * from ##flink where formin='".$id."' and displayin=".zfqs($type));
		while ($link=$links->next()) {
			$link['IMAGE']=$link['ICON'] ? $link['ICON'] : "edit.png";
			$a[]=$link;
		}
		$this->links=$a;
	}

	function getLinks($id) {
		$a=array();
		foreach ($this->links as $i => $link) {
			$map=array();
			$url=$link['FORMOUTALT'];
			if ($link['MAPPING']) {
				$s=explode(",",$link['MAPPING']);
				foreach ($s as $value) {
					$v=explode(":",$value);
					$from=$v[1];
					$to=$v[0];
					if ($$from) $map[$to]=$$from;
					elseif ($_POST[$from]) $map[$to]=$_POST[$from];
					elseif ($_GET[$from]) $map[$to]=$_GET[$from];
					else $map[$to]=$from;
					$url.="&".$to."=".$map[$to];
				}
				$json=zf_json_encode($map);
				$json=str_replace('"',"'",$json);
				if ($this->escape_quote) $json=str_replace("'","\'",$json);
			}
			$link['MAP']=$json;
			$link['URL']=$url;
			$a[]=$link;
		}
		return $a;

	}

}
?>