<?php
/**
 * Name 	stripComments
 *
 * Description	Strip php comments from php file
 *
 * @package	RoboTaMeR
 * @category	File
 * @type	Function
 * @author      Dennis T Kaplan
 * @copyright	(C) 2009-2011 Dennis T Kaplan
 * @license	GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @todo
 *
 * @param  string $file
 * @param  mixed  $file_desc '/var/www/somefile.php' 
 * @return string 
 */
 
function stripComments($file)
{
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
	return $newStr;
}
?>
