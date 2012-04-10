<?php
/*  about.php
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
<?php //include ("includes/httpclass.inc.php"); ?>
<?php 
if (!IsAdmin()) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {    
?>


     <table width="100%" class="datatable">
      <tr><td> 
            <h6>Version info</h6>
            Zingiri Web Shop version <strong><?php echo $zing_version ?></strong><br />
			PHP version <strong><?php echo phpversion(); ?></strong><br />
			MySQL version <strong><?php echo mysql_get_server_info(); ?></strong><br />
			GD-enabled <strong><?php echo (extension_loaded('gd')) ? "True" : "False"; ?></strong><br />
			More server info <strong><?php echo $_SERVER['SERVER_SOFTWARE']; ?></strong>
            <h6>About</h6>
            Once upon a time Zingiri Web Shop started off from FreeWebshop.org version 2.2.9_R2. FreeWebshop.org is a free shopping cart script. 
            It's designed to simplify e-commerce for everyone. The project is an initiative by Elmar Wenners of chaozz@work software.
            The script is written in PHP and uses a MySQL database. The script is released under the GNU General Public License as 
            published by the Free Software Foundation.<br />
            You can find out more about the original project here: <a href="http://www.freewebshop.org"><img src="<?php echo ZING_URL;?>/fws/templates/default/images/poweredby.png"/></a>.<br />
            <h6>Contributors</h6>
            Special thanks go to Marek Krajnak for providing the Slovak version, Luisa Fumi for providing the first Italian version and <a href="http://forums.zingiri.com/member.php?action=profile&uid=863">Giuseppe M. Corsano</a> for completely reviewing and completing it, <a href="http://forums.zingiri.com/member.php?action=profile&uid=1530">ginger</a> for providing the Norwegian Nynorsk language version and Timothy Hall for his testing support and excellent videos.
            <h6>3rd party addons</h6>
			<strong>Lightbox JS: Fullsize Image Overlays</strong><br />
			by Lokesh Dhakar - http://www.huddletogether.com<br />
			<br />
			<strong>email2: Sends e-mail messages without the need of an smtp server</strong><br />
            by Jason Jacques - http://poss.sourceforge.net/email/<br />
			<br />
			<strong>DOMPDF: Create PDF's with PHP</strong><br />
            by digitaljunkies.ca - http://www.digitaljunkies.ca/dompdf/<br />
			<br />
          </td>
      </tr>
     </table>
<?php } ?>