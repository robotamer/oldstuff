<?php
/**
 * $Id: Reset.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Reset')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form reset class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Reset extends Form_Object implements Html_Design_Interface
    {
        /**
         * builder
         * @return void
         * @access public
         */
        public function __construct()
        {
            parent :: __construct();
        }
        /**
         * set the style
         * @access public
         * @final
         * @param string $style : the style
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
        * set name tag
        * @param string $name
        * @access public
        * @final
        * @return boolean
        */
        public final function setName($name = '')
        {
            return $this->setAttribute('name', $name);
        }
        /**
        * get the name
        * @access public
        * @return string
        */
        public function getName()
        {
            return $this->getStringAttribute('name');
        }
        /**
         * set the value
         * @access public
         * @final
         * @param string $value : the value
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
         * return the html content
         * @access public
         * @return string
         */
        public function __toString()
        {
            $elts = trim($this->getSerializedElements());
            if (!empty ($elts)) {
                $elts = ' ' . $elts;
            }
            return '<input type="reset"' . $elts . '/>';
        }
    }
}