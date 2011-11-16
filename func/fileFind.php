<?php

// file Functions
function fileFind($dir, $file, $ext = 'php') {
    /*
     * Finds the path of a file by directory, filename, and extension.
     * If no extension is given, the default .php extension will be used.
     *
     * @param   string   directory name (html, bin, db, etc, etc.)
     * @param   string   filename with subdirectory
     * @param   string   extension to search for
     * @return  string   single file path
    */
    $_paths = array(USR,LIB,ETC);
    $ext = '.'.$ext;
    $file = $file.$ext;
    $path = $dir.'/'.$file;
    $found = FALSE;
    if ($dir == 'etc') {
        $found = ETC.'/'.$file;
    }elseif($dir == 'html') { 
        $found = USR.'/'.$path;
    }elseif ($dir == 'bin') {
        $found = BIN.'/'.$path;
    } else {
        foreach ($_paths as $dir) {
            if (is_file($dir.$path)) {
                // A path has been found
                $found = $dir.$path;
                return $found;
                break;
            }
        }
    }
    if (is_file($found)) {
        return $found;
    }else {
        echo '<pre>';
        Throw new Exception('The requested '.$path.' was not found');
    }
}

?>
