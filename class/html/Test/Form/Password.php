<?php
/**
 * $Id: FormPassword.class.php 562 2006-09-28 16:38:53Z matthieu $
 */
if (!class_exists('Test_Form_Password')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	if (!defined('__TESTCLASSES_PATH__')) {
		define('__TESTCLASSES_PATH__', realpath(__CLASS_PATH__ . '/testclasses/'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * unit test case for Form_Password class
	 * @author matthieu <matthieu@phplibrairies.com>
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Form_Password extends Test_Html_Template implements Test_Html_Design_Interface {
		/**
		 * the test field name
		 */
		const __FIELDNAME__ = "passfield";
		/**
		 * the value
		 */
		const __VALUE__ = 'Login';
		/**
		 * the field length
		 */
		const __LENGTH__ = 40;
		/**
		 * constructor
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent :: __construct();
			$this->fieldname = self :: __FIELDNAME__;
		}
		/**
		 * reinit the FormTextarea object
		 * @access public
		 * @return void
		 */
		public function setUp() {
			$this->obj = new Form_Password($this->fieldname);
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testDefault() {
			$this->_testCode('<input type="password" name="' . $this->fieldname . '"/>');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyle() {
			$this->obj->setStyle(parent :: __STYLE_CONTENT__);
			$this->_testCode('<input type="password" name="' . $this->fieldname . '" style="' . parent :: __STYLE_CONTENT__ . '"/>');
			$this->_testGetter('getStyle()', parent :: __STYLE_CONTENT__, $this->obj->getStyle());
		}
		/**
		 * test the set style class method
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass() {
			$this->obj->setStyleClass(parent :: __STYLE_CLASS_NAME__);
			$this->_testCode('<input type="password" name="' . $this->fieldname . '" class="' . parent :: __STYLE_CLASS_NAME__ . '"/>');
            $this->_testGetter('getStyleClass()', parent :: __STYLE_CLASS_NAME__, $this->obj->getStyleClass());
		}
		/**
		 * test the set length method
		 * @access public
		 * @return void
		 */
		public function testSetLength() {
			$this->obj->setLength(self :: __LENGTH__);
			$this->_testCode('<input type="password" name="' . $this->fieldname . '" size="' . self :: __LENGTH__ . '"/>');
		}
		/**
		 * test the set length method
		 * @access public
		 * @return void
		 */
		public function testSetMaxLength() {
			$this->obj->setMaxLength(self :: __LENGTH__);
			$this->_testCode('<input type="password" name="' . $this->fieldname . '" maxlength="' . self :: __LENGTH__ . '"/>');
		}
		/**
		 * test the add js method
		 * @access public
		 * @return void
		 */
		public function testAddJS() {
			$js = new Html_Javascript(Html_Javascript :: __ONFOCUS__, "alert('ok')");
			$this->obj->addJs($js);
			$this->_testCode('<input type="password" name="' . $this->fieldname . '" ' . $js->__toString() . '/>');
			unset ($js);
		}
		/**
		* test the add js method with an unexpected js event
		* @access public
		* @return void
		*/
		public function testAddJSUnexpected() {
			$this->obj->addJs(new Html_Javascript(Html_Javascript :: __ONSUBMIT__, "alert('ok')"));
			$this->_testCode('<input type="password" name="' . $this->fieldname . '"/>');
		}
		/**
		* test the set disabled method
		* @access public
		* @return void
		*/
		public function testSetDisabled() {
			try {
				$this->obj->setDisabled();
				$this->_testCode('<input type="password" name="' . $this->fieldname . '" disabled/>');
			}
			catch (Exception $e) {
				$this->assertTrue(false, 'An unexpected exception is thrown while disabling ' . get_class($this->obj) . ' object :' . $e->getMessage());
			}
		}
	}
}