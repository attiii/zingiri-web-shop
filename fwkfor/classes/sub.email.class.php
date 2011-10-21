<?php
/*  sub.email.class.php
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
class emailZfSubElement extends zfSubElement {
	
	function verify() {
		$email=$this->int;
		if ($email=='') return true;
		
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/i", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			$this->error_message="Wrong email format!";
			$this->is_error=true;
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/i", $local_array[$i])) {
				$this->error_message="Wrong email format!";
				$this->is_error=true;
				return false;
			}
		}
		if (!preg_match("/^\[?[0-9\.]+\]?$/i", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				$this->error_message="Wrong email format!";
				$this->is_error=true;
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/i", $domain_array[$i])) {
					$this->error_message="Wrong email format!";
					$this->is_error=true;
					return false;
				}
			}
		}
		return true;
	}
}