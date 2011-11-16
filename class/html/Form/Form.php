<?php
/**
 * $Id: Form.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Form')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Form extends Form_Object implements Html_Target_Interface, Html_Design_Interface
    {
        const _ENC_MULTIPART_ = 'multipart/form-data';
        const _METHOD_POST_ = 'post';
        const _METHOD_GET_ = 'get';
        /**
         * constructor
         * @param string $action : the form action
         * @access public
         * @return void
         */
        public function __construct($action)
        {
            parent :: __construct();
            $this->setAttribute('action', $action, true);
            $this->setAttribute('method', self :: _METHOD_POST_);
            $this->setAttribute('target', self :: _TARG_SELF_);
        }
        /**
         * return the action
         * @return string
         * @access public
         */
        public function getAction()
        {
            return $this->getStringAttribute('action');
        }
        /**
         * set the form method
         * @param string $method : the form method
         * @access public
         * @final
         * @return boolean
         */
        public final function setMethod($method)
        {
            return $this->setAttribute('method', $method);
        }
        /**
        * get the method
        * @access public
        * @return string
        */
        public function getMethod()
        {
            return $this->getStringAttribute('method');
        }
        /**
         * set the target value
         * @param string $target : the form target
         * @access public
         * @final
         * @return boolean
         */
        public final function setTarget($target)
        {
            return $this->setAttribute('target', $target);
        }
        /**
         * get the target
         * @access public
         * @return string
         */
        public function getTarget()
        {
            return $this->getStringAttribute('target');
        }
        /**
         * set the style value
         * @param string $style
         * @access public
         * @final
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
         * set the enctype value
         * @param string $enctype
         * @access public
         * @final
         * @return boolean
         */
        public final function setEnctype($enctype)
        {
            return $this->setAttribute('enctype', $enctype);
        }
        /**
        * get the enctype
        * @access public
        * @return string
        */
        public function getEnctype()
        {
            return $this->getStringAttribute('enctype');
        }
        /**
         * set the form name
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
         * get the form code
         * @access public
         * @return string
         */
        public function __toString()
        {
            return '<form ' . $this->getSerializedElements() . '>';
        }
        /**
         * get the endform tag
         * @access public
         * @final
         * @return string
         */
        public final function endForm()
        {
            return '</form>';
        }
    }
}