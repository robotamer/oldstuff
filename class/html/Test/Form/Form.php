<?php
/**
 * $Id: Form.class.php 562 2006-09-28 16:38:53Z matthieu $
 */
if (!class_exists('Test_Form_Form')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * unit test case for Form_Form class
	 * @author matthieu <matthieu@phplibrairies.com>
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Form_Form extends Test_Html_Template implements Test_Html_Target_Interface, Test_Html_Design_Interface {
		const __GET__ = 'GET';
		const __FIELDNAME__ = 'formLabel';
		const __DEFAULT_METHOD__ = Form_Form :: _METHOD_POST_;
		const __DEFAULT_TARGET__ = Html_Target_Interface :: _TARG_SELF_;
		/**
		 * @var string $action the form action
		 * @access private
		 */
		private $action = '';
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
			$this->obj = new Form_Form($this->action);
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testDefault() {
			$this->_testCode('<form action="' . $this->action . '" method="' . self :: __DEFAULT_METHOD__ . '" target="' . self :: __DEFAULT_TARGET__ . '">');
			$this->_testGetter('getAction()', $this->action, $this->obj->getAction());
			$this->_testGetter('getMethod()', self :: __DEFAULT_METHOD__, $this->obj->getMethod());
			$this->_testGetter('getTarget()', self :: __DEFAULT_TARGET__, $this->obj->getTarget());
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyle() {
			$this->obj->setStyle(parent :: __STYLE_CONTENT__);
			$this->_testCode('<form action="' . $this->action . '" method="' . self :: __DEFAULT_METHOD__ . '" target="' . self :: __DEFAULT_TARGET__ . '" style="' . parent :: __STYLE_CONTENT__ . '">');
			$this->_testGetter('getStyle()', parent :: __STYLE_CONTENT__, $this->obj->getStyle());
		}
		/**
		 * test the set style class method
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass() {
			$this->obj->setStyleClass(parent :: __STYLE_CLASS_NAME__);
			$this->_testCode('<form action="' . $this->action . '" method="' . self :: __DEFAULT_METHOD__ . '" target="' . self :: __DEFAULT_TARGET__ . '" class="' . parent :: __STYLE_CLASS_NAME__ . '">');
		}
		/**
		 * test the set style class method
		 * @access public
		 * @return void
		 */
		public function testSetName() {
			$this->obj->setName($this->fieldname);
			$this->_testCode('<form action="' . $this->action . '" method="' . self :: __DEFAULT_METHOD__ . '" target="' . self :: __DEFAULT_TARGET__ . '" name="' . $this->fieldname . '">');
		}
		/**
		 * test the set method method
		 * @access public
		 * @return void
		 */
		public function testSetMethod() {
			$this->obj->setMethod(self :: __GET__);
			$this->_testCode('<form action="' . $this->action . '" method="' . self :: __GET__ . '" target="' . self :: __DEFAULT_TARGET__ . '">');
		}
		/**
		 * test the set target method
		 * @access public
		 * @return void
		 */
		public function testSetTarget() {
			$this->obj->setTarget(Html_Target_Interface :: _TARG_PARENT_);
			$this->_testCode('<form action="' . $this->action . '" method="' . self :: __DEFAULT_METHOD__ . '" target="' . Html_Target_Interface :: _TARG_PARENT_ . '">');
		}
		/**
		 * test the add js method
		 * @access public
		 * @return void
		 */
		public function testAddJS() {
			$js = new Html_Javascript(Html_Javascript :: __ONSUBMIT__, "alert('ok')");
			$this->obj->addJs($js);
			$this->_testCode('<form action="' . $this->action . '" method="' . self :: __DEFAULT_METHOD__ . '" target="' . self :: __DEFAULT_TARGET__ . '" ' . $js->__toString() . '>');
			unset ($js);
		}
		/**
		* test the add js method with an unexpected js event
		* @access public
		* @return void
		*/
		public function testAddJSUnexpected() {
			$this->obj->addJs(new Html_Javascript(Html_Javascript :: __ONCHANGE__, "alert('ok')"));
			$this->_testCode('<form action="' . $this->action . '" method="' . self :: __DEFAULT_METHOD__ . '" target="' . self :: __DEFAULT_TARGET__ . '">');
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
				$this->_testCode('<form action="' . $this->action . '" method="' . self :: __DEFAULT_METHOD__ . '" target="' . self :: __DEFAULT_TARGET__ . '">');
			}
		}
	}
}