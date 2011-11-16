<?php // Uri

/**
 * An open source application development framework for PHP 5.2 or newer
 *
 * @package      TaMeR
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 */


/**
 * Name: Uri
 * Description
 *
 * @author     Dennis T Kaplan
 */

/*
 * https://www.example.com/appName/page.php/segment1/segment2/
 *
 * Uri::appName();     == appName 
 * Uri::baseUrl();     == https://www.example.com/
 * Uri::selfUrlUrl();  == https://www.example.com/appName/page.php
 * Uri::segment(1);    == segment1
 *
 *
 */    
	
class Uri {	
	
    public static function protocol(){
        return (getenv('HTTPS') == 'on') ? 'https' : 'http';
    }
    public static function domain(){
        $host = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
        return $host[1].'.'.$host[0];
    }
    public static function subDomain(){
        $host = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
        return isset($host[2]) ? $host[2] : 'www';
    }
    public static function baseUrl(){
        return self::protocol().'://'.self::subDomain().'.'.self::domain().'/';
    }
    
    public static function fullUrl(){
        return self::baseUrl() . trim(self::selfUrl(), '/');
    }
    
    public static function selfUrl(){
        // /admin/debug.php and nothing after just the file
        $selfurl = getenv('SCRIPT_NAME');
        return $selfurl; //strstr($selfurl, '.php', TRUE);
    }
    public static function appFolder(){
        /*
         * Application Folder
         * Removes file name and returns just the URL folder
         */
        $haystack = getenv('SCRIPT_NAME');
        return substr($haystack,0,strrpos($haystack,'/'));
    }
    public static function mvcPath(){
        return ROOT;
    }
    public static function appPath(){
        /*
         * Full Application Path from server root
         */
        return self::mvcPath() . self::appFolder();
    }
    public static function appName(){
        /*
         * Application Name
         */
        $haystack = getenv('SCRIPT_NAME');
        $haystack = trim($haystack, '/');
        return substr($haystack, 0,strpos($haystack, '/'));
        //return strstr(trim($haystack, '/'),'/', true); php 5.3
    }
    public static function classFile(){
        /*
         * YRoute class File / php file name as in index.php
         */
        $haystack = getenv('SCRIPT_NAME');
        $array = array_reverse(explode('/',$haystack));
        return $array[0];
    }
    public static function className(){
        /*
         * YRoute class Name / php file name without the extention .php
         */
        $haystack = self::classFile();
        return substr($haystack, 0,strpos($haystack, '.'));
        // return strstr($file, '.', true); // php 5.3
    }
    
    public static function actionName(){
        $segments = self::segments();
    	$secOne = isset($segments[0]) ? $segments[0] : '' ;
    	$language = FALSE;
    	
    	if(strlen($secOne) == 2){
    		include_once 'Zend/Locale.php';
    		$list = Zend_Locale::getTranslationList('language', 'en');	
    		$language = ($secOne && array_key_exists($secOne, $list)) ? $secOne : FALSE;
    		$l = ($language !== FALSE) ? $language : 'en';
    		defined('LANGUAGE') || define('LANGUAGE', $l);
        }else{
        	defined('LANGUAGE') || define('LANGUAGE', 'en');
        }

        if($language !== FALSE) array_shift($segments);
        
        if(isset($segments[0])) {
            $action = $segments[0];
        }else{
            $action = 'index';
        }
        return $action;
    }  
    
    public static function language(){
    	defined('LANGUAGE') || self::actionName();
        return LANGUAGE;
    }
    
    public static function segmentCount(){
        return count(self::segments());
    }
    
    public static function segments(){
    	$segmentArray = $requestPathArray = array();

    	if(isset($_SERVER['PATH_INFO'])) $requestPathArray = explode('/', trim($_SERVER['PATH_INFO'],'/'));

        foreach($requestPathArray as $value) {
            if($value != ''){
                $segmentArray[] = self::alphaValidate($value);
            }
        }
        //array_unshift($segmentArray,'');
        //unset($segmentArray[0]);
        return $segmentArray;
    }
    public static function segment($no) {
        $array = self::segments();
        return isset($array[$no]) ? $array[$no] : FALSE;
    }
    /**
     * @todo Incorporate language support
     * @todo Create & move to Validation Class
     *
     * @param string $string
     * @return string error
     */
    public static function alphaValidate($string) {
        /**
         * preg_match = alpha or no, underscore only as concatenation character
         * @todo Incorporate language support
         * @todo Create & move to Validation Class
         */
        //if(preg_match('/^[A-Za-z0-9](?:[._][A-Za-z0-9]+)$/' , $string)|| $string == '') {
        //if(preg_match("^[A-Za-z0-9_]*$", $string)|| $string == '') {
        if(ctype_alnum($string) || $string == '') {
            return $string;
        } else {
            trigger_error('String must be Alpha Numeric: '.$string.'<br />'.printa($_SERVER), E_USER_ERROR);
        }
    }
    // final private function __construct() {} // php 5.3
    function __construct() {}
}
?>
