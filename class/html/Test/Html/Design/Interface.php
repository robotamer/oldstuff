<?php
/**
 * $Id:Interface.php 1383 2007-04-06 20:56:27Z matthieu $
 */
if (!interface_exists('Test_Html_Design_Interface')) {
	/**
	 * form interface Element for simpletest unit test case
	 * @author Matthieu MARY <matthieu@phplibrairies.com>
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 * @package html
	 * @subpackage unit_test_case
	 */
	interface Test_Html_Design_Interface {
		/**
		 * set the style
		 * @access public
		 * @param string $style : the style
		 * @return void
		 */
		public function testSetStyle();
		/**
		 * get the class style
		 * @access public
		 * @return void
		 */
		public function testSetStyleClass();
	}
}