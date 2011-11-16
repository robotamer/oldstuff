<?php
/**
 * $Id: FormSelect.class.php 562 2006-09-28 16:38:53Z matthieu $
 */
if (!class_exists('Test_Form_Select')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * unit test case for Form_Select class
	 * @author matthieu <matthieu@phplibrairies.com>
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Form_Select extends Test_Html_Template implements Test_Html_Design_Interface {
		/**
		 * the group name label
		 */
		const __GROUP_NAME__ = 'group_name';
		/**
		 * the test field name
		 */
		const __FIELDNAME__ = 'selectfield';
		/**
		 * @var mixed $option : the option object to test
		 * @access private
		 */
		private $opt = null;
		/**
		 * constructor
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent :: __construct();
			$this->fieldname = self :: __FIELDNAME__;
			$this->opt = new Form_Option('option_name', 'option_value', true);
		}
		/**
		 * reinit the FormTextarea object
		 * @access public
		 * @return void
		 */
		public function setUp() {
			$this->obj = new Form_Select($this->fieldname);
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testDefault() {
			$this->_testCode('<select name="' . $this->fieldname . '"></select>');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyle() {
			$this->obj->setStyle(parent :: __STYLE_CONTENT__);
			$this->_testCode('<select name="' . $this->fieldname . '" style="' . parent :: __STYLE_CONTENT__ . '"></select>');
			$this->_testGetter('getStyle()', parent :: __STYLE_CONTENT__, $this->obj->getStyle());
		}
		/**
		 * test the set style class method
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass() {
			$this->obj->setStyleClass(parent :: __STYLE_CLASS_NAME__);
			$this->_testCode('<select name="' . $this->fieldname . '" class="' . parent :: __STYLE_CLASS_NAME__ . '"></select>');
            $this->_testGetter('getStyleClass()', parent :: __STYLE_CLASS_NAME__, $this->obj->getStyleClass());
		}
		/**
		 * test the add option method
		 * @access public
		 * @return void
		 */
		public function testAddOption() {
			$this->obj->addOption($this->opt->getName(), $this->opt->getValue(), $this->opt->isSelected());
			$this->_testCode('<select name="' . $this->fieldname . '"><option value="' . $this->opt->getValue() . '" selected="selected">' . $this->opt->getName() . '</option></select>');
		}
		/**
		 * test the add option object method
		 * @access public
		 * @return void
		 */
		public function testAddOptionObject() {
			$this->obj->addOptionObject($this->opt);
			$this->_testCode('<select name="' . $this->fieldname . '"><option value="' . $this->opt->getValue() . '" selected="selected">' . $this->opt->getName() . '</option></select>');
		}
		/**
		 * test the add option method
		 * @access public
		 * @return void
		 */
		public function testAddOptionGroup() {
			$this->obj->addOptionGroup(self :: __GROUP_NAME__, $this->opt->getName(), $this->opt->getValue(), $this->opt->isSelected());
			$this->_testCode('<select name="' . $this->fieldname . '"><optgroup label="' . self :: __GROUP_NAME__ . '"><option value="' . $this->opt->getValue() . '" selected="selected">' . $this->opt->getName() . '</option></optgroup></select>');
		}
		/**
		 * test the add option object method
		 * @access public
		 * @return void
		 */
		public function testAddOptionGroupObject() {
			$this->obj->addOptionGroupObject(self :: __GROUP_NAME__, $this->opt);
			$this->_testCode('<select name="' . $this->fieldname . '"><optgroup label="' . self :: __GROUP_NAME__ . '"><option value="' . $this->opt->getValue() . '" selected="selected">' . $this->opt->getName() . '</option></optgroup></select>');
		}
		/**
		 * test the set multiple method
		 * @access public
		 * @return void
		 */
		public function testSetMultiple() {
			$this->obj->setMultiple(5);
			$this->_testCode('<select name="' . $this->fieldname . '" multiple="multiple"></select>');
		}
		/**
		 * test the set disabled method
		 * @access public
		 * @return void
		 */
		public function testSetDisabled() {
			try {
				$this->obj->setDisabled();
				$this->_testCode('<select name="' . $this->fieldname . '" disabled></select>');
			}
			catch (Exception $e) {
				$this->assertTrue(false, 'An unexpected exception is thrown while disabling ' . get_class($this->obj) . ' object :' . $e->getMessage());
			}
		}
		/**
		 * test the add js method
		 * @access public
		 * @return void
		 */
		public function testAddJS() {
			$js = new Html_Javascript(Html_Javascript :: __ONCHANGE__, "alert('ok')");
			$this->obj->addJs($js);
			$this->_testCode('<select name="' . $this->fieldname . '" ' . $js->__toString() . '></select>');
			unset ($js);
		}
		/**
		* test the add js method with an unexpected js event
		* @access public
		* @return void
		*/
		public function testAddJSUnexpected() {
			$this->obj->addJs(new Html_Javascript(Html_Javascript :: __ONSUBMIT__, "alert('ok')"));
			$this->_testCode('<select name="' . $this->fieldname . '"></select>');
		}
	}
}