<?php // TaMeR Message
/**
 *
 * @category   TaMeR
 * @package    Message
 * @copyright  Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license    http://tamer.pzzazz.net/license.html
 * @link       http://tamer.pzzazz.net
 *********************************************************/

/**
 * As with all TaMeR libraries you will have to have a few thinks defined
 * TaMeR standard is that those are defined in the boot.php file
 * located at your servers document root.
 * 
 * Prerequisites:
 * 
 * Optional requisites:
 * You may define the debug constant to get errors and notices
 * define('DEBUG', 1);
 */

defined('PROOT') || include $_SERVER['DOCUMENT_ROOT'].'/boot.php';

/**
 * Message uses cookies and javascript to deliver info & error messages
 * to the web suer
 * 
 * @category   TaMeR
 * @package    Message
 * @author     Dennis T Kaplan
 */
class Message {

	//--- Class Constants
	const warn    = '<img src="/media/icon/warn.gif" alt="Warning" />';
	const error   = '<img src="/media/icon/error.gif" alt="Error" />';
	const stop    = '<img src="/media/icon/stop.gif" alt="Stop" />';
	const info    = '<img src="/media/icon/info.gif" alt="Info" />';
	const success = '<img src="/media/icon/success.gif" alt="Success" />';
	const red     = '#FF0000';
	const green   = '#008000';
	const yellow  = '#FF9900';
	const blue    = '#0000FF';
		
	function __construct()
    {

    }

    function __destruct()
    {
    }
    
	function viewIcons()
    {
		echo self::success . EOL;
		echo self::warn . EOL;
		echo self::info . EOL;
		echo self::error . EOL;
		echo self::stop . EOL;
    }


}
?>
