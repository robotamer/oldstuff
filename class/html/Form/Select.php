<?php
/**
 * $Id: Select.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Select')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form select class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Select extends Form_Object implements Html_Design_Interface
    {
        /**
         * @var array $_elts options elts
         * @access private
         */
        private $_elts = array ();
        /**
         * @var boolean $group : does we use group?
         * @access private
         */
        private $_group = false;
        /**
         * builder
         * @param string $name : the select name
         * @access public
         * @return void
         */
        public function __construct($name)
        {
            parent :: __construct();
            $this->setAttribute('name', $name);
        }
        /**
        * return the name attribute value
        * @access public
        * @return string
        */
        public function getName()
        {
            return $this->getStringAttribute('name');
        }
        /**
         * set the style for the radio
         * @access public
         * @final
         * @param string $style : the style value for the radio
         * @return boolean
         */
        public final function setStyle($style = '')
        {
            return $this->setAttribute('style', strval($style));
        }
        /**
        * add a new style
        * @access public
        * @param string $style : the new style to set
        * @return boolean
        */
        public function addStyle($style = '')
        {
            return $this->mergeStyle(strval($style));
        }
        /**
         * get the style
         * @access public
         * @return string
         */
        public function getStyle()
        {
            return $this->getStringAttribute('style');
        }
        /**
         * set the style
         * @access public
         * @final
         * @param string $style : the style value for the radio
         * @return boolean
         */
        public final function setStyleClass($style = '')
        {
            return $this->setAttribute('class', strval($style));
        }
        /**
         * get the style class
         * @access public
         * @return string
         */
        public function getStyleClass()
        {
            return $this->getStringAttribute('class');
        }
        /**
         * set a multiple value for the select boc
         * @param int $size : the expected size
         * @final
         * @access public
         * @return boolean
         */
        public final function setMultiple($size)
        {
            return $this->setMultipleAttribute($size);
        }
        /**
         * add an option
         * @param string $optionName : the option name
         * @param string $optionValue : the option value
         * @param boolean $selected : does the option is selected ?
         * @return boolean
         * @final
         * @access public
         */
        public final function addOption($optionName, $optionValue = '', $selected = false)
        {
            $this->_elts[] = new Form_Option($optionName, $optionValue, $selected);
            return true;
        }
        /**
         * add an option object
         * @see option.class.php
         * @param mixed $option : the option object
         * @return boolean
         * @final
         * @access public
         */
        public final function addOptionObject(Form_Option $option)
        {
            $this->_elts[] = $option;
            return true;
        }
        /**
         * get the options
         * @return array
         * @access public
         */
        public function getOption()
        {
            return $this->_elts;
        }
        /**
         * add an option object
         * @see option.class.php
         * @param mixed $option : the option object
         * @return boolean
         * @final
         * @access public
         */
        public function addOptionGroupObject($groupName, Form_Option $option)
        {
            $is_ok = true;
            $this->_group = true;
            $this->_elts[$groupName][] = $option;
            return $is_ok;
        }
        /**
         * add an option
         * @param string $optionName : the option name
         * @param string $optionValue : the option value
         * @param boolean $selected : does the option is selected ?
         * @return void
         * @final
         * @access public
         */
        function addOptionGroup($groupName, $optionName, $optionValue = '', $selected = false)
        {
            $this->_group = true;
            $this->_elts[$groupName][] = new Form_Option($optionName, $optionValue, $selected);
        }
        /**
         * return the html content
         * @access public
         * @return string
         */
        public function __toString()
        {
            $elements = $this->getSerializedElements();
            $elements = (trim($elements) == '') ? trim($elements) : ' ' . trim($elements);
            $code = '<select' . $elements . '>';
            if ($this->_group) {
                foreach ($this->_elts as $currentLabel => $values) {
                    $code .= '<optgroup label="' . $currentLabel . '">';
                    foreach ($values as $v) {
                        $code .= $v->__toString();
                    }
                    $code .= '</optgroup>';
                }
            }
            else {
                foreach ($this->_elts as $v) {
                    $code .= $v->__toString();
                }
            }
            $code .= '</select>';
            return $code;
        }
    }
}