<?php
/**
 * $Id: Option.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Form_Option')) {
    if (!defined('__CLASS_PATH__')) {
        define('__CLASS_PATH__', realpath(dirname(__FILE__) . '/../'));
    }
    require_once __CLASS_PATH__ . '/Autoload.php';
    /**
     * form option class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Form_Option extends Form_Object
    {
        /**
         * @var string $name : the label of the option
         * @acces private
         */
        private $name = '';
        /**
         * constructor
         * @param string $name : the option name
         * @param string $value : the value
         * @param boolean $selected : does the option selected?
         * @access public
         */
        public function __construct($name, $value = '', $selected = false)
        {
            parent :: __construct();
            $this->name = strval($name);
            $this->setAttribute('value', $value);
            $this->setSelectedAttribute($selected);
        }
        /**
         * get the value
         * @access public
         * @final
         * @return string
         */
        public final function getValue()
        {
            return $this->getStringAttribute('value');
        }
        /**
         * get the option name
         * @access public
         * @final
         * @return string
         */
        public final function getName()
        {
            return $this->name;
        }
        /**
         * return the html corresponding code
         * @access public
         * @return string
         */
        public function __toString()
        {
            $options = trim($this->getSerializedElements());
            if (!empty ($options)) {
                $options = ' ' . $options;
            }
            return '<option' . $options . '>' . $this->name . '</option>';
        }
    }
}