<?php
/**
 * $Id: Files.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Files')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form file class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Files extends Form_Object implements Html_Design_Interface
    {
        /**
         * constructor
         * @param string $name : the password field name
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
         * @param string $style : the style value
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
         * return the html content
         * @access public
         * @return string
         */
        public function __toString()
        {
            $elements = $this->getSerializedElements();
            $elements = (trim($elements) == '') ? trim($elements) : ' ' . trim($elements);
            return '<input type="file"' . $elements . '/>';
        }
    }
}