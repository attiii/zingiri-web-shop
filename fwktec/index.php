<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$aphps_projects['fwktec']['label']='Technical framework';
$aphps_projects['fwktec']['dir']=dirname(__FILE__).'/';
$aphps_projects['fwktec']['url']=BASE_URL.'fwktec/';
$aphps_projects['fwktec']['level']='admin';

require(dirname(__FILE__).'/functions-core/index.php');
