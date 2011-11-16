<?php
/**
 * $Id:Interface.php 1383 2007-04-06 20:56:27Z matthieu $
 */
if (!interface_exists('Test_Html_Target_Interface')) {
	/**
	 * target html interface for the simpletest unit test case
	 * @author Matthieu MARY <matthieu@phplibrairies.com>
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 * @package html
	 * @subpackage unit_test_case
	 */
	interface Test_Html_Target_Interface {
		/**
		 * test the set target method
		 * @return void
		 */
		public function testSetTarget();
	}
}