<?php
/*  all.inc.php
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
if (!defined("FACES_DIR")) {
	define("FACES_DIR", dirname(__FILE__)."/../fields/");
}
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');

if (!defined("ZING_SAAS") || !ZING_SAAS) require(dirname(__FILE__)."/../../../../../wp-config.php");
$dbtablesprefix = $table_prefix."zing_";
define("DB_PREFIX",$dbtablesprefix);
$dblocation = DB_HOST;
$dbname = DB_NAME;
$dbuser = DB_USER;
$dbpass = DB_PASSWORD;

if (!extension_loaded('json')) require(dirname(__FILE__).'/JSON.php');

require(dirname(__FILE__)."/../includes/faces.inc.php");
require(dirname(__FILE__)."/../includes/connectdb.inc.php");
require(dirname(__FILE__)."/../includes/db.inc.php");

require(dirname(__FILE__)."/../classes/index.php");

if (defined("ZING_APPS_CUSTOM")) {
	if ($handle = opendir(ZING_APPS_CUSTOM.'classes')) {
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,"class.php")) {
				require_once(ZING_APPS_CUSTOM."classes/".$file);
			}
		}
		closedir($handle);
	}
}

if (!defined("ZING_APPS_MAX_ROWS"))
define ("ZING_APPS_MAX_ROWS",15);

?>