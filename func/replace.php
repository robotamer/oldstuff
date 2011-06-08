<?php
/**
 * @category   TaMeR
 * @package    Functions
 * @copyright  2008 - 2011 Dennis T Kaplan
 * @license    http://tamer.pzzazz.net/license.html
 * @link       http://tamer.pzzazz.net
 * @author     Dennis T Kaplan
 * @date       May 1, 2011
 * @version    1.0
 * @access     public
 **/

/**
 * Replace all spaces with underscore
 * Only one underscore for multiple spaces in a row
 *
 * @param string $string
 * @return string
 */
function replaceSpaceUnderscore($string)
{
    return preg_replace('/ss+/i', '_', $string);
}

/**
 * Replace all spaces with a spcae
 * Only one space for multiple spaces in a row
 *
 * @param string $string
 * @return string
 */
function replaceSpacesSpace($string)
{
    return preg_replace('/ss+/i', ' ', $string);
}

/**
 * Replace all spaces
 * No space will be left
 *
 * @param string $string
 * @return string
 */
function replaceSpacesNoSpace($string)
{
    return preg_replace('/ss+/i', '', $string);
}

?>