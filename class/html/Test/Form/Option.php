<?php
/**
 * $Id: FormOption.class.php 562 2006-09-28 16:38:53Z matthieu $
 */
if (!class_exists('Test_Form_Option')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * unit test case for Form_Option class
	 * @author matthieu <matthieu@phplibrairies.com>
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Form_Option extends Test_Html_Template {
		/**
		 * the test field name
		 */
		const __FIELDNAME__ = "selectFieldname";
		/**
		 * the value
		 */
		const __VALUE__ = 'optionValue';
		/**
		 * constructor
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent :: __construct();
			$this->fieldname = self :: __FIELDNAME__;
			$this->value = self :: __VALUE__;
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testDefault() {
			$this->obj = new Form_Option($this->fieldname);
			$this->_testCode('<option>' . $this->fieldname . '</option>');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testDefaultSelected() {
			$this->obj = new Form_Option($this->fieldname, '', true);
			$this->_testCode('<option selected="selected">' . $this->fieldname . '</option>');
		}
		/**
		 * test with a value method
		 * @access public
		 * @return void
		 */
		public function testWithValue() {
			$this->obj = new Form_Option($this->fieldname, $this->value);
			$this->_testCode('<option value="' . $this->value . '">' . $this->fieldname . '</option>');
		}
		/**
		 * test the selected method
		 * @access public
		 * @return void
		 */
		public function testSelected() {
			$this->obj = new Form_Option($this->fieldname, $this->value, true);
			$this->_testCode('<option value="' . $this->value . '" selected="selected">' . $this->fieldname . '</option>');
		}
                /**
        * test the set disabled method
        * @access public
        * @return void
        */
        public function testSetDisabled() {
            $this->obj = new Form_Option($this->fieldname, $this->value, true);
            try {
                $this->obj->setDisabled();
                $this->assertTrue(false, 'No exception is thrown when asking disabled method on ' . get_class($this->obj) . ' instance');
            }
            catch (Exception $e) {
                $this->_testCode('<option value="' . $this->value . '" selected="selected">' . $this->fieldname . '</option>');
            }
        }
	}
}