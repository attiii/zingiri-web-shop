<?php
/*  sub.ip.class.php
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
class ipZfSubElement extends zfSubElement {
	function verify()
	{
		$isValid=true;
		
		$ip=explode('.',$this->ext);
		if (count($ip) > 0) {
			foreach ($ip as $n) {
				if (!is_numeric($n) || $n < 0 || $n > 255) $isValid=false;
			}
		} else $isValid=false;
		
		if (!$isValid) return $this->error("IP address format  is not valid!");
		$this->ext=$this->int;
		return true;
	}
}
?>