<?php // Validate

/**
 * @package      TaMeR FrameWork
 * @category     Core
 * @copyright    2008 - 2011 Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 */

/**
 * @author     Dennis T Kaplan
 */


class Validate {

    public function __construct() {}

    /**
     * Alpha
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function alpha($string) {
        // The "i" after the pattern delimiter indicates a case-insensitive search
        return ( ! preg_match("/^([a-z])+$/i", $string)) ? FALSE : TRUE;
    }

    /**
     * Numeric
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function numeric($string) {
        return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $string);

    }
}

?>