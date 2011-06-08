<?php
function getRand(){
    $fd = fopen("/dev/urandom","r");
    $data = fread($fd,16);
    fclose($fd);
    return md5($data);
}
?>
