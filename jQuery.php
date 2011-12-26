<?php

/**
 * RoboTamer Modified:
 * Combined jQuery libs, all classes in one file
 **/


/**
 * jQuery
 *
 * @author Anton Shevchuk
 * @access   public
 * @package  jQuery
 * @version  0.8
 */
class jQuery
{
    /**
     * static var for realize singlton
     * @var jQuery
     */
    public static $jQuery;

    /**
     * response stack
     * @var array
     */
    public $response = array(
                              // actions (addMessage, addError, eval etc.)
                              'a' => array(),
                              // jqueries
                              'q' => array()
                            );
    /**
     * __construct
     *
     * @access  public
     */
    function __construct()
    {

    }

    /**
     * init
     * init singleton if needed
     *
     * @return void
     */
    public static function init()
    {
        if (empty(jQuery::$jQuery)) {
            jQuery::$jQuery = new jQuery();
        }
        return true;
    }


    /**
     * addData
     *
     * add any data to response
     *
     * @param string $key
     * @param mixed $value
     * @param string $callBack
     * @return jQuery
     */
    public static function addData ($key, $value, $callBack = null)
    {
        jQuery::init();

        $jQuery_Action = new jQuery_Action();
        $jQuery_Action ->add('k', $key);
        $jQuery_Action ->add('v', $value);

        // add call back func into response JSON obj
        if ($callBack) {
            $jQuery_Action ->add("callback", $callBack);
        }

        jQuery::addAction(__FUNCTION__, $jQuery_Action);

        return jQuery::$jQuery;
    }

    /**
     * addMessage
     *
     * @param string $msg
     * @param string $callBack
     * @param array  $params
     * @return jQuery
     */
    public static function addMessage ($msg, $callBack = null, $params = null)
    {
        jQuery::init();

        $jQuery_Action = new jQuery_Action();
        $jQuery_Action ->add("msg", $msg);


        // add call back func into response JSON obj
        if ($callBack) {
            $jQuery_Action ->add("callback", $callBack);
        }

        if ($params) {
            $jQuery_Action ->add("params",  $params);
        }

        jQuery::addAction(__FUNCTION__, $jQuery_Action);

        return jQuery::$jQuery;
    }

    /**
     * addError
     *
     * @param string $msg
     * @param string $callBack
     * @param array  $params
     * @return jQuery
     */
    public static function addError ($msg, $callBack = null, $params = null)
    {
        jQuery::init();

        $jQuery_Action = new jQuery_Action();
        $jQuery_Action ->add("msg", $msg);

        // add call back func into response JSON obj
        if ($callBack) {
            $jQuery_Action ->add("callback", $callBack);
        }

        if ($params) {
            $jQuery_Action ->add("params",  $params);
        }

        jQuery::addAction(__FUNCTION__, $jQuery_Action);

        return jQuery::$jQuery;
    }
    /**
     * evalScript
     *
     * @param  string $foo
     * @return jQuery
     */
    public static function evalScript ($foo)
    {
        jQuery::init();

        $jQuery_Action = new jQuery_Action();
        $jQuery_Action ->add("foo", $foo);

        jQuery::addAction(__FUNCTION__, $jQuery_Action);

        return jQuery::$jQuery;
    }

    /**
     * response
     * init singleton if needed
     *
     * @return string JSON
     */
    public static function getResponse()
    {
        jQuery::init();

        echo json_encode(jQuery::$jQuery->response);
        exit ();
    }

    /**
     * addQuery
     * add query to stack
     *
     * @return jQuery_Element
     */
    public static function addQuery($selector)
    {
        jQuery::init();

        return new jQuery_Element($selector);
    }

    /**
     * addQuery
     * add query to stack
     *
     * @param  jQuery_Element $jQuery_Element
     * @return void
     */
    public static function addElement(jQuery_Element &$jQuery_Element)
    {
        jQuery::init();

        array_push(jQuery::$jQuery->response['q'], $jQuery_Element);
    }


    /**
     * addAction
     * add query to stack
     *
     * @param  string $name
     * @param  jQuery_Action $jQuery_Action
     * @return void
     */
    public static function addAction($name, jQuery_Action &$jQuery_Action)
    {
        jQuery::init();

        jQuery::$jQuery->response['a'][$name][] = $jQuery_Action;
    }
}

/**
 * jQuery
 *
 * alias for jQuery::jQuery
 *
 * @access  public
 * @param   string   $selector
 * @return  jQuery_Element
 */
function jQuery($selector)
{
    return jQuery::addQuery($selector);
}


/**
 * Class jQuery_Action
 *
 * Abstract class for any parameter of any action
 *
 * @author Anton Shevchuk
 * @access   public
 * @package  jQuery
 */
class jQuery_Action
{
    /**
     * add param to list
     *
     * @param  string $param
     * @param  string $value
     * @return jQuery_Action
     */
    public function add($param, $value)
    {
        $this->$param = $value;
        return $this;
    }
}


/**
 * jQuery_Element - class for work with jQuery framework
 *
 * @author Anton Shevchuk
 * @access   public
 * @package  jQuery
 */
class jQuery_Element
{
    /**
     * selector path
     * @var string
     */
    public $s;

    /**
     * methods
     * @var array
     */
    public $m = array();

    /**
     * args
     * @var array
     */
    public $a = array();

    /**
     * __construct
     * contructor of jQuery
     *
     * @return jQuery_Element
     */
    public function __construct($selector)
    {
        jQuery::addElement($this);
        $this->s = $selector;
    }

    /**
     * __call
     *
     * @return jQuery_Element
     */
    public function __call($method, $args)
    {
        array_push($this->m, $method);
        array_push($this->a, $args);

        return $this;
    }

    /**
     * end
     * need to create new jQuery
     *
     * @return jQuery_Element
     */
    public function end()
    {
        return new jQuery_Element($this->s);
    }
}
