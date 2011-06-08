<?php
function getVars(){
   $result=array();
   $skip=array('GLOBALS','_REQUEST',
               '_ENV','HTTP_ENV_VARS',
               '_POST','HTTP_POST_VARS',
               '_GET','HTTP_GET_VARS',
               '_COOKIE','HTTP_COOKIE_VARS',
               '_SERVER','HTTP_SERVER_VARS',
               '_FILES','HTTP_POST_FILES',
               '_SESSION','HTTP_SESSION_VARS');
   foreach($GLOBALS as $k=>$v)
      if(!in_array($k,$skip))
         $result[$k]=$v;
   return $result;
}
?>
