<?php
if ( !function_exists('stripComments')) {
    loadFFunc('stripComments');
}
function loadFFunc($str){
    if (!defined('LIBS')) {
        trigger_error("LIBS constant not defined", E_USER_WARNING);
    }
    $str = preg_replace('/\s\s+/', ' ', $str);
    $array = explode(' ',$str);
    foreach($array as $v){
        if ( !function_exists($v)) {
            include(LIBS.'/TaMeR/Functions/'.$v.'.php');
        }
    }
}

