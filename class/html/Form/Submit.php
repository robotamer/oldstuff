<?php
/**
 * $Id: Submit.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Submit')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form submit class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Submit extends Form_Object implements Html_Design_Interface
    {
        /**
         * @var boolean $_img : is it an input type image?
         * @access private
         */
        private $_img = false;
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
         * @return string
         * @access public
         */
        public function getStyle()
        {
            return $this->getStringAttribute('style');
        }
        /**
         * set the style
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
         * set the picture url
         * @access public
         * @final
         * @param string $url : the picture url
         * @return boolean
         */
        public final function setImgUrl($url)
        {
            $this->_img = true;
            return $this->setAttribute('src', $url);
        }
        /**
         * get the src
         * @access public
         * @return string
         */
        public function getImgUrl()
        {
            return $this->getStringAttribute('src');
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
            return '<input type="' . ($this->_img ? 'image' : 'submit') . '"' . $elements . '/>';
        }
    }
}