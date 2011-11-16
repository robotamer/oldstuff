<?php
/**
 * $Id: Picture.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Html_Picture')) {
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
    class Html_Picture extends Html_Element implements Html_Design_Interface, HTML_Interface {
        /**
         * constructor
         * @access public
         * @param string $picture_path : path to the picture
         * @return void
         */
        public function __construct($picture_path) {
            parent :: __construct();
            $this->setAttribute('src', $picture_path);
        }
        /**
         * get the html code
         * @access public
         * @return string
         */
        public function __toString() {
            return '<img ' . $this->serializeAttributes() . '/>';
        }
        /**
         * get the src attribute value
         * @access public
         * @return string
         */
        public function getSrc() {
            return $this->getStringAttribute('src');
        }
        /**
        * set the design class
        * @param string $style : class style to set
        * @access public
        * @return boolean
        */
        public function setStyleClass($style = '') {
            return $this->setAttribute('class', strval($style));
        }
        /**
        * get the class style
        * @access public
        * @return void
        */
        public function getStyleClass() {
            return $this->getStringAttribute('class');
        }
        /**
        * set the style
        * @access public
        * @param string $style : the style
        * @return boolean
        */
        public function setStyle($style = '') {
            return $this->setAttribute('style', strval($style));
        }
        /**
        * add a new style
        * @access public
        * @param string $style : the new style to set
        * @return boolean
        */
        public function addStyle($style = '') {
            return $this->mergeStyle(strval($style));
        }
        /**
        * set the style
        * @access public
        * @param string $style : the style
        * @return void
        */
        public function getStyle() {
            return $this->getStringAttribute('style');
        }
        /**
             * set the alt value
        * @var string $alt : the alt value
        * @access public
        * @return boolean
        */
        public function setAlt($alt = '') {
            return $this->setAttribute('alt', $alt);
        }
        /**
             * get the alt value
         * @return string
         * @access public
         */
        public function getAlt() {
            return $this->getStringAttribute('alt');
        }
    }
}