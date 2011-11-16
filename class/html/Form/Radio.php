<?php
/**
 * $Id: Radio.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Radio')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form radio class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Radio extends Form_Object implements Html_Design_Interface
    {
        /**
         * builder
         * @param string $name : name of the radio
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
         * set the value for the radio
         * @access public
         * @final
         * @param string $value : the value of the radio
         * @return boolean
         */
        public final function setValue($value = '')
        {
            return $this->setAttribute('value', $value, true);
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
         * set the style
         * @access public
         * @final
         * @param string $class : the class value for the radio
         * @return boolean
         */
        public final function setStyleClass($class = '')
        {
            return $this->setAttribute('class', strval($class));
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
         * set the checked value
         * @access public
         * @return boolean
         */
        public final function setChecked($checkState = true)
        {
            return $this->setCheckedAttribute($checkState);
        }
        /**
         * return the html content for the radio button
         * @access public
         * @return string
         */
        public function __toString()
        {
            return '<input type="radio" ' . $this->getSerializedElements() . '/>';
        }
    }
}