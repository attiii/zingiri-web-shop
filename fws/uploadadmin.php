<?php
/*  groupadmin.php
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
    // function to read mysql dump
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
              if (!$result && !$ignoreerrors) die(mysql_error());
              $query = "";
            }
          }
        }
    }
 // admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
      // upload the SQL file
      if ($action == "upload_pricelist") {
         $target_path = $brands_dir."/";
         $target_path = $target_path."pricelist.sql"; 
         if (strtoupper(substr($_FILES['uploadedfile']['name'], -3)) == "SQL") {         
             // delete previous pricelist if it exists
             if (file_exists($target_path)) {
                 unlink($target_path); 
             }

             if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
                // now read the temp file and put it's values into the database
                parse_mysql_dump($target_path, TRUE, $dbtablesprefix);
                unlink($target_path);
                PutWindow($gfx_dir, $txt['general13'], $txt['uploadadmin7'], "notify.gif", "50");                
             } 
             else{
                echo $txt['uploadadmin2'];
             }   
         }
         else { echo $txt['uploadadmin3']; }
      }
?>             
     <table width="80%" class="datatable">
      <caption><?php echo $txt['uploadadmin4']; ?></caption>
       <tr><td>         
	     <form enctype="multipart/form-data" action="index.php?page=uploadadmin" method="POST">
           <input type="hidden" name="action" value="upload_pricelist">
	       <input type="hidden" name="MAX_FILE_SIZE" value="500000">
	       <input name="uploadedfile" type="file" size="50"><br />
	       <br />
	       <div style="text-align:center;"><input type="submit" value="<?php echo $txt['uploadadmin6']; ?>"></div>
	     </form>
	   </td></tr>
	 </table>
<?php } ?>