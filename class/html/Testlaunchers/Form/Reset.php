<?php
/**
 * $Id: Reset.php 1630 2007-05-12 22:39:42Z matthieu $
 */
if (!defined('__CLASS_PATH__')) {
	define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
}
require_once __CLASS_PATH__ . '/Autoload.php';
/**
 * launcher for unit test case Test_Form_Submit
 * @author Matthieu MARY <matthieu@phplibrairies.com>
 * @package html
 * @subpackage unit_test_case
 */
$test = new Test_Form_Reset();
$test->run(new TextReporter());
?>