<?php

/**
 * $Id: Picture.php 1384 2007-04-06 21:02:59Z matthieu $
 */
if (!class_exists('Test_Html_Picture')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * Test class for Html_Picture class
	 * @author Matthieu MARY <matthieu@phplibrairies.com>
	 * @since $Date$
	 * @package html
	 * @subpackage unit_test_case
	 */
	class Test_Html_Picture extends Test_Html_Template implements Test_Html_Design_Interface {
		/**
		 * constructor
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent :: __construct();
		}
		public function setUp() {
			$this->initObject(parent :: __DEFAULT_PICT_PATH__);
		}
		/**
		 * initialise the object
		 * @param string $picturePath : mandatory parameter
		 * @access private
		 * @return void
		 */
		private function initObject($picturePath) {
			$this->obj = new Html_Picture($picturePath);
		}
		/**
		 * test the toString method
		 * @access public
		 * @return void
		 */
		public function testDefault() {
			$this->_testCode('<img src="' . parent :: __DEFAULT_PICT_PATH__ . '"/>');
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyle() {
			$this->obj->setStyle(parent :: __STYLE_CONTENT__);
			$this->_testCode('<img src="' . parent :: __DEFAULT_PICT_PATH__ . '" style="' . parent :: __STYLE_CONTENT__ . '"/>');
			$this->_testGetter('getStyle()', parent :: __STYLE_CONTENT__, $this->obj->getStyle());
		}
		/**
		 * test the set style method
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass() {
			$this->obj->setStyleClass(parent :: __STYLE_CLASS_NAME__);
			$this->_testCode('<img src="' . parent :: __DEFAULT_PICT_PATH__ . '" class="' . parent :: __STYLE_CLASS_NAME__ . '"/>');
			$this->_testGetter('getStyleClass()', parent :: __STYLE_CLASS_NAME__, $this->obj->getStyleClass());
		}
		/**
		* test the set style method
		* @access public
		* @return void
		*/
		public function testAddJS() {
			$js = new Html_Javascript(Html_Javascript :: __ONKEYUP__, "alert('ok')");
			$this->obj->addJS($js);
			$this->_testCode('<img src="' . parent :: __DEFAULT_PICT_PATH__ . '" ' . $js->__toString() . '/>');
			unset ($js);
		}
		/**
		* test the set style method for un unexpected event
		* @access public
		* @return void
		*/
		public function testAddJSUnexpected() {
			$this->obj->addJS(new Html_Javascript(Html_Javascript :: __ONCLICK__, "alert('ok')"));
			$this->_testCode('<img src="' . parent :: __DEFAULT_PICT_PATH__ . '"/>');
		}
	}
}