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
<?php if ($index_refer <> 1) { exit(); } ?>
<?php 
require(ZING_DIR."includes/checklogin.inc.php");
if (LoggedIn() == 1) {
    PutWindow($gfx_dir, $txt['my3'], $txt['my4'], "personal.jpg", "80");
?>
     <br />
     <br />     
     <table width="80%" class="datatable">
      <caption><?php echo $txt['my5']; ?></caption>
      <tr><td>
           <table width="100%" class="borderless"><tr>
             <td><div style="text-align:center;"><img src="<?php echo $gfx_dir ?>/key.gif" alt="" /><br /><?php echo $txt['my6'] ?>: <?php echo $customerid ?></div></td>
             <td><div style="text-align:center;"><a class="plain" href="<?php zurl("index.php?zfaces=form&form=profile&action=edit&id=".$customerid,true);?>"><img src="<?php echo $gfx_dir ?>/customers.gif" alt="" /><br /><?php echo $txt['my7'] ?></a></div></td>
             <td><div style="text-align:center;"><a class="plain" href="<?php zurl("index.php?zfaces=form&form=password&action=edit&id=".$customerid,true);?>"><img src="<?php echo $gfx_dir ?>/password.gif" alt="" /><br /><?php echo $txt['customer16'] ?></a></div></td>
             <td><div style="text-align:center;"><a class="plain" href="<?php zurl("index.php?zfaces=list&form=address&map=".faces_map(array('customerid' => $customerid)),true);?>"><img src="<?php echo $gfx_dir; ?>/address.png" height="32px" alt="" /><br /><?php echo $txt['my10'] ?></a></div></td>
             </tr><tr>
             <td></td>
             <td><div style="text-align:center;"><a class="plain" href="<?php zurl("index.php?page=orders&id=".$customerid,true); ?>"><img src="<?php echo $gfx_dir; ?>/orders.jpg" width="32px" height="32px" alt="" /><br /><?php echo $txt['my8'] ?></a></div></td>
             <td><div style="text-align:center;"><a class="plain" href="<?php zurl("index.php?page=cart&action=show",true)?>"><img src="<?php echo $gfx_dir; ?>/carticon.gif" alt="" /><br /><?php echo $txt['my9'] ?></a></div></td>
             <td><div style="text-align:center;"><a class="plain" href="<?php zurl("index.php?page=products&action=show",true)?>"><img src="<?php echo $gfx_dir; ?>/products.gif" alt="" /><br /><?php echo $txt['admin5'] ?></a></div></td>
           </tr></table>
          </td> 
      </tr>
     </table>
<?php } ?>     