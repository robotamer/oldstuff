<?php
/**
 * $Id: Div.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Html_Div')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public
     * License
     * @package html
     */
    class Html_Div extends Html_Element implements Html_Design_Interface
    {
        /**
         * @access protected
         * @var string $_content : the div content
         */
        protected $_content = '&nbsp;';
        /**
         * @acces public
         * @return void
         */
        public function __construct()
        {
            parent :: __construct();
        }
        /**
         * set the div content
         * @acces public
         * @param mixed $content : espected string or Html_HTMLElement
         */
        public function setContent($content)
        {
            $this->_content = ($content instanceof Html_Element) ? $content->__toString() : $content;
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
         * serialise object
         * @access public
         * @return string
         */
        public function __toString()
        {
            $elements = $this->serializeAttributes();
            $elements = (trim($elements) == '') ? trim($elements) : ' ' . trim($elements);
            return '<div' . $elements . '>' . $this->_content . '</div>';
        }
    }
}