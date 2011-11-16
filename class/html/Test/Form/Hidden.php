<?php
/**
 * $Id: FormHidden.class.php 562 2006-09-28 16:38:53Z matthieu $
 */
if (!class_exists('Test_Form_Hidden')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * unit test case for Form_Hidden class
	 * @author matthieu <matthieu@phplibrairies.com>
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Form_Hidden extends Test_Html_Template {
		/**
		 * the value
		 */
		const __VALUE__ = 'Value';
		const __NAME__ = 'hiddenField';
		/**
		 * constructor
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent :: __construct();
			$this->value = self :: __VALUE__;
		}
		/**
		 * reinit the FormButton object
		 * @access public
		 * @return void
		 */
		public function setUp() {
			$this->obj = new Form_Hidden(self :: __NAME__);
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testDefault() {
			$this->_testCode('<input type="hidden" name="' . self :: __NAME__ . '"/>');
		}
		/**
		 * test the set value method
		 * @access public
		 * @return void
		 */
		public function testSetValue() {
			$this->obj->setValue($this->value);
			$this->_testCode('<input type="hidden" name="' . self :: __NAME__ . '" value="' . $this->value . '"/>');
		}
		/**
		* test the set disabled method
		* @access public
		* @return void
		*/
		public function testSetDisabled() {
			try {
				$this->obj->setDisabled();
                $this->assertTrue(false, 'No exception is thrown when asking disabled method on ' . get_class($this->obj) . ' instance');
            }
			catch (Exception $e) {
				$this->_testCode('<input type="hidden" name="' . self :: __NAME__ . '"/>');
			}
		}
	}
}