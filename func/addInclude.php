<?php
/**
 * Add include directory
 *
 * Add a directory to the php include path
 */

/**
 * @category   TaMeR
 * @package    Functions
 * @subpackage File
 * @author     Dennis T Kaplan
 * @copyright  Copyright (c) 2008 - 2011 Dennis T Kaplan
 * @license    http://tamer.pzzazz.net/license.html
 * @link       http://tamer.pzzazz.net
 *
 * @param string  $dir The directory to add to the path
 * @param boolean $at_end If true, place this directory at the end of the include path. Otherwise, place it at the beginning.
 */
function addInclude($dir, $at_end = false)
{
    $exist = file_exists($dir);
    if ( ! $exist || ($exist && filetype($dir) != 'dir'))
    {
        trigger_error("Include path '{$dir}' does not exist", E_USER_WARNING);
        echo '<pre>'; print_r(debug_backtrace()); echo '</pre>';
        exit;
    }
    $dir   = rtrim($dir, "/");
    $paths = ltrim(get_include_path(), ".".PATH_SEPARATOR);
    $paths = explode(PATH_SEPARATOR, $paths);
    $ds    = '.'.PATH_SEPARATOR;
    if (strlen($paths[0]) && array_search($dir, $paths) === false) {
        $at_end ? array_push($paths, $dir) : array_unshift($paths, $dir);
        $paths = implode(PATH_SEPARATOR, $paths);
        set_include_path($ds.$paths);
    } else {
        set_include_path($ds.$dir);
    }
}
/* #Test it
echo get_include_path();
echo '<br />';
addInclude('/var/www/library/');
echo get_include_path();
echo '<br />';
*/
?>
