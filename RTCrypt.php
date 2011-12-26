<?php
/**
 * An open source PHP library collection
 *
 * @category     RoboTamer
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license      http://www.RoboTamer.com/license.html
 * @link         http://www.RoboTamer.com
 **/

/**
 * RTCrypt
 *
 * RTCrypt allows for encryption and decryption on the fly using
 * a simple but effective method.
 *
 * RTCrypt does not require mcrypt, mhash or any other PHP extension,
 * it uses only PHP.
 *
 * @category     RoboTamer
 * @package      RTCrypt
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2011, Dennis T Kaplan
 * @license      http://www.RoboTamer.com/license.html
 * @link         http://www.RoboTamer.com
 **/
class RTCrypt
{
    const streight = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    private $scramble1 = NULL;
    private $scramble2 = NULL;

    public function __construct()
    {
        $this->setScramble1();
    }

    public function __destruct(){}

    public function getStreight()
    {
        return self::streight;
    }

    public function getScramble1()
    {
        return implode($this->scramble1);
    }

    public function getScramble2()
    {
        return implode($this->scramble2);
    }

    /**
     * Set the characters you like to replace
     *
     * @access  private
     * @param   string $str
     */
    private function setScramble1()
    {
        $this->scramble1 = str_split(self::streight);
    }

    /**
     * This is your private key.
     * You can generate a random private key based on scramble1 via
     * the randomizeString($scramble1) function.
     *
     * @access  public
     * @param   string $str
     * @return  bool TRUE
     */
    public function setScramble2($str=NULL)
    {
        if($str===NULL){
            trigger_error('No key, use genKey($str)', E_USER_ERROR );
            die;
        }
        $this->scramble2 = str_split($str);
        return TRUE;
    }

    /**
     * This will encrypt your data
     *
     * @access  public
     * @param   string $str
     * @return  string encrypt data
     */
    public function encrypt($str)
    {
        if($this->scramble2 === NULL) $this->setScramble2();
        $str = base64_encode($str);
        $len = strlen($str);
        $newstr='';
        for($i=0; $i < $len;$i++){
            $r = substr($str, -1);
            $str = substr($str, 0, -1);
            $an = array_search($r,$this->scramble1);
            if($an !== FALSE){
                $newstr .= $this->scramble2[$an];
            }else{
                $newstr .= $r;
            }
        }
        return $newstr;
    }

    /**
     * This will decrypt a Crypted string back to the original data
     *
     * @access  public
     * @param   string $str
     * @return  string
     */
    public function decrypt($str)
    {
        if($this->scramble2 === NULL) $this->setScramble2();
        $len = strlen($str);
        $newstr='';
        for($i=0; $i < $len;$i++){
            $r = substr($str, -1);
            $str = substr($str, 0, -1);
            $an = array_search($r,$this->scramble2);
            if($an !== FALSE){
                $newstr .= $this->scramble1[$an];
            }else{
                $newstr .= $r;
            }
        }
        $str = base64_decode($newstr);
        return $str;
    }

    /**
     * Generates your private key.
     * You would use it to set scramble2
     * Keep it save!
     *
     * @access  public
     * @return  string
     */
    public function genKey()
    {
        $array = str_split(self::streight);
        shuffle($array);
        return implode($array);
    }
}
?>
