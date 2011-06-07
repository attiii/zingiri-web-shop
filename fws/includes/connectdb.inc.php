<?php
/*  connectdb.inc.php
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
    // connect to db
	if  ($dblocation == "" || $dbuser == "") { echo "<h1>Please run <a href=\"install.php\">the installation</a> first</h1>"; exit; }
    $db = @mysql_connect($dblocation,$dbuser,$dbpass) or die("<h1>Could not connect to the database. Please check your settings</h1>");
 	@mysql_select_db($dbname,$db) or die("<h1>Could not connect to the database. Please check your settings</h1>");
?>