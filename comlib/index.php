<?php if ($index_refer <> 1) { exit(); } ?>
<?php
$aphps_projects['comlib']['label']='Common Library';
$aphps_projects['comlib']['dir']=dirname(__FILE__).'/';
$aphps_projects['comlib']['url']=BASE_URL.'comlib/';
$aphps_projects['comlib']['level']='admin';
$aphps_projects['comlib']['system']=1;
$aphps_projects['comlib']['js'][]='jQuery.bubbletip-1.0.6.js'; //jquery jsPlumb adapter
$aphps_projects['comlib']['js'][]='help.js';
$aphps_projects['comlib']['css'][]='bubbletip/bubbletip.css';
