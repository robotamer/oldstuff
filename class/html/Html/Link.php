<?php
/**
 * $Id: Link.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Html_Link')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * link object
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Html_Link extends Html_Element implements Html_Target_Interface, Html_Design_Interface, HTML_Interface
    {
        /**
         * constructor
         * @param string $href : the link target
         * @return void
         */
        public function __construct($href)
        {
            parent :: __construct();
            $this->setAttribute('href', $href);
        }
        /**
         * set the link target
         * @access public
         * @return boolean
         * @param string $target : the link target
         */
        public function setTarget($target)
        {
            return $this->setAttribute('target', $target);
        }
        /**
         * get the link target
         * @access public
         * @return string
         */
        public function getTarget()
        {
            return $this->getStringAttribute('target');
        }
        /**
         * get the href location
         * @return string
         * @access public
         */
        public function getHref()
        {
            return $this->getStringAttribute('href');
        }
        /**
         * set the design class
         * @access public
         * @param string $style
         * @return boolean
         */
        public function setStyleClass($style = '')
        {
            return $this->setAttribute('class', strval($style));
        }
        /**
        * get the class style
        * @access public
        * @return void
        */
        public function getStyleClass()
        {
            return $this->getStringAttribute('class');
        }
        /**
        * set the style
        * @access public
        * @param string $style : the style
        * @return boolean
        */
        public function setStyle($style = '')
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
        * set the style
        * @access public
        * @param string $style : the style
        * @return void
        */
        public function getStyle()
        {
            return $this->getStringAttribute('style');
        }
        /**
         * set the link title
         * @param string $title: the title to set
         * @access public
         * @return boolean
         */
        public function setTitle($title)
        {
            return $this->setAttribute('title', $title);
        }
        /**
         * get the link title
         * @access public
         * @return string
         */
        public function getTitle()
        {
            return $this->getStringAttribute('title');
        }
        /**
         * get the html code
         * @access public
         * @return string
         */
        public function __toString()
        {
            return '<a ' . $this->serializeAttributes() . '>';
        }
        /**
         * close the link
         * @param string $label : the link label
         * @return string
         */
        public function close($mix)
        {
            $label = $mix;
            if (($mix instanceof Html_Element) && method_exists($mix, '__toString')) {
                $label = $mix->__toString();
            }
            return $this->__toString() . $label . '</a>';
        }
    }
}