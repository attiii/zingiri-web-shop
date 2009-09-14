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
<?php include ("includes/httpclass.inc.php"); ?>
<?php 
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {    
?>


     <table width="100%" class="datatable">
       <caption>About FreeWebshop.org</caption>
      <tr><td> 
            <h6>Version info</h6>
            <br />
            FreeWebshop.org version <strong><?php echo $version ?></strong><br />
			PHP version <strong><?php echo phpversion(); ?></strong><br />
			MySQL version <strong><?php echo mysql_get_server_info(); ?></strong><br />
			GD-enabled <strong><?php echo (extension_loaded('gd')) ? "True" : "False"; ?></strong><br />
			<br />
			<strong>More server info</strong><br /><em><?php echo $_SERVER['SERVER_SOFTWARE']; ?></em><br />
            <br />
            For changes in this version <a href="docs/changelog.txt">read the changelog</a><br />
            Inform yourself about the copyright and <a href="docs/copyright.txt">read the copyright text</a><br />
            <br />
            <h6>About</h6>
            <br />
            Thank you for using FreeWebshop.org. FreeWebshop.org is a free shopping cart script. 
            It's designed to simplify e-commerce for everyone. The project is an initiative by Elmar Wenners of chaozz@work software.
            The script is written in PHP and uses a MySQL database. The script is released under the GNU General Public License as 
            published by the Free Software Foundation.<br />
            <br />
            The project could use your contribution. <a href="http://www.freewebshop.org">See what you can do for FreeWebshop.org</a><br />
            <br />
            <h6>Help FreeWebshop.org</h6>
			<br />
			Give this script a good rating on Hotscripts.com<br />
	        <form action="http://www.hotscripts.com/cgi-bin/rate.cgi" method="POST">
			<input type=hidden name="ID" value="59709">
			<b>Rate Our Script @ <a href="http://www.hotscripts.com">HotScripts.com</a></b>
			<select name="ex_rate" size="1"><option selected>Select</option><option value="5">Excellent!</option><option value="4">Very Good</option><option value="3">Good</option><option value="2">Fair</option><option value="1">Poor</option></select><input type="submit" value="Go!">
			</form>
            <h6>Support</h6>
            <br />
            Support site  : <a href="http://www.freewebshop.org">freewebshop.org</a><br />
            Support forum : <a href="http://www.chaozz.nl/forum">chaozz.nl/forum</a><br />
            <br />
            Because this product is free software there is limited support. Free support is only available
            via the forums. If you prefer personal support, please contact me: <a href="mailto:sales@freewebshop.org">sales@freewebshop.org</a>.<br />
            <br />
            For any other questions, read the documentation in the "/doc" folder of this installation.<br />
            <br />
            <br />
            Greetz,<br />
            Elmar Wenners (aka chaozz)<br />
			<br />
            <h6>3rd party addons</h6>
			<br />
			<strong>Lightbox JS: Fullsize Image Overlays</strong><br />
			by Lokesh Dhakar - http://www.huddletogether.com<br />
			<br />
			<strong>TinyMCE: WYSIWYG editor</strong><br />
			by Moxiecode Systems AB - http://tinymce.moxiecode.com/<br/ >
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