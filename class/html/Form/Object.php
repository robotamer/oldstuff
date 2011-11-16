<?php
/**
 * $Id: Object.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Object')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form object class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Object extends Html_Element implements Html_Interface
    {
        /**
         * disabled value
         */
        const __DISABLED_VALUE__ = 'disabled';
        /**
         * @var boolean $_isDisabled : does the object is disabled?
         * @access private
         */
        private $_isDisabled = false;
        /**
         * constructor
         * @access public
         * @return void
         */
        public function __construct()
        {
            parent :: __construct();
        }
        /**
        * set disabled flag
        * @acess public
        * @return void
        * @throws Exception
        */
        public function setDisabled($disabledState = true)
        {
            $isOk = false;
            if (in_array(get_class($this), array (
                    'Form_Button',
                    'Form_Checkbox',
                    'Form_Files',
                    'Form_Password',
                    'Form_Radio',
                    'Form_Reset',
                    'Form_Select',
                    'Form_Submit',
                    'Form_Text',
                    'Form_Textarea'
                )))
            {
                $this->_isDisabled = $disabledState;
                $isOk = true;
            } else {
                throw new Exception('This element cannot be disabled');
            }
            return $isOk;
        }
        /**
         * does object is disabled?
         * @access public
         * @return boolean
         */
        public function isDisabled()
        {
            return $this->_isDisabled;
        }
        /**
         * return the html output for the form object
         * @access protected
         * @return string
         */
        protected function getSerializedElements()
        {
            $elts = $this->serializeAttributes();
            if ($this->_isDisabled) {
                $elts .= ' ' . self :: __DISABLED_VALUE__;
            }
            return $elts;
        }
        /**
        * set multiple tag
        * @param string $size
        * @access protected
        * @return void
        */
        protected function setMultipleAttribute($size)
        {
            $isOk = false;
            if ($this instanceof Form_Select) {
                if ((is_int($size) && ($size > 0)) || (is_bool($size) && ($size == true))) {
                    $isOk = $this->setAttribute('multiple', 'multiple');
                }
            }
            return $isOk;
        }
        /**
         * does the multiple tag is set?
         * @access public
         * @return boolean
         * @throws Exception
         */
        public function isMultiple()
        {
            $returnValue = false;
            if (!($this instanceof Form_Select)) {
                throw new Exception('Form_Object :: isMultiple can be call only on FormSelect instance');
            }
            else {
                $returnValue = ($this->getStringAttribute('multiple') == 'multiple');
            }
            return $returnValue;
        }
        /**
         * set selected tag
         * @param boolean $selected
         * @access protected
         * @return boolean
         */
        protected function setSelectedAttribute($selected = false)
        {
            $isOk = false;
            if ($this instanceof Form_Option) {
                if (is_bool($selected) && ($selected == true)) {
                    $isOk = $this->setAttribute('selected', 'selected');
                }
            }
            return $isOk;
        }
        /**
         * does the element is selected?
         * @return boolean
         * @access public
         * @throws Exception
         */
        public function isSelected()
        {
            $return_value = false;
            if (!($this instanceof Form_Option))
            {
                throw new Exception('Form_Object :: isSelected can be call only on FormOption instance');
            } else {
                $return_value = ($this->getStringAttribute('selected') == 'selected');
            }
            return $return_value;
        }
        /**
         * set checked tag
         * @param boolean $checked
         * @access protected
         * @return boolean
         */
        protected function setCheckedAttribute($checked = false)
        {
            $isOk = false;
            if (in_array(get_class($this), array (
                    'Form_Checkbox',
                    'Form_Radio'
                )))
            {
                if (is_bool($checked) && ($checked == true)) {
                    $isOk = $this->setAttribute('checked', 'checked');
                }
            }
            return $isOk;
        }
        /**
         * does the element is checked?
         * @access public
         * @return boolean
         * @throws Exception
         */
        public function isChecked()
        {
            $isOk = false;
            if (!in_array(get_class($this), array (
                    'Form_Checkbox',
                    'Form_Radio'
                )))
            {
                throw new Exception('Form_Object :: isChecked can be call only on FormCheckbox or FormRadio instance');
            }
            else {
                $return_value = ($this->getStringAttribute('checked') == 'checked');
            }
            return $isOk;
        }
    }
}