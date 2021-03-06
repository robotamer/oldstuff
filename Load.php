<?php
/**
 * An open source application development framework for PHP
 *
 * @category    RoboTaMeR
 * @author      Dennis T Kaplan
 * @copyright   Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license     http://RoboTamer.com/license.html
 * @link        http://RoboTamer.com
 */

 /**
 *
 * This class loads stuff
 *
 * @category   RoboTaMeR
 * @package    Load
 * @author     Dennis T Kaplan
 * @copyright  Copyright (c) 2009 - 2011, Dennis T Kaplan
 * @license    http://RoboTamer.com/license
 * @link       http://RoboTamer.com
 */
class Load
{
    protected $type;
    protected $path;

    public function __construct(){}
    public function __destruct(){}

	public static function getArray($id,$key = NULL, $file = 'config')
	{
		$data = self::a($file);
		if($key === NULL){
			return $data[$id];
		}elseif(isset($data[$id][$key])){
			return $data[$id][$key];
		}
		return FALSE;
	}

    public static function a($array_name) {
        if( ! is_string($array_name)){
            trigger_error("Array name must be a string!", E_USER_WARNING);
            exit;
        }
        if(isset($GLOBALS[$array_name]) && is_array($GLOBALS[$array_name]))
            return $GLOBALS[$array_name];

        $dfile = ETC.$array_name.'.php';
        $mfile = FALSE;
        if( defined('MROOT') && MROOT != FALSE)
            $mfile = MROOT.'array/'.$array_name.'.php';
        return self::inc($dfile,$mfile,$array_name);
    }

    public static function aSave($var)
    {
        $array_name = array_search($var, $GLOBALS);

        $dfile = ETC.$array_name.'.php';

        $string = var_export($var,true);
        file_put_contents($dfile,
            '<?php'.PHP_EOL.'return '.$string.';'.PHP_EOL.'?>',LOCK_EX);

        $mfile = FALSE;
        if( defined('MROOT') && MROOT !== FALSE){
            $mfile = MROOT.'array/'.$array_name.'.php';
            file_put_contents($mfile, self::stripComments($dfile));
        }

    }//end save()


	/**
		Lock-aware file reader
			@param $file string
			@public
	**/
	static function getFile($file) {
		$out=FALSE;
		if (!function_exists('flock'))
			return file_get_contents($file);
		elseif (is_file($file) && $handle=@fopen($file,'r')) {
			flock($handle,LOCK_SH);
			$size=filesize($file);
			if ($size)
				$out=fread($handle,$size);
			flock($handle,LOCK_UN);
			fclose($handle);
		}
		return $out;
	}

	/**
		Lock-aware file writer
			@param $file string
			@param $data string
			@public
	**/
	static function putFile($file,$data) {
		return file_put_contents($file,$data,LOCK_EX);
	}

    public static function f($str) {
        if( ! is_string($str))
            trigger_error("Argument for load must be a string!", E_USER_WARNING);

        $array = self::prepArgs($str);
        $mfile = FALSE;
        foreach($array as $func_name){
            if ( function_exists($func_name)) continue;
            $dfile = FUN.$func_name.'.php';
            if( defined('MROOT') && MROOT != FALSE)
                $mfile = MROOT.'func/'.$func_name.'.php';
            self::inc($dfile,$mfile);
        }
    }

    /**
     * Description load function
     * if availabe load from memory folder; else
     *  if a memory folder is availabe, copy to memory then load; else
     *   load from file
     * 
     * @todo replace include with getFile() will not work until I do !!!
     * 
     * @package     RoboTamer
     * @category    File
     * @author      Dennis T Kaplan
     * @copyright  (C) 2009-2011 Dennis T Kaplan
     *
     * @param  string $dfile Disk File
     * @param  string $mfile Memory File
     * @param  string $array
     * @return mixed
     */
    protected static function inc($dfile,$mfile = FALSE,$array = FALSE)
    {
        if($mfile !== FALSE){
            if(file_exists($mfile)){
				return file_get_contents($mfile);
                 include $mfile;
            }elseif(self::hasMRoot()){
                //file_put_contents($mfile, self::stripComments($dfile));
                file_put_contents($mfile, php_strip_whitespace($dfile));
                chmod($mfile, 00644);
				return file_get_contents($mfile);
            }else{
                // Fail Safe
				return file_get_contents($dfile);
            }
        }else{
			return file_get_contents($dfile);
        }
    }

    /**
     * Name stripComments
     *
     * Description Strip php comments from php file
     *
     * @package     TaMeR
     * @category    File
     * @type        Function
     * @author      Ionuț G. Stan
     *
     * @param  string $file
     * @param  mixed  $file_desc '/var/www/somefile.php'
     * @return string
     */
    public static function stripComments($file) {
        $fileStr = file_get_contents($file);
        $newStr  = '';

        $commentTokens = array(T_COMMENT);

        if (defined('T_DOC_COMMENT'))
            $commentTokens[] = T_DOC_COMMENT; // PHP 5
        if (defined('T_ML_COMMENT'))
            $commentTokens[] = T_ML_COMMENT;  // PHP 4

        $tokens = token_get_all($fileStr);

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $commentTokens))
                        continue;

                $token = $token[1];
            }

            $newStr .= $token;
        }
        // Replace all \s \t \n with one space and trim
        $newStr = trim(preg_replace('/\s+/', ' ', $newStr));
        $newStr = str_replace(', ',',',$newStr);
        $newStr = str_replace(' => ','=>',$newStr);
        $newStr = str_replace(' (','(',$newStr);
        $newStr = str_replace('( ','(',$newStr);
        $newStr = str_replace(') ',')',$newStr);
        $newStr = str_replace(' )',')',$newStr);
        $newStr = str_replace(' = ','=',$newStr);
        $newStr = str_replace(' }','}',$newStr);
        $newStr = str_replace('{ ','{',$newStr);
        return $newStr;
    }

    public static function prepArgs($args) {
        $args = preg_replace('/\s\s+/', ' ', trim($args));
        return explode(' ',$args);
    }

    protected static function hasMRoot() {
    /**
     * Checks if the memory directory is available on the file system
     * and creates the directory structure if the memory folder is
     * available but the directory structure has not been created.
     */
        if( defined('MROOT') && MROOT != FALSE){
            if(file_exists(MROOT)){
                return TRUE;
            }else{
                mkdir(MROOT, 0777);
                mkdir(MROOT.'class',  0777);
                mkdir(MROOT.'func' ,  0777);
                mkdir(MROOT.'array',  0777);
                return TRUE;
            }
        }else{
            return FALSE;
        }
    }
}
?>