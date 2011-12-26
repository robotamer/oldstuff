<?php
/**
 * Master Singleton
 *
 * One singleton to call any class as singleton
 *
 * @package		RoboTaMeR
 * @author		Dennis T Kaplan
 * @copyright	Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license		http://php.RoboTamer.com/license.html
 * @link		http://php.RoboTamer.com
 *
 * $t = Singleton::factory('Translate');
 * e(Singleton::getClasses());
 *
 **/
class_alias('Singleton', 'S');

class Singleton {

	protected static $instances = array();

	protected function __construct(){}

	public static function factory($class = NULL, $arg1 = NULL) {

		if(!array_key_exists('Singleton', self::$instances))
			self::$instances['Singleton'] = new $class();

		if(!array_key_exists($class, self::$instances) && $class !== NULL)
			self::$instances[$class] = new $class($arg1);
		return self::$instances[$class];
	}

	/**
	* Use like this:	$tmpl = S::Template();
	*
	* @todo	Can't figure out how to pass the $arguments
	* @param	string $className
	* @param	mixed $arguments Class __construct arguments
	* @return	object
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

	public function __clone() {
		//prevent cloning of the object:
		// issues an E_USER_ERROR if this is attempted

		logSet("Singleton Class Cloned!",'error');
		trigger_error( 'Cloning the Singleton Class is not permitted', E_USER_ERROR );
	}

	public function __destruct(){
		foreach(array_keys(array_reverse(self::$instances, TRUE)) as $class) {
			if(defined('DEBUG') && DEBUG == 'S') echo 'shutdown: '. $class . BR;
			unset(self::$instances[ $class ]);
		}
	}
}
?>