<?php
/*  settings.inc.php
 Copyright 2006, 2007 Elmar Wenners
 Support site: http://www.chaozz.nl

 This file is part of FreeWebshop.org.

 FreeWebshop.org is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FreeWebshop.org is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
?>
<?php
//lookup database parameters in Wordpress configuration file
define('ABSPATH', dirname(__FILE__) . '/');
require(dirname(__FILE__)."/../../../../../wp-config.php");
// database values
$dbtablesprefix = $table_prefix."zing_";
$dblocation = DB_HOST;
$dbname = DB_NAME;
$dbuser = DB_USER;
$dbpass = DB_PASSWORD;
?>