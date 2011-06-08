<?php
/**
 * Rreverse strstr()
 *
 * Returns part of haystack string from start to the first occurrence
 * of the needle
 */

/**
 * @category   TaMeR
 * @package    Functions
 * @subpackage String
 * @copyright  Copyright (c) 2008 - 2011 Dennis T Kaplan
 * @license    http://tamer.pzzazz.net/license.html
 * @link       http://tamer.pzzazz.net
 * @author     Dennis T Kaplan
 * @date       June 17, 2007
 * @version    1.2
 * @access     public
 * @param      string $haystack 'this/that/whatever'
 * @param      string $needle   '/'
 * @param      string $start     0 will return this
 * @return     string
 **/
function rstrstr($haystack,$needle, $start=0)
   {
      return substr($haystack, $start,strpos($haystack, $needle));
   }
?>
