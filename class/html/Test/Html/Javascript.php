<?php

/**
 * $Id: Javascript.php 1384 2007-04-06 21:02:59Z matthieu $
 */
if (!class_exists('Test_Html_Javascript')) {
	if (!defined('__CLASS_PATH__')) {
		define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../../'));
	}
	require_once __CLASS_PATH__ . '/Autoload.php';
	/**
	 * unit test case for Html_Javascript class
	 * @author Matthieu MARY <matthieu@phplibrairies.com>
	 * @package html
	 * @subpackage unit_test_case
	 */
	class Test_Html_Javascript extends Test_Html_Template {
		/**
		 * @var mixed $js : the js object tested
		 * @access private
		 * @see Javascript.class.php
		 */
		private $js = null;
		/**
		 * constructor
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent :: __construct();
		}
		/**
		 * test if the code generated is well formed
		 * @acces private
		 * @return void
		 */
		private function _testCodeGenerated() {
			$html_code = $this->js->__toString();
			$this->assertFalse(empty ($html_code), 'Code returned is empty');
			$this->assertTrue(is_int(strpos($html_code, 'javascript')), 'Error while testing js html code generated. Cannot found javascript label');
			unset ($html_code);
		}
		/**
		 * test validity for form objects
		 * @access public
		 * @return void
		 */
		public function testForForm() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONRESET__,
				Html_Javascript :: __ONSUBMIT__
			));
			$form = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form = new Form_Form(basename($_SERVER['PHP_SELF']));
				$form->addJS($this->js);
				$expected_html_code = '<form action="' . basename($_SERVER['PHP_SELF']) . '" method="post" target="_self"' . $js_expected_code . '>';
				$this->testGetterAnswer($form->__toString(), $expected_html_code);
				unset ($form);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		 * test validity for form button objects
		 * @access public
		 * @return void
		 */
		public function testForButton() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCLICK__,
				Html_Javascript :: __ONFOCUS__,
				Html_Javascript :: __ONMOUSEDOWN__,
				Html_Javascript :: __ONMOUSEUP__
			));
			$form_button = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_button = new Form_Button();
				$form_button->addJS($this->js);
				$expected_html_code = '<input type="button"' . $js_expected_code . '/>';
				$this->testGetterAnswer($form_button->__toString(), $expected_html_code);
				unset ($form_button);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form checkbox objects
		* @access public
		* @return void
		*/
		public function testForCheckbox() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCLICK__,
				Html_Javascript :: __ONFOCUS__
			));
			$form_checkbox = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_checkbox = new Form_Checkbox('temp');
				$form_checkbox->addJS($this->js);
				$expected_html_code = '<input type="checkbox" name="temp"' . $js_expected_code . '/>';
				$this->testGetterAnswer($form_checkbox->__toString(), $expected_html_code);
				unset ($form_checkbox);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form files objects
		* @access public
		* @return void
		*/
		public function testForFiles() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCHANGE__,
				Html_Javascript :: __ONFOCUS__
			));
			$form_files = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_files = new Form_Files('temp');
				$form_files->addJS($this->js);
				$expected_html_code = '<input type="file" name="temp"' . $js_expected_code . '/>';
				$this->testGetterAnswer($form_files->__toString(), $expected_html_code);
				unset ($form_files);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form options objects
		* @access public
		* @return void
		*/
		public function testForPassword() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONFOCUS__
			));
			$form_password = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_password = new Form_Password('temp');
				$form_password->addJS($this->js);
				$expected_html_code = '<input type="password" name="temp"' . $js_expected_code . '/>';
				$this->testGetterAnswer($form_password->__toString(), $expected_html_code);
				unset ($form_password);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form radio objects
		* @access public
		* @return void
		*/
		public function testForRadio() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCLICK__,
				Html_Javascript :: __ONFOCUS__
			));
			$form_radio = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_radio = new Form_Radio('temp');
				$form_radio->addJS($this->js);
				$expected_html_code = '<input type="radio" name="temp"' . $js_expected_code . '/>';
				$this->testGetterAnswer($form_radio->__toString(), $expected_html_code);
				unset ($form_radio);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form reset objects
		* @access public
		* @return void
		*/
		public function testForReset() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCLICK__,
				Html_Javascript :: __ONFOCUS__
			));
			$form_reset = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_reset = new Form_Reset();
				$form_reset->addJS($this->js);
				$expected_html_code = '<input type="reset"' . $js_expected_code . '/>';
				$this->testGetterAnswer($form_reset->__toString(), $expected_html_code);
				unset ($form_reset);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form select objects
		* @access public
		* @return void
		*/
		public function testForSelect() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCHANGE__,
				Html_Javascript :: __ONCLICK__,
				Html_Javascript :: __ONFOCUS__
			));
			$form_select = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_select = new Form_Select('test');
				$form_select->addJS($this->js);
				$expected_html_code = '<select name="test"' . $js_expected_code . '></select>';
				$this->testGetterAnswer($form_select->__toString(), $expected_html_code);
				unset ($form_select);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form submit objects
		* @access public
		* @return void
		*/
		public function testForSubmit() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCHANGE__,
				Html_Javascript :: __ONCLICK__,
				Html_Javascript :: __ONFOCUS__
			));
			$form_submit = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_submit = new Form_Submit();
				$form_submit->addJS($this->js);
				$expected_html_code = '<input type="submit"' . $js_expected_code . '/>';
				$this->testGetterAnswer($form_submit->__toString(), $expected_html_code);
				unset ($form_submit);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form submit objects
		* @access public
		* @return void
		*/
		public function testForText() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCHANGE__,
				Html_Javascript :: __ONFOCUS__,
				Html_Javascript :: __ONSELECT__
			));
			$form_text = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_text = new Form_Text('test');
				$form_text->addJS($this->js);
				$expected_html_code = '<input type="text" name="test"' . $js_expected_code . '/>';
				$this->testGetterAnswer($form_text->__toString(), $expected_html_code);
				unset ($form_text);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		* test validity for form submit objects
		* @access public
		* @return void
		*/
		public function testForTextarea() {
			$answer = $this->getExpectedAnswer(false, array (
				Html_Javascript :: __ONBLUR__,
				Html_Javascript :: __ONCHANGE__,
				Html_Javascript :: __ONFOCUS__,
				Html_Javascript :: __ONKEYDOWN__,
				Html_Javascript :: __ONKEYUP__,
				Html_Javascript :: __ONKEYPRESS__,
				Html_Javascript :: __ONSELECT__
			));
			$form_textarea = null;
			$js_expected_code = '';
			foreach ($answer as $current_event => $is_expected) {
				$this->js = new Html_Javascript($current_event, "alert('ok');");
				$js_expected_code = $is_expected ? ' ' . $this->js->__toString() : '';
				$form_textarea = new Form_Textarea('test');
				$form_textarea->addJS($this->js);
				$expected_html_code = '<textarea name="test"' . $js_expected_code . '></textarea>';
				$this->testGetterAnswer($form_textarea->__toString(), $expected_html_code);
				unset ($form_textarea);
			}
			unset ($js_expected_code);
			unset ($answer);
		}
		/**
		 * return an array with flag for each javascript event
		 * by default, all events are flag to expected (true).
		 * @param boolean $default_flag : the default flag
		 * @param array $not_expected_fields : array of flags labels that are not expected for one object
		 * @return array
		 */
		private function getExpectedAnswer($default_flag = true, $not_expected_fields = array ()) {
			$expected_values = array (
				Html_Javascript :: __ONABORT__ => $default_flag,
				Html_Javascript :: __ONBLUR__ => $default_flag,
				Html_Javascript :: __ONCLICK__ => $default_flag,
				Html_Javascript :: __ONCHANGE__ => $default_flag,
				Html_Javascript :: __ONDOUBLECLICK__ => $default_flag,
				Html_Javascript :: __ONDRAGANDDROP__ => $default_flag,
				Html_Javascript :: __ONERROR__ => $default_flag,
				Html_Javascript :: __ONFOCUS__ => $default_flag,
				Html_Javascript :: __ONKEYDOWN__ => $default_flag,
				Html_Javascript :: __ONKEYPRESS__ => $default_flag,
				Html_Javascript :: __ONKEYUP__ => $default_flag,
				Html_Javascript :: __ONMOUSEOVER__ => $default_flag,
				Html_Javascript :: __ONMOUSEOUT__ => $default_flag,
				Html_Javascript :: __ONRESET__ => $default_flag,
				Html_Javascript :: __ONRESIZE__ => $default_flag,
				Html_Javascript :: __ONSUBMIT__ => $default_flag,
				Html_Javascript :: __ONUNLOAD__ => $default_flag,
				Html_Javascript :: __ONLOAD__ => $default_flag
			);
			foreach ($not_expected_fields as $field_to_set_to_unexpected) {
				$expected_values[$field_to_set_to_unexpected] = !$default_flag;
			}
			unset ($field_to_set_to_unexpected);
			return $expected_values;
		}
	}
}