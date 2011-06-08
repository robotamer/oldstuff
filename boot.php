<?php
date_default_timezone_set('UTC');

//defined('DEBUG') || define('DEBUG', false);
defined('DEBUG') || define('DEBUG', 1);
//defined('DEBUG') || define('DEBUG', 'Route'); // See log

//defined('MROOT') || define('MROOT','/dev/shm/php/'); #Memory
defined('MROOT') || define('MROOT',FALSE); #No Memory
define('PHPL', '/var/www/asset/php/' ); # PHP Libraries

$host = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
$domain = $host[1].'.'.$host[0];
$subdomain = isset($host[2]) ? $host[2] : 'www';

switch ($subdomain) {
	case 'tr': // Turkish
		define('LANGUAGE', 'tr');
		break;
	case 'de': //German
		define('LANGUAGE', 'de');
		break;
	default:  // English
		define('LANGUAGE', 'en');
}
define('DOMAIN', strtolower($domain));
define('HOST', LANGUAGE.'.'.DOMAIN);
define('URL', 'http://'.LANGUAGE.'.'.DOMAIN);
unset($host,$domain,$subdomain);

# APPL must be defined in execution file!
//defined('APPL')  || trigger_error("Please define application location (APPL)");
########################################################################
# System Configuration
########################################################################

defined('__DIR__') || define('__DIR__', substr(__FILE__, 0, strrpos(__FILE__, '/')));

defined('ROOT')  || define('ROOT', __DIR__.'/');
define('PROOT', ROOT.'public/' ); #Public
define('AROOT', ROOT.'appl/');    # Application / Controller
define('LROOT', ROOT.'libs/' ); # Local Libraries
define('DROOT', ROOT.'data/'); # Data, DB etc
define('CROOT', PHPL.'class/' ); # Classes
define('FROOT', PHPL.'func/'); # Functions

$title = $description = $author = $keywords = $message='';

include(FROOT.'autoload.php');


if(DEBUG != FALSE) {
	//error_reporting(E_ALL | E_STRICT);
	error_reporting(E_ALL | E_DEPRECATED);
	@ini_set('display_errors', 1);
	@ini_set('display_startup_errors', 1);
	@ini_set('warn_plus_overloading', 1);
	Load::f('e');
}else{
	error_reporting(E_ALL ^ E_WARNING);
	@ini_set('display_errors', 0);
	@ini_set('display_startup_errors', 0);
	@ini_set('warn_plus_overloading', 0);
	Log::set($_SERVER['REMOTE_ADDR'],'Remote IP');
}

defined('DS')    || define('DS'   ,DIRECTORY_SEPARATOR);
if(isset($_SERVER['argv'])){
	defined('BR') || define('BR'   ,"\n");
	defined('HR') || define('HR'   ,"\n_______________________\n");
}else{
	defined('BR') || define('BR'   ,"<br/>\n");
	defined('HR') || define('HR'   ,"<hr/>\n");
}

/*
 * Loading contrapart asset file to public file
 */
if (defined('APPL') && file_exists(AROOT.basename($_SERVER['SCRIPT_NAME'])))
include AROOT.APPL.DS.basename($_SERVER['SCRIPT_NAME']);
?>