<?php
/**
 * $Id: Textarea.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Textarea')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form textarea class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Textarea extends Form_Object implements Html_Design_Interface
    {
        /**
         * @var string $_value : the textarea value
         * @access private
         */
        private $_value = '';
        /**
         * builder
         * @return void
         * @param string $name : the textField name
         * @access public
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
         * set the value for the radio
         * @access public
         * @final
         * @param string $value : the value of the radio
         * @return boolean
         */
        public final function setValue($value = '')
        {
            $this->_value = $value;
            return true;
        }
        /**
         * get the value
         * @access public
         * @return string
         */
        public function getValue()
        {
            return $this->getStringAttribute('value');
        }
        /**
         * set the field length
         * @access public
         * @final
         * @param string $rows
         * @return boolean
         */
        public final function setRows($rows = 10)
        {
            return $this->setAttribute('rows', $rows);
        }
        /**
         * get the rows number
         * @access public
         * @return int
         */
        public function getRows()
        {
            return $this->getIntAttribute('rows');
        }
        /**
         * set the field max length
         * @access public
         * @final
         * @param int $cols
         * @return boolean
         */
        public final function setCols($cols = 20)
        {
            return $this->setAttribute('cols', $cols);
        }
        /**
        * set the field max length
        * @access public
        * @param string $cols
        * @return void
        */
        public function getCols()
        {
            return $this->getIntAttribute('cols');
        }
        /**
         * return the html content
         * @access public
         * @return string
         */
        public function __toString()
        {
            return '<textarea ' . $this->getSerializedElements() . '>' . $this->_value . '</textarea>';
        }
    }
}