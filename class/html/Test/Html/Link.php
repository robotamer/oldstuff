<?php
/**
 * $Id: Link.php 1384 2007-04-06 21:02:59Z matthieu $
 */
if (!class_exists('Test_Html_Link')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * Test class for Html_Link class
	 * @author Matthieu MARY <matthieu@phplibrairies.com>
	 * @since $Date$
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Html_Link extends Test_Html_Template implements Test_Html_Design_Interface, Test_Html_Target_Interface {
		/**
		 * @var string $href the href tested value
		 * @access private
		 */
		const __HREF__ = "#";
		/**
		 * the expected title value
		 */
		const __EXPECTED_TITLE__ = "this is my expected title";
		/**
		 * the expected closed lavel
		 */
		const __CLOSE_LABEL__ = "click here";
		/**
		 * constructor
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent :: __construct();
		}
		/**
		 * reinit the Link object
		 * @access public
		 * @return void
		 */
		public function setUp() {
			$this->obj = new Html_Link(self :: __HREF__);
		}
		/**
		* test the set style method
		* @access public
		* @return void
		*/
		public function testDefault() {
			$this->_testCode('<a href="' . self :: __HREF__ . '">');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetTitle() {
			$this->obj->setTitle(self :: __EXPECTED_TITLE__);
			$this->_testCode('<a href="' . self :: __HREF__ . '" title="' . self :: __EXPECTED_TITLE__ . '">');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyle() {
			$this->obj->setStyle(parent :: __STYLE_CONTENT__);
			$this->_testCode('<a href="' . self :: __HREF__ . '" style="' . parent :: __STYLE_CONTENT__ . '">');
            $this->_testGetter('getStyle()', parent :: __STYLE_CONTENT__, $this->obj->getStyle());
		}
		/**
		 * test the set style class method
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass() {
			$this->obj->setStyleClass(parent :: __STYLE_CLASS_NAME__);
			$this->_testCode('<a href="' . self :: __HREF__ . '" class="' . parent :: __STYLE_CLASS_NAME__ . '">');
            $this->_testGetter('getStyleClass()', parent :: __STYLE_CLASS_NAME__, $this->obj->getStyleClass());
		}
		/**
		* test the set target method
		* @access public
		* @return void
		*/
		public function testSetTarget() {
			$this->obj->setTarget(Html_Target_Interface :: _TARG_PARENT_);
			$this->_testCode('<a href="' . self :: __HREF__ . '" target="' . Html_Target_Interface :: _TARG_PARENT_ . '">');
		}
		/**
		* test the set target method
		* @access public
		* @return void
		*/
		public function testClose() {
			$return = $this->obj->close(self :: __CLOSE_LABEL__);
			$expectedValue = '<a href="' . self :: __HREF__ . '">' . self :: __CLOSE_LABEL__ . '</a>';
			$this->assertTrue(($return != ''), 'toString return an empty value');
			$this->assertTrue((strcasecmp($return, $expectedValue) == 0), "unexpected return value for toString: found $return, expected $expectedValue");
		}
	}
}