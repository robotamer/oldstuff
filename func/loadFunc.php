<?php
if ( !function_exists('stripComments')) {
    loadFunc('stripComments');
}
function loadFunc($str){
    if (!defined('FROOT')) {
        trigger_error("FROOT constant not defined", E_USER_WARNING);
    }
    $str = preg_replace('/\s\s+/', ' ', $str);
    $array = explode(' ',$str);
    foreach($array as $v){
        if ( !function_exists($v)) {
            include(FROOT.$v.'.php');
        }
    }
}

