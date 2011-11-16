<?php

/**
 * $Id: Template.php 1384 2007-04-06 21:02:59Z matthieu $
 */
if (!class_exists('Test_Html_Template')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	if (!defined('__SIMPLETEST_PATH__')) {
		define('__SIMPLETEST_PATH__', realpath(__CLASS_PATH__ . '/simpletest/'));
	}
	require_once __SIMPLETEST_PATH__ . '/shell_tester.php';
	require_once __SIMPLETEST_PATH__ . '/reporter.php';
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * base architecture for Html unit test cases
	 * @author matthieu <matthieu@phplibrairies.com>
	 * @package html
	 * @subpackage unit_test_case
	 */
	class Test_Html_Template extends ShellTestCase {
		/**
		 * @var mixed the object to test
		 * @access protected
		 */
		protected $obj = null;
		/**
		 * @var string $value : the expected value
		 * @access protected
		 */
		protected $value = '';
		/**
		 * @var string $fieldname : the fieldname
		 * @access protected
		 */
		protected $fieldname = '';
		/**
		 * design style class name
		 */
		const __STYLE_CLASS_NAME__ = 'design';
		/**
		 * design style value
		 */
		const __STYLE_CONTENT__ = 'margin: 5px; width: 150px; font-weight: bold;';
		/**
		 * the default image picture
		 */
		const __DEFAULT_PICT_PATH__ = './img/picture.gif';
		/**
		 * constructor
		 * @param string $closeBracket : the closeBracket value
		 * @return void
		 */
		public function __construct() {
			parent :: __construct();
		}
		/**
		 * test a getter answer
		 * @access protected
		 * @param string $expectedValue : the expected value
		 * @param string $valueFound : the value found
		 * @return void
		 */
		protected function testGetterAnswer($valueFound, $expectedValue) {
			$this->assertTrue(($valueFound != ''), 'return value is empty');
			$this->assertTrue(($valueFound == $expectedValue), "value mismatch: expected $expectedValue found $valueFound");
		}
		/**
		 * test the toString method of the object
		 * @param string $expectedCodeValue : the expected code value
		 * @access protected
		 * @return void
		 */
		protected function _testCode($expectedCodeValue) {
			$return = $this->obj->__toString();
			$this->assertTrue(($return != ''), 'toString return an empty value');
			$this->assertTrue((strcasecmp($return, $expectedCodeValue) == 0), "unexpected return value for toString: found $return, expected $expectedCodeValue");
		}
		/**
		 * test a getter
		 * @access protected
		 * @return void
		 * @param string $getter_name : the name of the getter used
		 * @param string $expect_value
		 * @param string $found_value
		 */
		protected function _testGetter($getter_name, $expected_value, $found_value) {
			$this->assertTrue((strcasecmp($expected_value, $found_value) == 0), 'Unexpected return value for getter ' . $getter_name . ': found ' . $found_value . ', expecting ' . $expected_value);
		}
        /**
         * check bug 1
         * @see http://www.phplibrairies.com/bugtrack/
         */
        public function testBug_1() {
            if (!is_null($this->obj)) {
                if (in_array("Html_Design_Interface", class_implements(get_class($this->obj)))) {
                    $this->obj->setStyle('font-weight: bold');
                    $this->assertTrue((strcasecmp($this->obj->getStyle(), 'font-weight: bold') == 0), "class have bug #1, see http://www.phplibrairies.com/bugtrack/");
                } else {
                    $this->assertTrue(true);
                }
            }
        }
	}
}