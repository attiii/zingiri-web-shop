<?php
/*  readvals.inc.php
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
    // some values we read through get or post 
	$group = 0;
	$cat   = 0;
	$page  = "main";
	
	if (!empty($_GET['page'])) {
	    $page=htmlspecialchars(strip_slashes($_GET['page']));
	}
	if (!empty($_GET['action'])) {
	    $action=htmlspecialchars(strip_slashes($_GET['action']));
	}
	if (!empty($_POST['action'])) {
	    $action=htmlspecialchars(strip_slashes($_POST['action']));
	}
	if (!empty($_GET['cat'])) {
	    $cat=intval(htmlspecialchars(strip_slashes($_GET['cat'])));
	}
	if (!empty($_GET['prod'])) {
	    $prod=intval(htmlspecialchars(strip_slashes($_GET['prod'])));
	}
	if (!empty($_GET['group'])) {
	    $group=intval(htmlspecialchars(strip_slashes($_GET['group'])));
	}
?>