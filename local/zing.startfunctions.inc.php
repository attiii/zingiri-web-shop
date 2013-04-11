<?php
/*  zing.startfunctions.inc.php
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
include (ZING_DIR."./includes/index.php");         // general functions
require(ZING_DIR."./classes/index.php");         // general classes
if (get_option('zing_webshop_proX')) {
	require_once(get_option('zing_webshop_pro_dir')."classes/index.php");         // general classes
}
include (ZING_LOC."./zing.subs.readcookie.inc.php");  // cookie functions
?>