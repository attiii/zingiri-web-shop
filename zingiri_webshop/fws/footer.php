<?php
/*  footer.php
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
      //website start year and current year
      $current_year = date("Y");

      if ($start_year == $current_year) { 
	      $footer_year = $current_year; 
	  }
	  else { $footer_year = $start_year."-".$current_year; }
    
	  echo $shopname ?> | <?php if (!is_null($page_footer)) { echo $page_footer." | "; } ?> &copy;<?php echo $footer_year;

  	  // if you want to remove this POWERED BY FREEWEBSHOP.ORG line, please donate 9 euro's to my project..
	  // read about it here: http://www.freewebshop.org/?id=32
	  echo "<br /><br />";
      echo "<a class=\"plain\" href=\"http://www.freewebshop.org\"><img src=\"".$gfx_dir."/poweredby.png\" alt=\"Powered by FreeWebshop.org\" class=\"borderless\" /></a>";

?>
