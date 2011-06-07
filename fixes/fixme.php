<?php
// Restore regressed admin rights from subscriber to administrator
/*
$resetUser='admin';
global $wpdb;
$wpdb->show_errors();
$query="update ##usermeta set meta_value='".'a:1:{s:13:"administrator";b:1;}'."' where user_id=(select ID from ##users where user_login='".$resetUser."') and meta_key='wp_capabilities'";
$query=str_replace('##',$wpdb->prefix,$query);
echo $query.'-- REMOVE THIS CODE NOW';
$wpdb->query($query);
*/
?>