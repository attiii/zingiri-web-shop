<?php
/*  install.php
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
    error_reporting(E_ALL);
    function InStr($String,$Find,$CaseSensitive = false) {
	        $i=0;
	        while (strlen($String)>=$i) {
		          unset($substring);
		          if ($CaseSensitive) {
			         $Find=strtolower($Find);
			         $String=strtolower($String);
		          }
		    	  $substring=substr($String,$i,strlen($Find));
		          if ($substring==$Find) return true;
		          $i++;
	        }
			return false;
    }
    function PrintError ($message) {
	    echo "<br /><br />";
	    echo "<table border=\"5\" bordercolor=\"red\" cellpadding=\"4\" cellspacing=\"0\"><tr><td><strong>Error:</strong>&nbsp;";
	    echo $message;
	    echo "<br /><br /><a href=\"install.php\">Try again</a>";
	    echo "</td></tr></table>";
	    exit;
    }
    function parse_mysql_dump($url, $ignoreerrors = false, $prefix) {
        $file_content = file($url);
        $query = ""; 
        foreach($file_content as $sql_line) {
          $tsl = trim($sql_line);
          if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
			$sql_line = str_replace("CREATE TABLE `", "CREATE TABLE `".$prefix, $sql_line);
			$sql_line = str_replace("INSERT INTO `", "INSERT INTO `".$prefix, $sql_line);
			$sql_line = str_replace("ALTER TABLE `", "ALTER TABLE `".$prefix, $sql_line);
			$sql_line = str_replace("UPDATE ", "UPDATE ".$prefix, $sql_line);
			$sql_line = str_replace("TRUNCATE TABLE `", "TRUNCATE TABLE `".$prefix, $sql_line);
            $query .= $sql_line;
            if(preg_match("/;\s*$/", $sql_line)) {
              $result = mysql_query($query);
              if (!$result && !$ignoreerrors) echo "<font color=\"red\"><b>".mysql_error()."</b></font><br />";
              $query = "";
            }
          }
        }
    }
    // header printer
    function PrintPageHeader ($header) {
	    echo "<h1>".$header."<hr size=\"5\" noshade color=\"#001894\" width=\"100%\"></h1>";
    }
	    

    // read post values
    $step = 1;
    if (!empty($_POST['step'])) { $step=$_POST['step']; }
    // database values
	if (!empty($_POST['dbtablesprefix'])) { $dbtablesprefix=$_POST['dbtablesprefix']; }
    else { $dbtablesprefix = ""; }	 
    if (!empty($_POST['dblocation'])) { $dblocation=$_POST['dblocation']; }
    else { $dblocation = ""; }	    
    if (!empty($_POST['dbname'])) { $dbname=$_POST['dbname']; }
    else { $dbname = ""; }	    
    if (!empty($_POST['dbuser'])) { $dbuser=$_POST['dbuser']; }
    else { $dbuser = ""; }	    
    if (!empty($_POST['dbpass'])) { $dbpass=$_POST['dbpass']; }
    else { $dbpass = ""; }	    
    
    // header
    ?>
    <html>
     <head>
	  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	  <META HTTP-EQUIV="Expires" CONTENT="-1">
      <title>FreeWebshop.org installation procedure</title>
     </head>
     <body bgcolor="white">
     <font face="verdana,arial">
    <?php   
    
    if ($step == 1) {
	    // display welcome
	    PrintPageHeader("Thank your for choosing FreeWebshop.org");
	    ?>
	    I will now guide you through the installation of your very own webshop
	    in just a few easy steps.<br />
	    <br />
	    <strong>NOTE:</strong> Please make sure you have read the documentation included in the "docs"
	    folder, concerning copyrights, editting of certain files and more
	    important installation information.<br />
	    <br />
	    Things you need to do before you press 'Next step':
	    <ul>
	     <li>Make <font color="red">a database</font> (write down it's data)</li>
	     <li>Add <font color="red">a user</font> to this database (write down it's data)</li>
	     <li>Make sure <strong>includes\settings.inc.php</strong> is <font color="red">chmodded to 777</font></li>
	     <li>If you are planning on using an SMTP server for sending emails, then make sure you know it's address, port, username and password</li>
	    </ul> 
	    Press <em>'Next step'</em> if you are ready to continue..<br />
	    <br />
	    <form method="post" action="install.php">
	          <input type="hidden" name="step" value="2">
	          <input type="submit" value="Next step">
	    </form>      
	    <?php
    }

    if ($step == 2) {
	    // show form in which database values must be filled in
	    PrintPageHeader("Step 1 - Database access");
	    ?>
	    The first thing we need is access to a database. If you haven't already
	    made one, make one NOW (or in some cases; let your hosting provider make
	    one for you)! You need to fill in the proper database values in the form below.<br />
	    <br />
	    <form method="post" action="install.php">
	          <strong>Database values</strong><br />
			  Database tables Prefix name:<br />
	          <input type="text" name="dbtablesprefix" value="fws_"><br />
	          Where is the database located (most common: localhost):<br />
	          <input type="text" name="dblocation" value="localhost"><br />
	          What is the name of the database:<br />
	          <input type="text" name="dbname" value=""><br />
	          What is username to connect to the database:<br />
	          <input type="text" name="dbuser" value=""><br />
	          What is password to connect to the database:<br />
	          <input type="password" name="dbpass" value=""><br />
	          <input type="hidden" name="step" value="3">
	          <input type="submit" value="Next step">
	    </form>      
	    <?php
    }    	    
 
    if ($step == 3) {
       PrintPageHeader("Step 2 - Saving settings");
	   // try to connect to database before writing them to the settings file
	   $db = mysql_connect($dblocation,$dbuser,$dbpass) or die(PrintError ("Could not connect to the database ".$dbname." with the data supplied. Please check the data!"));
 	   mysql_select_db($dbname,$db) or die(PrintError ("Could not connect to the database ".$dbname." with the data supplied. Please check the data!"));
	   echo "Succesfully connected to the database with the values you entered..<br />";
	        // save values to settings.php
	        $nothing = "";
	        $filename = "includes/settings.inc.php";
            @chmod($filename, 0777);
	        // now lets check if you are running the setup for the first time
	        $f = fopen($filename,"rb") or PrintError("Couldn find includes\settings.inc.php");
	        $settings = fread ($f, filesize($filename));
	        fclose ($f);
            if (instr($settings, "database values", false) == FALSE) {
	            $f = fopen($filename,"a") or PrintError("Couldn find includes\settings.inc.php");
		        if ($f) {
			        fwrite($f,"<?php\n");
			        fwrite($f,"    // database values\n");
					fwrite($f,"    $".$nothing."dbtablesprefix = \"".$dbtablesprefix."\";\n");
			        fwrite($f,"    $".$nothing."dblocation = \"".$dblocation."\";\n");
			        fwrite($f,"    $".$nothing."dbname = \"".$dbname."\";\n");
			        fwrite($f,"    $".$nothing."dbuser = \"".$dbuser."\";\n");
			        fwrite($f,"    $".$nothing."dbpass = \"".$dbpass."\";\n");
			        fwrite($f,"?>");
			        fclose($f);
			        echo "Values are saved!";
			        ?>
		            <br />
		            <form method="post" action="install.php">
						 <input type="hidden" name="dbtablesprefix" value="<?php echo $dbtablesprefix; ?>">
		                 <input type="hidden" name="dblocation" value="<?php echo $dblocation; ?>">
		                 <input type="hidden" name="dbname" value="<?php echo $dbname; ?>">
		                 <input type="hidden" name="dbuser" value="<?php echo $dbuser; ?>">
		                 <input type="hidden" name="dbpass" value="<?php echo $dbpass; ?>">
		                 <input type="hidden" name="step" value="4">
		                 <input type="submit" value="Next step">
		            </form>
		            <?php      
	            }
	            else {
			    // settings.php is not writable
			    PrintError ("includes/settings.inc.php is not writable. Change it's permissions to 777 and try again!");
		        }
	     }
		 else {   
		    // settings.php is already filled once
		    PrintError ("There is a problem! The values are already in the settings file. This can be caused by:<br /><br />1. You ran the setup twice (or more). If you want to re-run the setup, then reupload the orginal includes\settings.inc.php file<br />2. You did an upgrade and don't need to run install.php");
	    }
    }
    
    if ($step == 4 ) {
	   PrintPageHeader("Step 4 - Make the database structure"); 
	   // connect to database
	   $db = mysql_connect($dblocation,$dbuser,$dbpass) or die(PrintError ("Could not connect to the database ".$dbname." with the data supplied. Please check the data!"));
 	   mysql_select_db($dbname,$db) or die(PrintError ("Could not connect to the database ".$dbname." with the data supplied. Please check the data!"));
 	   
 	   echo "Filling database <strong>".$dbname."</strong> with the correct tables and values..<br /><br />"; 
       // fill database with correct structure	    
       parse_mysql_dump('FreeWebshop.sql', FALSE, $dbtablesprefix);
      
       echo "<br /><strong>DONE!</strong><br /><br />";
       echo "You are now ready to start using your brand new webshop. Remember to <strong>delete install.php</strong>!<br /><br />";
       echo "You can now <a href=\"index.php?page=my\">login to your shop</a> using the following data:<br />";
       echo "<strong>username:</strong> admin<br /><strong>password:</strong> admin_1234<br /><br />";
       echo "<font color=\"red\">DON'T FORGET TO CHANGE <strong>THE ADMIN PASSWORD AND EMAIL ADDRESS</strong>!!</font><br /><br />";
       echo "Thanks again for choosing FreeWebshop.org. For support, visit <a href=\"http://www.freewebshop.org\">the FreeWebshop.org homepage</a>.<br />";
       echo "Good luck,<br /> Elmar Wenners / FreeWebshop.org";
    }

   // footer
?>
   </font>
   </body>
  </html> 
