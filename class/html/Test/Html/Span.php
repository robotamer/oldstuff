<?php
/**
 * $Id: Link.php 1384 2007-04-06 21:02:59Z matthieu $
 */
if (!class_exists('Test_Html_Span')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * Test class for Html_Div class
	 * @author Matthieu MARY <matthieu@phplibrairies.com>
	 * @since $Date$
	 * @package html
     * @subpackage unit_test_case
	 */
	class Test_Html_Span extends Test_Html_Template implements Test_Html_Design_Interface {
		/**
		 * the expected closed lavel
		 */
		const __CONTENT_STRING__ = "my content";

		const __DEFAULT_CONTENT__ = '&nbsp;';
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
			$this->obj = new Html_Span();
		}
		/**
		* test the default output
		* @access public
		* @return void
		*/
		public function testDefault() {
			$this->_testCode('<span>'.self :: __DEFAULT_CONTENT__.'</span>');
		}
		/**
		 * test the set content method with a string parameter
		 * @access public
		 * @return void
		 */
		public function testContentWithString() {
			$this->obj->setContent(self :: __CONTENT_STRING__);
			$this->_testCode('<span>'.self :: __CONTENT_STRING__.'</span>');
		}
		/**
		 * test the set content method with an Html_Object
		 * @access public
		 * @return void
		 */
		public function testContentHtmlObject() {
			$button = new Form_Button();
			$this->obj->setContent($button);
			$this->_testCode('<span>'.$button->__toString().'</span>');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyle() {
			$this->obj->setStyle(parent :: __STYLE_CONTENT__);
			$this->_testCode('<span style="' . parent :: __STYLE_CONTENT__ . '">'.self :: __DEFAULT_CONTENT__.'</span>');
            $this->_testGetter('getStyle()', parent :: __STYLE_CONTENT__, $this->obj->getStyle());
		}
		/**
		 * test the set style class method
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass() {
			$this->obj->setStyleClass(parent :: __STYLE_CLASS_NAME__);
			$this->_testCode('<span class="' . parent :: __STYLE_CLASS_NAME__ . '">'.self :: __DEFAULT_CONTENT__.'</span>');
            $this->_testGetter('getStyleClass()', parent :: __STYLE_CLASS_NAME__, $this->obj->getStyleClass());
		}
	}
}