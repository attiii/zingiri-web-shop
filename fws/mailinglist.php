<?php
/*  mailing.php
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
// admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {  
    $mailinglist = "";
    $query = "SELECT * FROM `".$dbtablesprefix."customer` WHERE `NEWSLETTER` = '1'"; 
    $sql = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_row($sql)) {
	       $mailinglist = $row[12].", ".$mailinglist;
	}
?>
  <table class="borderless" width="100%">
   <tr><td>
    <textarea rows="30" cols="65" readonly><?php echo $mailinglist ?></textarea><br />
   </td></tr>
  </table>   
<?php	
}    
?>