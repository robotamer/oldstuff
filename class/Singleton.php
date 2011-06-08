<?php
/**
 *      Master Singleton
 *
 * One singilton to call any class as singilton
 *
 * @package      RoboTaMeR
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 **/
class_alias('Singleton', 'S');

class Singleton {

   //private static $debug = true;

   protected static $instances = array();

   protected function __construct(){}

   public static function factory($class = NULL) {

      if(!array_key_exists('Singleton', self::$instances))
         self::$instances['Singleton'] = new $class();

      if(!array_key_exists($class, self::$instances) && $class !== NULL)
         self::$instances[$class] = new $class();
      return self::$instances[$class];
   }
   /**
   * Use like this:  $tmpl = S::Template();
   *
   * @todo    Can't figure out how to pass the $arguments
   * @param   string $className
   * @param   mixed $arguments Class __construct arguments
   * @return  object
   **/
   public static function __callStatic($className, $arguments=null) {
      return self::factory($className);
   }
   public function getClasses(){
      $array=array();
      foreach(array_keys(self::$instances, TRUE) as $class)
         $array[]=$class;
      return $array;
   }
   public function __destruct(){
      foreach(array_keys(array_reverse(self::$instances, TRUE)) as $class) {
         if(defined('DEBUG') && DEBUG == 'S') echo 'shutdown: '. $class . BR;
         unset(self::$instances[ $class ]);
      }
   }
}
?>