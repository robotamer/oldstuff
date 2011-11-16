<?php
/**
 *
 * @package      TaMeR
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 */


/**
 * Name: 
 * Description
*
* @author  :  Dennis T Kaplan
*        
* @version :  1.0
* Date     :  June 17, 2009
* Function : fileget()
* Purpose  : includes requested function or class from file
*
* @access  : public
* @param   : string func or class name
* @return  : function or class
**/

if (!function_exists('fileget'))
    {
        $i = str_replace('//','/',dirname(__FILE__).'/');
        define("PATH", substr($i, 0,strpos($i, 'TaMeR')));
        unset($i);

        function fileget($s)
            {
                $a = $s;
                if(strpos($s, '.') > 0)
                    {
                        $a= substr($a, 0,strpos($a, '.'));
                    }
                if (!function_exists($a))
                    {
                        $lines = file(PATH.'TaMeR/files');
                        foreach ($lines as $no => $line)
                            {
                                if((strpos($line,$s) > 0) && (!strpos($line,'/test/')))
                                    {
                                        $line = trim($line,'./');
                                        $line = trim($line,"\n");
                                        $line = PATH.'TaMeR/'.$line;
                                        include_once($line);
                                    }
                            }
                    }
            }
    }
?>