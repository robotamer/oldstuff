<?php
/**
 * $Id: Checkbox.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Checkbox')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form checkbox class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Checkbox extends Form_Object implements Html_Design_Interface
    {
        /**
         * builder
         * @access public
         * @return void
         * @param string $name : the checkbox name
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
         * set the style
         * @access public
         * @final
         * @param string $style : the style value
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
         * set the style class
         * @access public
         * @final
         * @param string $style : the style
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
        * set the selected option
        * @access public
        * @final
        * @param boolean $selected : does the option checked?
        * @return boolean
        */
        public final function setChecked($selected = false)
        {
            return $this->setCheckedAttribute($selected);
        }
        /**
         * set the value
         * @access public
         * @final
         * @param string $value : the value
         * @return void
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
        * return the html content
        * @access public
        * @return string
        */
        public function __toString()
        {
            return '<input type="checkbox" ' . $this->getSerializedElements() . '/>';
        }
    }
}