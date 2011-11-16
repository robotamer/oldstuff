<?php
/**
 * $Id: FormRadio.class.php 562 2006-09-28 16:38:53Z matthieu $
 */
if (!class_exists('Test_Form_Radio')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * unit test case for Form_Radio class
	 * @author matthieu <matthieu@phplibrairies.com>
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Form_Radio extends Test_Html_Template implements Test_Html_Design_Interface {
		/**
		 * the test field name
		 */
		const __FIELDNAME__ = "radiofield";
		/**
		 * the value
		 */
		const __VALUE__ = 'selected_value';
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
		 * reinit the FormTextarea object
		 * @access public
		 * @return void
		 */
		public function setUp() {
			$this->obj = new Form_Radio($this->fieldname);
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testDefault() {
			$this->_testCode('<input type="radio" name="' . $this->fieldname . '"/>');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyle() {
			$this->obj->setStyle(parent :: __STYLE_CONTENT__);
			$this->_testCode('<input type="radio" name="' . $this->fieldname . '" style="' . parent :: __STYLE_CONTENT__ . '"/>');
			$this->_testGetter('getStyle()', parent :: __STYLE_CONTENT__, $this->obj->getStyle());
		}
		/**
		 * test the set style class method
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass() {
			$this->obj->setStyleClass(parent :: __STYLE_CLASS_NAME__);
			$this->_testCode('<input type="radio" name="' . $this->fieldname . '" class="' . parent :: __STYLE_CLASS_NAME__ . '"/>');
            $this->_testGetter('getStyleClass()', parent :: __STYLE_CLASS_NAME__, $this->obj->getStyleClass());
		}
		/**
		 * test the set value method
		 * @access public
		 * @return void
		 */
		public function testSetValue() {
			$this->obj->setValue($this->value);
			$this->_testCode('<input type="radio" name="' . $this->fieldname . '" value="' . $this->value . '"/>');
		}
		/**
		 * test the set checked method
		 * @access public
		 * @return void
		 */
		public function testSetChecked() {
			$this->obj->setChecked();
			$this->_testCode('<input type="radio" name="' . $this->fieldname . '" checked="checked"/>');
		}
		/**
		 * test the add js method
		 * @access public
		 * @return void
		 */
		public function testAddJS() {
			$js = new Html_Javascript(Html_Javascript :: __ONFOCUS__, "alert('ok')");
			$this->obj->addJs($js);
			$this->_testCode('<input type="radio" name="' . $this->fieldname . '" ' . $js->__toString() . '/>');
		}
		/**
		* test the add js method with an unexpected js event
		* @access public
		* @return void
		*/
		public function testAddJSUnexpected() {
			$this->obj->addJs(new Html_Javascript(Html_Javascript :: __ONRESET__, "alert('ok')"));
			$this->_testCode('<input type="radio" name="' . $this->fieldname . '"/>');
		}
		/**
		* test the set disabled method
		* @access public
		* @return void
		*/
		public function testSetDisabled() {
			try {
				$this->obj->setDisabled();
				$this->_testCode('<input type="radio" name="' . $this->fieldname . '" disabled/>');
			}
			catch (Exception $e) {
				$this->assertTrue(false, 'An unexpected exception is thrown while disabling ' . get_class($this->obj) . ' object :' . $e->getMessage());
			}
		}
	}
}