<?php
/**
 * $Id: Element.php 1828 2007-08-25 12:38:16Z matthieu $
 */
if (!class_exists('Html_Element')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * html object class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Html_Element implements Html_Interface
    {
        /**
         * @var array $_attributes : the formObject's attributes
         * @access private
         */
        private $_attributes = array ();
        /**
         * @var array $_js : the javascript objects
         * @access private
         */
        private $_js = array ();
        /**
         * constructor
         * @access public
         * @return void
         */
        public function __construct()
        {
        }
        /**
         * set an attribute
         * @param string $attributeName : the attribute name
         * @param mixed $attributeValue : the attribute value
         * @param boolean $allowedEmptyValues : can we set empty values?
         * @return boolean
         * @throws Exception
         */
        protected function setAttribute($attributeName, $attributeValue, $allowedEmptyValues = false)
        {
            $isOk = false;
            if (!is_scalar($attributeValue)) {
                throw new Exception('Only scalar values can be pass to ' . get_class($this) . '::setAttribute');
            } else {
                if ($allowedEmptyValues || !empty ($attributeValue)) {
                    $isOk = true;
                    $this->_attributes[$attributeName] = $attributeValue;
                }
            }
            return $isOk;
        }
        /**
         * get a string attribute
         * @param string $attibute : the attribute name
         * @return string
         * @final
         */
        protected final function getStringAttribute($attribute)
        {
            $returnValue = '';
            if (isset ($this->_attributes[$attribute])) {
                $returnValue = strval($this->_attributes[$attribute]);
            }
            return $returnValue;
        }
        /**
        * get a numeric attribute
        * @param string $attibute : the attribute name
        * @return string
        * @final
        * @access protected
        */
        protected final function getIntAttribute($attribute)
        {
            $returnValue = 0;
            if (isset ($this->_attributes[$attribute])) {
                $returnValue = intval($this->_attributes[$attribute]);
            }
            return $returnValue;
        }
        /**
         * get the html code for the attributes
         * @access protected
         * @return string
         */
        protected function serializeAttributes()
        {
            $elts = '';
            foreach ($this->_attributes as $attribute => $value) {
                $elts .= $attribute . '="' . $value . '" ';
            }
            foreach ($this->_js as $jsObject) {
                if ($jsObject->isValid($this)) {
                    $elts .= $jsObject->__toString() . ' ';
                }
            }
            return trim($elts);
        }
        /**
        * merge specified parameter style to style tag already seted
        * @access protected
        * @return boolean
        */
        protected function mergeStyle($style)
        {
            $styles = explode(';', $style);
            $returnValue = (is_array($styles) && (count($styles) > 0));
            foreach ($styles as $current_style) {
                $this->mergeOneStyle($current_style);
            }
            return $returnValue;
        }
        /**
         * merge one style to the current style setted
         * @access private
         * @return boolean
         * @param string $style the style to add (key: value)
         */
        private function mergeOneStyle($style)
        {
            $currentSettedStyle = $this->getStyle();
            $values = explode(':', $style);
            $returnValue = false;
            if (count($values) == 2) {
                $returnValue = true;
                $tagName = trim($values[0]);
                $tagPos = strpos($currentSettedStyle, $tagName);
                $new_style = '';
                if (is_int($tagPos)) {
                    $old_tag = substr($currentSettedStyle, $tagPos, strpos($currentSettedStyle, ';') - 1);
                    // tag already exists...
                    $new_style = str_replace($old_tag, $style, $currentSettedStyle);
                }
                else {
                    $new_style = (!empty($currentSettedStyle) ? $currentSettedStyle . '; ' . $style : $style);
                }
                $this->setStyle($new_style);
                unset ($new_style);
                unset ($tagName);
                unset ($values);
                unset ($currentSettedStyle);
            }
            return $returnValue;
        }
        /**
         * add a javascript object
         * @access protected
         * @param mixed $_jsObject : the javascript object
         * @see javascript.class.php
         * @return boolean
         */
        public function addJS(Html_Javascript $jsObject)
        {
            $returnValue = false;
            if ($jsObject->isValid($this)) {
                $this->_js[] = $jsObject;
                $returnValue = true;
            }
            return $returnValue;
        }
        /**
         * get the javascript object
         * @access protected
         * @return array
         */
        public function getJS()
        {
            return $this->_js;
        }
        /**
        * return the id
        * @return string
        * @access public
        */
        public function getId()
        {
            return $this->getStringAttribute('id');
        }
        /**
         * set the id
         * @param string $id
         * @access public
         * @return boolean
         */
        public function setId($id)
        {
            return $this->setAttribute('id', $id);
        }
    }
}