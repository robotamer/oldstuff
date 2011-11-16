<?php
/**
 * $Id: FormTextarea.class.php 562 2006-09-28 16:38:53Z matthieu $
 */
if (!class_exists('Test_Form_Textarea')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * unit test case for Form_Textarea class
	 * @author matthieu <matthieu@phplibrairies.com>
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Form_Textarea extends Test_Html_Template implements Test_Html_Design_Interface {
		/**
		 * the test field name
		 */
		const __FIELDNAME__ = "field";
		/**
		 * number of rows
		 */
		const __ROWS_NUMBER__ = 10;
		/**
		 * number of cols
		 */
		const __COLS_NUMBER__ = 10;
		const __VALUE__ = 'Type your text here';
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
			$this->obj = new Form_Textarea($this->fieldname);
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testDefault() {
			$this->_testCode('<textarea name="' . $this->fieldname . '"></textarea>');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyle() {
			$this->obj->setStyle(parent :: __STYLE_CONTENT__);
			$this->_testCode('<textarea name="' . $this->fieldname . '" style="' . parent :: __STYLE_CONTENT__ . '"></textarea>');
			$this->_testGetter('getStyle()', parent :: __STYLE_CONTENT__, $this->obj->getStyle());
		}
		/**
		 * test the set style class method
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass() {
			$this->obj->setStyleClass(parent :: __STYLE_CLASS_NAME__);
			$this->_testCode('<textarea name="' . $this->fieldname . '" class="' . parent :: __STYLE_CLASS_NAME__ . '"></textarea>');
            $this->_testGetter('getStyleClass()', parent :: __STYLE_CLASS_NAME__, $this->obj->getStyleClass());
		}
		/**
		 * test the set value method
		 * @access public
		 * @return void
		 */
		public function testSetValue() {
			$this->obj->setValue($this->value);
			$this->_testCode('<textarea name="' . $this->fieldname . '">' . $this->value . '</textarea>');
		}
		/**
		 * test the set rows method
		 * @access public
		 * @return void
		 */
		public function testSetRows() {
			$this->obj->setRows(self :: __ROWS_NUMBER__);
			$this->_testCode('<textarea name="' . $this->fieldname . '" rows="' . self :: __ROWS_NUMBER__ . '"></textarea>');
		}
		/**
		 * test the set cols method
		 * @access public
		 * @return void
		 */
		public function testSetCols() {
			$this->obj->setCols(self :: __COLS_NUMBER__);
			$this->_testCode('<textarea name="' . $this->fieldname . '" cols="' . self :: __COLS_NUMBER__ . '"></textarea>');
		}
		/**
		 * test the add js method
		 * @access public
		 * @return void
		 */
		public function testAddJS() {
			$js = new Html_Javascript(Html_Javascript :: __ONCHANGE__, "alert('ok')");
			$this->obj->addJs($js);
			$this->_testCode('<textarea name="' . $this->fieldname . '" ' . $js->__toString() . '></textarea>');
			unset ($js);
		}
		/**
		* test the add js method with an unexpected js event
		* @access public
		* @return void
		*/
		public function testAddJSUnexpected() {
			$this->obj->addJs(new Html_Javascript(Html_Javascript :: __ONRESET__, "alert('ok')"));
			$this->_testCode('<textarea name="' . $this->fieldname . '"></textarea>');
		}
		/**
		* test the set disabled method
		* @access public
		* @return void
		*/
		public function testSetDisabled() {
			try {
				$this->obj->setDisabled();
				$this->_testCode('<textarea name="' . $this->fieldname . '" disabled></textarea>');
			}
			catch (Exception $e) {
				$this->assertTrue(false, 'An unexpected exception is thrown while disabling ' . get_class($this->obj) . ' object :' . $e->getMessage());
			}
		}
	}
}