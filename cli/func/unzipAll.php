<?php
echo "\t Edit first!!! \n"; exit;
$dir = '/tmp/font/';
$zips = scandir($dir);
foreach($zips as $file) {

    shell_exec('unzip '. $dir.$file);
}


?>