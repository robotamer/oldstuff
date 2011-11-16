<?php // mvRecrucivly

/**
 * Name: mvRecrucivly
 * Description
 *
 * @package   TaMeR Framework Core
 * @copyright (C) 2009-2010 Dennis T Kaplan
 * @license   GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link      http://phptamer.pzzazz.com/license.html
 *
 * @subpackage
 * @author       Dennis T Kaplan
 * @todo
 */
echo "\t Edit first!!! \n"; exit;
$basedir = '/tmp/font/';
function getDirectoryTree( $outerDir, $filters = array() ) {
    $dirs = array_diff( scandir( $outerDir ), array_merge( Array( ".", ".." ), $filters ) );
    $dir_array = Array();
    foreach( $dirs as $d ) {
        if(is_dir($outerDir."/".$d)) {
            $dir_array[ $d ] = getDirectoryTree( $outerDir."/".$d, $filters ) ;
        }
        if(is_file($outerDir."/".$d)) {
            $file = $outerDir."/".$d;
            $newfile = '/tmp/fonts/'.strtolower($d);
            echo $file . "\n";
            if(strpos($newfile, '.ttf') > 0) {
                $newfile = str_replace(' ', '_', $newfile);
                if (!copy($file, $newfile)) {
                    echo "failed to copy $file \t to \t $newfile ...\n";
                }else{
                    echo "Copied $file \t to \t $newfile ...\n";
                }
            }
        }
    }
    return $dir_array;
}


$dirs = getDirectoryTree($basedir);

?>
