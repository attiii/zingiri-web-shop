<?php
echo '<h2>'.z_('Export').'</h2>';

$formName=$_REQUEST['form'];

if (class_exists('zf'.$formName)) $zfClass='zf'.$formName;
else $zfClass='zfForm';

$form=new $zfClass($formName);


$form->export(); //data is stored in aphpsData
