<?php
/**
 *
 * @package   TaMeR Framework Core
 * @copyright (C) 2009-2010 Dennis T Kaplan
 * @license   GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link      http://tamer.pzzazz.com
 *
 * @subpackage
 * @author       Dennis T Kaplan
 * @todo      
 */

class Core {

    /**
     * Our array of objects
     * @access private
     */
    private $objects = array();

    /**
     * Our array of settings
     * @access private
     */
    private $settings = array();

    /**
     * Our array of configs
     * @access private
     */
    private $key = array();

    /**
     * The frameworks human readable name
     * @access private
     */
    private $frameworkName = 'RoboTamer PHP Framework';

    public function storeObject( $object, $key ) {
        /**
         * Stores an object in the registry
         * @param String $object the name of the object
         * @param String $key the key for the array
         * @return void
         */
        $path = str_replace("_", "/", $object);
        require_once( ROOT . '/lib/' . $path . '.php');
        $this->objects[ $key ] = new $object( $this->instance );
    }
    public function getObject( $key ) {
        /**
         * Gets an object from the registry
         * @param String $key the array key
         * @return object
         */
        if( is_object ( $this->objects[ $key ] ) ) {
            return $this->objects[ $key ];
        }
    }
    public function cfgSet($group, $data) {
        /**
         * Stores settings in the registry
         * @param String $data
         * @param String $key the key for the array
         * @return void
         */
        $this->settings[ $group ] = $data;
    }
    public function cfgGet($group) {
        /**
         * Gets a setting from the registry
         * @param String $key the key in the array
         * @return void
         */
        if( ! isset($this->settings[ $group ] )) {
            $file = fileFind('etc', $group);
            require $file;
            self::cfgSet($group, $config);
        }//printa(debug_backtrace());exit;
        return $this->settings[ $group ];
    }
    public function get($group,$key) {
    	/**
    	 * Clone of cfgKey
    	 */ 
    	return $this->cfgKey($group,$key);
    }
    public function cfgKey($group,$key) {
        /**
         * Gets a setting by key from the registry
         * @param String $key the key in the array
         * @return void
         */
        if( ! isset($this->settings[ $group ] )) {
            $this->cfgGet($group);
        }
        return $this->settings[ $group ][ $key ];
    }
    public function getFrameworkName() {
        /**
         * Gets the frameworks name
         * @return String
         */
        return $this->frameworkName;
    }
    public function setGet() {
        /*
         * Get all Settings
        */
        return $this->settings;
    }
    public function setSet($array) {
        /*
         * Set all Settings
        */
        $this->settings = $array;
    }
    public function __construct() {
        /**
         * Trace constructor to prevent it being created directly
         * @access public
         */
        $trace = debug_backtrace();
        if($trace[1]['class'] != 'Singleton'){
            trigger_error("Class ".$trace[0]['class']
                    .' must be called from the Singleton Class', E_USER_ERROR );
        }
        require ETC.'settings.php';
        if(isset($config) && is_array($config)){
            $this->settings = $config;//e($config);
            unset($config);
        }
    }
    public function __clone() {
        /**
         * prevent cloning of the object: issues an E_USER_ERROR if this is attempted
         */
        logSet("Core Singleton Class Cloned!",'error');
        trigger_error( 'Cloning the registry is not permitted', E_USER_ERROR );
    }
    public function __destruct() {
        //logSet('$core->__destruct()','info');
    }
}     // End TaMeR FrameWork Core Class

?>
