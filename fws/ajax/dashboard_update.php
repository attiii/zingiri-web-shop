<?php
require(dirname(__FILE__).'/init.inc.php');

if (ZING_CMS=='wp') require(dirname(__FILE__).'/../../'.ZING_APPS_EMBED.'/includes/faces.inc.php');

if (!IsAdmin()) die('No access');

parse_str($_POST['data']);
//$zdashboard=array("abc","cde");
print_r($zdashboard);
$zdashboard=array_unique($zdashboard);
$json=zf_json_encode($zdashboard);
echo $json;
$db=new db();
$sql="update ##settings set dashboard='".$json."'";
$db->update($sql);
//print_r($zdashboard);

?>