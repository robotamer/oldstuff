<?php
/**
 * $Id: test.FormSelect.php 562 2006-09-28 16:38:53Z matthieu $
 */
if (!defined('__CLASS_PATH__')) {
	define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
}
require_once __CLASS_PATH__ . '/Autoload.php';
/**
 * launcher for unit test case Test_Form_Select
 * @author Matthieu MARY <matthieu@phplibrairies.com>
 * @package html
 * @subpackage unit_test_case
 */
$test = new Test_Form_Select();
$test->run(new TextReporter());
?>