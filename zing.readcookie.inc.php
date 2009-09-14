<?php
/*  zing.readcookie.inc.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Web Shop.

 Zingiri Web Shop is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Web Shop is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
// open the cookie and read the fortune ;-)
if (isset($_COOKIE['fws_cust'])) {
	$fws_cust = explode("-", $_COOKIE['fws_cust']);
	$name = $fws_cust[0];
	$customerid = $fws_cust[1];
	$md5pass = $fws_cust[2];
}
else {
	// you're not logged in, so you're a guest. let's see if you already have a session id
	if (!isset($_COOKIE['fws_guest'])) {
		$fws_guest = create_sessionid(8); // create a sessionid of 8 numbers, assuming a shop will never get 10.000.000 customers it's always a non existing customer id
		setcookie ("fws_guest", $fws_guest, time()+3600);
		$customerid = $fws_guest;

	}
	else {
			
		$customerid = $_COOKIE['fws_guest'];
	}
}
?>