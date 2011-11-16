<?php
/**
 * An open source application development framework for PHP 5.2 or newer
 *
 * @package      RoboTaMeR
 * @copyright    Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 */


/**        
* @version :  1.0
* Date     :  June 17, 2007
* Function : getip
* Purpose  : Get IP Address
*
* @access	public
* @return	string
**/ 

function getip() {
    if (getenv('HTTP_CLIENT_IP')) {
        $IP = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $IP = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
        $IP = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
        $IP = getenv('HTTP_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_FORWARDED')) {
        $IP = getenv('HTTP_FORWARDED');
    }
    else {
        $IP = $_SERVER['REMOTE_ADDR'];
    }
    
    if (strstr($IP, ', ')) {
        $ips = explode(', ', $IP);
        $IP = $ips[0];
    }
    return($IP);
}
?>