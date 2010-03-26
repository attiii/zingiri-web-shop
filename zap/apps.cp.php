<?php
/*  controlpanel.php
 Copyright 2008,2009 Erik Bogaerts
 Support site: http://www.zingiri.com

 This file is part of Zingiri Apps.

 Zingiri Apps is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Zingiri Apps is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FreeWebshop.org; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
$zing_apps_player_name = "Zingiri Apps Player";
$zing_apps_player_shortname = "zing_apps_player";
$zing_apps_player_categories_obj = get_categories('hide_empty=0');
$zing_apps_player_categories = array();
$zing_apps_player_pages=array();

$a = $wpdb->get_results( "SELECT id, post_title FROM ".$wpdb->prefix."posts WHERE post_type='page' and post_status='publish' ORDER BY post_title" );
foreach ($a as $o) {
	$zing_apps_player_pages[$o->id]=$o->post_title;
}

foreach ($zing_apps_player_categories_obj as $zing_apps_player_cat) {
	$zing_apps_player_categories[$zing_apps_player_cat->cat_ID] = $zing_apps_player_cat->cat_name;
}
$categories_tmp = array_unshift($zing_apps_player_categories, "Select a category:");	
$number_entries = array("Select a Number:","1","2","3","4","5","6","7","8","9","10", "12","14", "16", "18", "20" );
$banner_entries = array("Select a Type:","Glide","SlidingDoors" );
$banner_select = array("Yes","No" );
$banner_set = array("Category","Pages","Posts");
$zing_apps_player_options = array (

    array(  "name" => "Settings",
            "type" => "heading",
			"desc" => "This section customizes the ".$zing_apps_player_name." plugin.",
       ),
     	array(	"name" => "Forms page",
			"desc" => "Default page used to display lists & forms.",
			"id" => $zing_apps_player_shortname."_page",
			"std" => "",
			"type" => "select",
     		"options" => $zing_apps_player_pages),
);

function zing_apps_player_add_admin() {

    global $zing_apps_player_name, $zing_apps_player_shortname, $zing_apps_player_options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {
        	
        		zing_apps_player_load();
                foreach ($zing_apps_player_options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($zing_apps_player_options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: options-general.php?page=apps.cp.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($zing_apps_player_options as $value) {
                delete_option( $value['id'] ); 
                update_option( $value['id'], $value['std'] );}

            header("Location: options-general.php?page=apps.cp.php&reset=true");
            die;

        }
    }


      add_options_page($zing_apps_player_name." Options", "$zing_apps_player_name", 8, basename(__FILE__), 'zing_apps_player_admin');
}

function zing_apps_player_load() {
	global $wpdb;
	
	error_reporting(E_ALL & ~E_NOTICE);
	ini_set('display_errors', '1');
	
	require_once(dirname(__FILE__).'/includes/create.php');
	require_once(dirname(__FILE__).'/includes/faces.inc.php');
	require_once(dirname(__FILE__).'/includes/db.inc.php');
	require_once(dirname(__FILE__).'/classes/db.class.php');
	
	$wpdb->show_errors();
	$prefix=$wpdb->prefix."zing_";
	
	$dir=ZING_APPS_CUSTOM.'faces/';
	if ($handle = opendir($dir)) {
		$files=array();
		while (false !== ($file = readdir($handle))) {
			if (strstr($file,".json")) {
				$file_content = file_get_contents($dir.$file);
				$a=json_decode($file_content,true);
//				echo '<br />'.$file.'=';
//				print_r($a);
				zfCreate($a['NAME'],$a['ELEMENTCOUNT'],$a['ENTITY'],$a['TYPE'],$a['DATA'],$a['LABEL']);
			}
		}
	}
}

function zing_apps_player_create($file_content) {
	global $wpdb;
	
				$query = "";
				foreach($file_content as $sql_line) {
					$tsl = trim($sql_line);
					if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
						if (str_replace("##", $prefix, $sql_line) == $sql_line) {
							$sql_line = str_replace("CREATE TABLE `", "CREATE TABLE `".$prefix, $sql_line);
							$sql_line = str_replace("CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS`".$prefix, $sql_line);
							$sql_line = str_replace("INSERT INTO `", "INSERT INTO `".$prefix, $sql_line);
							$sql_line = str_replace("ALTER TABLE `", "ALTER TABLE `".$prefix, $sql_line);
							$sql_line = str_replace("UPDATE `", "UPDATE `".$prefix, $sql_line);
							$sql_line = str_replace("TRUNCATE TABLE `", "TRUNCATE TABLE `".$prefix, $sql_line);
						} else {
							$sql_line = str_replace("##", $prefix, $sql_line);
						}
						$query .= $sql_line;
						if(preg_match("/;\s*$/", $sql_line)) {
							$wpdb->query($query);
							$query = "";
						}
					}
				}
	
}

function zing_apps_player_admin() {

    global $zing_apps_player_name, $zing_apps_player_shortname, $zing_apps_player_options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_apps_player_name.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_apps_player_name.' settings reset.</strong></p></div>';
    
    
?>
<div class="wrap">
<h2><b><?php echo $zing_apps_player_name; ?></b></h2>

<form method="post">

<table class="optiontable">

<?php foreach ($zing_apps_player_options as $value) { 
    
	
if ($value['type'] == "text") { ?>
        
<tr align="left"> 
    <th scope="row"><?php echo $value['name']; ?>:</th>
    <td>
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" size="40" />
				
    </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>

<?php } elseif ($value['type'] == "textarea") { ?>
<tr align="left"> 
    <th scope="row"><?php echo $value['name']; ?>:</th>
    <td>
                   <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="50" rows="8"/>
				   <?php if ( get_settings( $value['id'] ) != "") { echo stripslashes (get_settings( $value['id'] )); } 
				   else { echo $value['std']; 
				   } ?>
</textarea>

				
    </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>


<?php } elseif ($value['type'] == "select") { ?>

    <tr align="left"> 
        <th scope="top"><?php echo $value['name']; ?>:</th>
	        <td>
            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                <option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
                <?php } ?>
            </select>
			
        </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>



<?php } elseif ($value['type'] == "heading") { ?>

   <tr valign="top"> 
		    <td colspan="2" style="text-align: left;"><h2 style="color:green;"><?php echo $value['name']; ?></h2></td>
		</tr>
<tr><td colspan=2> <small> <p style="color:red; margin:0 0;" > <?php echo $value['desc']; ?> </P> </small> <hr /></td></tr>

<?php } ?>
<?php 
}
?>
</table>
<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<p> For more information and support, contact us at <a href="http://www.zingiri.com" >Zingiri</a> or check out the <a href="http://forums.zingiri.com" >forums</a></p>
<?php
}
add_action('admin_menu', 'zing_apps_player_add_admin'); ?>
