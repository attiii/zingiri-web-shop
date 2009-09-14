<?php
/*  startmodules.inc.php
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
if (!defined("ZING_SUB")) define("ZING_SUB","");
	include ("./includes/readcookie.inc.php");      // read the cookie
	include ("./includes/settings.inc.php");        // database settings
	include ("./includes/connectdb.inc.php");       // connect to db
	include ("./includes/subs.inc.php");            // general functions
	include ("./includes/readvals.inc.php");        // get and post values
	include ("./includes/readsettings.inc.php");    // read shop settigns
	include( "./includes/setfolders.inc.php");      // set appropriate folders
	include ("./includes/initlang.inc.php");        // init the language
    include ("./".$lang_file);                         // read the language
?>