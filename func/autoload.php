<?php
defined('DS') || define('DS',DIRECTORY_SEPARATOR);
defined('CROOT') || set_include_path('.'.PATH_SEPARATOR.CROOT.ltrim(get_include_path(),'.'));

function autoload($class)
{
   if(preg_match('/[^a-z0-9\\/\\\\_.:-]/i', $class)){
     throw new Exception('Security check: Illegal character in filename');
   }
   if(class_exists($class, false) || interface_exists($class, false))
      return;

   if(defined('ROOT') && file_exists(ROOT.$class.'.class.php')){
      include ROOT.$class.'.class.php';
   }elseif(strpos($class, '_') > 0){
   // Load Zend files
      $array = explode('_', $class);
      $path = str_replace("_",DS, $class);
      try {
         include $path . '.php';
      } catch (Exception $e) {
            throw new Exception("Unable to load $class at $path. <br /> $e");
      }
    }else{
      $path = CROOT.'TaMeR'.DS.$class.'.php';
      if(!is_readable($path)) $path = CROOT .$class.'.php';
      //echo '<pre>'; print_r($file);echo $path;exit;
      if($path){
         try {
            include $path;
         } catch (Exception $e) {
            throw new Exception("Unable to load $class at $path. <br /> $e");
         }
      }
   }
}
# Silently start the autoloader
spl_autoload_register('autoload');
?>