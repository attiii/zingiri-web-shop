<?php
/*  setlang.php
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
<?php
	$time = time();
	$lang = $_GET['lang'];
	
	//added to improve redirect after language select
	//parse the redirect page from the lang link
	if (!empty($_GET['redirect_to'])) {
	    $redirect_to = "index.php?".$_GET['redirect_to'];
		$redirect_to = str_replace("**", "=", $redirect_to);
		$redirect_to = str_replace("$$", "&", $redirect_to);
	}else {
		$redirect_to = "index.php";
	}
	if (!$lang == NULL) {
                         if (setcookie ("cookie_lang",$lang, $time+30240000, '/')==TRUE)
                            {
 		                    ?>
                             <html><head><link href="freewebshop.css" rel="stylesheet" type="text/css">
                             <META HTTP-EQUIV=Refresh CONTENT="0; URL=<?php echo $redirect_to;?>"></head>
                             <body><p></body></html>
				            <?php
                         }
	                     else
	                         {
                             ?>
                              <html><head><link href="freewebshop.css" rel="stylesheet" type="text/css">
                              <META HTTP-EQUIV=Refresh CONTENT="10; URL=index.php"></head>
                              <body><br /><br /><br /><br /><br /><br /><br />
                              <div align=center>Cookie Error!</a></div></body></html>
	                         <?php
	                     }
   }
   else {
	   $lang = $default_lang;
   }
   exit;
?>