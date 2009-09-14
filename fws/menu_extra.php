<?php
/*  menu_extra.php
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
	//extra pages?
	if ($dir = @opendir($lang_dir."/".$lang)) {
		$extra_pages = 0;
		$files = array();
		while (($file = readdir($dir)) !== false) {
			if (substr($file, strlen($file) - 4) == '.fws' && substr($file,0,1) != '~') {
                array_push($files, $file);
				$extra_pages += 1;
			}
		}
		closedir($dir);
	}

	if ($extra_pages > 0) {
		echo "<h1>".$txt['menu19']."</h1>\n"; 
		echo "<ul id=\"navlist\">\n";
		sort($files);
		foreach ($files as $file) {
			$filename = explode(".",$file);
			echo "<li"; if ($page == "info" && $action== $filename[0]) { echo " id=\"active\""; }; echo "><a href=\"index.php?page=info&action=".$filename[0]."\">" . $filename[0] . "</a></li>\n";
		}
		// print extra menu end code
		echo "</ul>\n";
	}  
?>