<?php
function randString($length=6,$char="acdefghjkmnopqrstuvwxyz0123456789") {
    $string = "";
    for($i = 1; $i <= $length; $i++)
        $string .= $char[rand(0,strlen($char)-1)];
    return $string;
}
?>
