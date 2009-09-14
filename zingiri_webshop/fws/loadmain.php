<?php
/*  loadmain.php
    Copyright 2006, 2007, 2008 Elmar Wenners
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
<?php if ($index_refer <> 1) { exit(); } ?>
<?php
    if (file_exists('install.php')) { 
	    PutWindow($gfx_dir, $txt['header12'],$txt['header4'],"warning.gif", "50"); 
	}
    elseif ($shop_disabled == 1 && IsAdmin() == false && $page != "my") { 
	    PutWindow($gfx_dir, $shop_disabled_title,$shop_disabled_reason,"warning.gif", "50"); 
	}
	elseif (IsBanned() == true) { 
		PutWindow($gfx_dir, $txt['general12'],$txt['general10'],"warning.gif", "50"); 
	}
    else {
		    if (file_exists(dirname(__FILE__)."/$page.php")) {
		       include (dirname(__FILE__)."/$page.php");
		    }
		    else {
		       PutWindow($gfx_dir, $txt['general12'], $txt['general9'], "warning.gif", "50");
		    }
	}
?>