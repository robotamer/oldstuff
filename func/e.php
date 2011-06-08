<?php
/**
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see http://www.gnu.org/licenses/
**/

/**
 * e() --- Prints human-readable information about a variable
 *
 * string e ( mixed $expression[, string $name [, bool $return = false ]] )
 *
 * Replacement for php echo, print, print_r(), var_export() etc
 */

/**
 * @category    TaMeR
 * @copyright   Copyright (c) 2008 - 2011 Dennis T Kaplan
 * @license     http://www.gnu.org/licenses/gpl.txt
 * @link        http://github.com/pzzazz/TaMeR
 * @author      Dennis T Kaplan
 * @date        May 1, 2011
 * @version     1.0
 * @access      public
 * @param       mixed   $var
 * @param       string  $name
 * @param       boolean $return
 * @return      string
 **/
function e($var, $name = FALSE, $return = FALSE) {
    $preO = $preC = ''; $br = PHP_EOL;
    if( ! isset($_SERVER['argv'])){
        $preO = '<pre>'; $preC = '</pre>';
        $h1O = '<h1>';   $h1C = '</h1>';
        $br = '<br />'.PHP_EOL;
    }
    if(!is_array($var) && !is_object($var))
    {
        if ($name !== FALSE) echo $br.$name.': ';
        echo (isset($_SERVER['argv']))
                  ? $var.$br
                  : htmlspecialchars($var).$br;
    }else{
        if($return === FALSE) {
            if ($name !== FALSE) echo $br.$h1O.$name.': '.$h1C;
            echo $preO.print_r($var, TRUE).$preC.$br;
        }else{
            if ($name !== FALSE){
                return '<?php'.$br.'$'.$name.' = '.var_export($string, TRUE).';'.$br.'?>';
            }else{
                return $br.$preO.var_export($var, TRUE).$preC.$br;
            }
        }
    }
}
?>