<?php
/*  stockadmin.php
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
?><?php
// admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], $gfx_dir."/warning.gif", "50");
}
else {
	if (!empty($_POST['minimal_stock'])) {
	   $stock_warning_level = intval($_POST['minimal_stock']);
	}
?>
    <div align="center">
	    <table border="0">
		 <tr><td align="left">
	         <form method="POST" action="<?php zurl('?page=stockadmin',true)?>">
               <strong><?php echo $txt['productadmin12']; ?> <</strong>
		       <input type="text" name="minimal_stock" value="<?php echo $stock_warning_level; ?>" size="5">
			   <input type="submit" name="filterstock" value="<?php echo $txt['stockadmin2']; ?>">
			   <input type="submit" name="showall" value="<?php echo $txt['editsettings86']; ?>">
		     </form>
		 </td></tr>
		</table>
	</div>	
<?php		
	if (!empty($_POST['showall'])) { 
	    $query ="SELECT * FROM ".$dbtablesprefix."product ORDER BY STOCK ASC";
	} else {
	    $query ="SELECT * FROM ".$dbtablesprefix."product WHERE STOCK < ". qs($stock_warning_level) . " ORDER BY STOCK ASC";
	} 
    $sql = mysql_query($query) or die(mysql_error());
?>
    <table width="100%" class="datatable">
     <tr> 
      <th><?php echo $txt['productadmin9']; ?></th>
      <th><?php echo $txt['productadmin12']; ?></th>
     </tr>

    <?php
    if (mysql_num_rows($sql) == 0) {
        echo "<tr><td colspan=\"5\">" . $txt['browse5'] ."</td></tr>";
    }
    else {
        while ($row = mysql_fetch_row($sql)) {
	       echo "<tr>";
		   echo "<td>". $row[1] . " <a href=\"".zurl("?page=product&zfaces=form&form=product&action=edit&id=".$row[0]."&redirect=".wsCurrentPageURL(true))."\">".$txt['browse7']."</a></td>";
"</td>";   echo "<td>". $row[5] . "</td>";
		   echo "</tr>";
	    }
	}
    echo "</table>";
}	
?>