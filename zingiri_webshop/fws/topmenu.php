<?php
/*  my.php
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
       <ul>
<?php
           echo "<li><a href=\"index.php?page=main\">" . $txt['menu1'] . "</a></li>";
           echo "<li><a href=\"index.php?page=cart&action=show\">" . $txt['menu2'] . " (".CountCart($customerid).")</a></li>";
           if ($conditions_page == 1) { echo "<li><a href=\"index.php?page=conditions&action=checkout\">" . $txt['menu3'] . "</a></li>"; }
           else { echo "<li><a href=\"index.php?page=shipping\">" . $txt['menu3'] . "</a></li>"; }
           
           if (IsAdmin() == true) {
 	            echo "<li><a href=\"index.php?page=admin&version=$version\">" . $txt['menu9'] . "</a></li>";
           }
           if (LoggedIn() == true) {    
	            echo "<li><a href=\"index.php?page=my&id=".$customerid."\">".$txt['menu10']." ("; PrintUsername($txt['header3']); echo ")</a></li>";
	            echo "<li><a href=\"logout.php\">" . $txt['menu11'] . "</a></li>";
           }
           else {
	            echo "<li><a href=\"index.php?page=my\">" . $txt['menu12'] . "</a></li>";
	            echo "<li><a href=\"index.php?page=customer&action=new\">" . $txt['menu13'] . "</a></li>";
           }
           echo "<li>";
           ShowFlags($lang_dir,$gfx_dir);
           echo "</li>";
?>
       </ul>