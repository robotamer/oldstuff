<?php
/**
 * An open source application development framework for PHP
 *
 * @category     RoboTaMeR
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license      http://RoboTamer.com/license.html
 * @link         http://RoboTamer.com
 */


/*echo '<pre>';
$array = array(1,2,3,4,5=>55,'rrrrrrrr'=>1,2,3,4,5=>55,777777=>1,2,3,4,5=>55,'rrrrrrrr');
print_r($array);
echo '<br>';
$ss = new Strsafe();
$array = $ss->run($array);
print_r($array);
echo '<br>';

$array = $ss->run($array);
print_r($array);
*/

/**
 * encode, decode and also serialize when nessesery.
 * Works with anything that php can serialize.
 * string, array, etc.
 *
 * @category     RoboTaMeR
 * @package      Strsafe
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license      http://RoboTamer.com/license.html
 * @link         http://RoboTamer.com
 */
class StrSafe {

	const startstr  = '|~';
	const endstr    = '~>';
    const strsafe   = '|~StrSafe~>';
    const serialize = '|~unserialize~>';
    const base64    = '|~unbase64~>';
    const bz2       = '|~unbz2~>';

    const doBase64  = FALSE;
    const dobz2     = FALSE;

    private $_data;
    private $_ckSum;

    public function __construct() {}

    public function run($data) {
        $this->clear();
        if($this->check($data) === TRUE) {
            $this->decode($data);
        }else{
            $this->encode($data);
        }
        return $this->_data;
    }

    private function encode($data) {
        $this->setData($data);
        $this->status[self::strsafe] = self::strsafe;
        $this->serialize();
        $this->base64();
        foreach ($this->status as $value) {
            $this->_data = $this->_data.$value;
        }
    }

    private function decode($data){
        $job = NULL;
        $this->setData($data);
        $this->getJobs();
        $this->removeConst();
        foreach($this->jobs as $job) $this->$job();
    }
    private function getJobs(){
        $this->jobs = strstr($this->_data, self::strsafe);
        $this->jobs = explode(self::startstr, $this->jobs);
        unset($this->jobs[0],$this->jobs[1]);
        foreach ($this->jobs as $v) $this->jobs = str_replace(self::endstr, '', $this->jobs);
        $this->jobs = array_reverse($this->jobs); // Start in the right order
    }
    private function removeConst(){
        $this->_data = $this->rstrstr($this->_data, self::strsafe);
    }
    private function serialize(){
        if( ! is_string($this->_data)){
            $this->status[] = self::serialize;
            $this->_data = serialize($this->_data);
        }
    }
    private function unserialize(){
        $this->_data = unserialize($this->_data);
    }
    private function isSerialized() {
        if (!is_string($this->_data)) return FALSE;
        if (trim($this->_data) == '') return FALSE;
        if (preg_match("/^(i|s|a|o|d):(.*);/si",$this->_data) !== FALSE) return TRUE;
        return FALSE;
    } // Check if string is serialzied outside of strsafe

    private function base64(){
        $this->status[self::base64] = self::base64;
        $this->_data = base64_encode($this->_data);
    } // Make the variable ASCII save

    private function unbase64(){
        $this->_data = base64_decode($this->_data);
    }

    private function bz2(){
    	$this->status[self::bz2] = self::bz2;
        return bzcompress($this->_data, 9);
    } // @todo compress the variable

    private function unbz2(){
        return bzdecompress($this->_data);
    } // @todo compress the variable
    private function setData($data = NULL){
        if($this->_data === NULL && $data !== NULL){
            $this->_data = $data;
        }
    } // Set the variable to be prossesed

    public function getData() {
        return $this->_data;
    }

    public function check($data){
        if( is_string($data) && strpos( $data, self::strsafe) > 1 ){
            return TRUE;
        }
        return FALSE;
    } // Check if provided data is a strsafe string

    public function clear(){
        $this->_data = NULL;
    } // clear reset the class data
    public function setCkSum($string = 'TaMeR'){
        $this->_ckSum = $string;
    }// @todo
    public function rstrstr($haystack,$needle) {
        /**
         * reverse strstr()
         *
         * Returns part of haystack string from start to the first occurrence of needle
         * $haystack = 'this/that/theother';
         * $result = rstrstr($haystack, '/')
         * $result == this

         * @access  public
         * @param   string $haystack, string $needle
         * @return  string
         */
        return substr($haystack,0,strpos($haystack,$needle));
    } // reverse strstr()
}
?>