<?php
/**
 * $Id: Javascript.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!class_exists('Html_Javascript')) {
    /**
     * form javascript class
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    class Html_Javascript
    {
        /**
         * @access private
         * @var string $_evt : the evenement name
         */
        private $_evt = '';
        /**
         * @access private
         * @var string $_action : the action to do on the event
         */
        private $_action = '';
        const __ONABORT__ = 'onabort';
        const __ONBLUR__ = 'onblur';
        const __ONCLICK__ = 'onclick';
        const __ONCHANGE__ = 'onchange';
        const __ONDOUBLECLICK__ = 'ondblclick';
        const __ONDRAGANDDROP__ = 'ondragdrop';
        const __ONERROR__ = 'onerror';
        const __ONFOCUS__ = 'onfocus';
        const __ONKEYDOWN__ = 'onkeydown';
        const __ONKEYPRESS__ = 'onkeypress';
        const __ONKEYUP__ = 'onkeyup';
        const __ONLOAD__ = 'onload';
        const __ONMOUSEOVER__ = 'onmouseover';
        const __ONMOUSEDOWN__ = 'onmousedown';
        const __ONMOUSEOUT__ = 'onmouseout';
        const __ONMOUSEUP__ = 'onmouseup';
        const __ONRESET__ = 'onreset';
        const __ONRESIZE__ = 'onresize';
        const __ONSELECT__ = 'onselect';
        const __ONSUBMIT__ = 'onsubmit';
        const __ONUNLOAD__ = 'onunload';
        /**
         * builder
         * @access public
         * @return void
         * @param string $evt : the javascript event
         * @param string $action : the associated action
         */
        public function __construct($evt, $action)
        {
            $this->_evt = $evt;
            $this->_action = $action;
        }
        /**
         * get the event
         * @access public
         * @final
         * @eturn string
         */
        public final function getEvent()
        {
            return $this->_evt;
        }
        /**
        * get the action
        * @access public
        * @final
        * @eturn string
        */
        public final function getAction()
        {
            return $this->_action;
        }
        /**
         * return the code for the javascript event
         * @access public
         * @return string
         */
        public function __toString()
        {
            return $this->_evt . '="javascript:' . $this->_action . '"';
        }
        /**
         * check if a call to javascript is valid
         * @access public
         * @return boolean
         * @param string $object : the related object to javascript
         * @see Html_HTMLElement.class.php
         */
        public function isValid(Html_Element & $object)
        {
            $className = get_class($object);
            $expectedJsEvents = array ();
            switch ($className) {
                case 'Form_Form' :
                    $expectedJsEvents = array (
                        self :: __ONRESET__,
                        self :: __ONSUBMIT__
                    );
                    break;
                case 'Form_Button' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCLICK__,
                        self :: __ONFOCUS__,
                        self :: __ONMOUSEDOWN__,
                        self :: __ONMOUSEUP__
                    );
                    break;
                case 'Form_Checkbox' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCLICK__,
                        self :: __ONFOCUS__
                    );
                    break;
                case 'Form_Files' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCHANGE__,
                        self :: __ONFOCUS__
                    );
                    break;
                case 'Form_Hidden' :
                    $expectedJsEvents = array ();
                    break;
                case 'Form_Option' :
                    $expectedJsEvents = array ();
                    break;
                case 'Form_Password' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONFOCUS__
                    );
                    break;
                case 'Form_Radio' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCLICK__,
                        self :: __ONFOCUS__
                    );
                    break;
                case 'Form_Reset' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCLICK__,
                        self :: __ONFOCUS__
                    );
                    break;
                case 'Form_Select' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCHANGE__,
                        self :: __ONCLICK__,
                        self :: __ONFOCUS__
                    );
                    break;
                case 'Form_Submit' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCHANGE__,
                        self :: __ONCLICK__,
                        self :: __ONFOCUS__
                    );
                    break;
                case 'Form_Textarea' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCHANGE__,
                        self :: __ONFOCUS__,
                        self :: __ONKEYDOWN__,
                        self :: __ONKEYUP__,
                        self :: __ONKEYPRESS__,
                        self :: __ONSELECT__
                    );
                    break;
                case 'Form_Text' :
                    $expectedJsEvents = array (
                        self :: __ONBLUR__,
                        self :: __ONCHANGE__,
                        self :: __ONFOCUS__,
                        self :: __ONSELECT__
                    );
                    break;
                case 'Html_Link' :
                    $expectedJsEvents = array (
                        self :: __ONCLICK__,
                        self :: __ONDOUBLECLICK__,
                        self :: __ONKEYDOWN__,
                        self :: __ONKEYUP__,
                        self :: __ONKEYPRESS__,
                        self :: __ONMOUSEDOWN__,
                        self :: __ONMOUSEOUT__,
                        self :: __ONMOUSEOVER__,
                        self :: __ONMOUSEUP__
                    );
                    break;
                case 'Html_Picture' :
                    $expectedJsEvents = array (
                        self :: __ONABORT__,
                        self :: __ONERROR__,
                        self :: __ONKEYDOWN__,
                        self :: __ONKEYUP__,
                        self :: __ONRESIZE__,
                        self :: __ONKEYPRESS__,
                        self :: __ONLOAD__
                    );
                    break;
                default :
                    $expectedJsEvents = array (
                        self :: __ONABORT__,
                        self :: __ONBLUR__,
                        self :: __ONCLICK__,
                        self :: __ONCHANGE__,
                        self :: __ONDOUBLECLICK__,
                        self :: __ONDRAGANDDROP__,
                        self :: __ONERROR__,
                        self :: __ONFOCUS__,
                        self :: __ONKEYDOWN__,
                        self :: __ONKEYPRESS__,
                        self :: __ONKEYUP__,
                        self :: __ONMOUSEOVER__,
                        self :: __ONMOUSEOUT__,
                        self :: __ONRESET__,
                        self :: __ONRESIZE__,
                        self :: __ONSUBMIT__,
                        self :: __ONUNLOAD__,
                        self :: __ONLOAD__
                    );
            }
            return in_array($this->_evt, $expectedJsEvents);
        }
    }
}