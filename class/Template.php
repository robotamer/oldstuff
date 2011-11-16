<?php // Template

/**
 * An open source application development framework for PHP 5.2 or newer
 *
 * @package    RoboTamer
 * @author     Dennis T Kaplan
 * @copyright  Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license    http://tamer.pzzazz.net/license.html
 * @link       http://tamer.pzzazz.net
 *
 */

/**
 * @author    Dennis T Kaplan
 *
 * @todo Move default css files to config
 */


/**
 * Abstract master class for extension.
 */
require_once 'Zend/View/Abstract.php';


class Template extends Zend_View_Abstract {

    private $metaName = array(  'rating'=>'General',
                                'author'=>'Dennis T Kaplan',
                                'copyright'=>'Copyright 2008 - 2011',
                                'robots'=>'index',
                                'description'=>'RoboTamer PHP FrameWork',
                                'keywords'=>'framework, PHP, productivity'
                             );
    private $metaHttpEquiv = array( 'Set-Cookie'=>'RoboTamer=1;expires=; path=/',
                                    'Content-Type'=>'text/html; charset=UTF-8',
                                    'Content-Language'=>'en-US'
                                  );

    /**
     * Constructor
     *
     * Removed  Zend_View_Stream since I have that in my php.ini file turned on
     *
     * @param  array $config
     * @return void
     */
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->setBasePath(ROOT.'/view');
        $this->addScriptPath(ROOT.'/view/layouts');
    }
   public function create() {
      $core = Singleton::factory('Core');
      $this->headTitle($core->get('view', 'title'))->setSeparator(' :: ');
      $this->genMeta();
      $this->headLink()->prependStylesheet('/asset/css/layout.css');
      $this->headLink()->prependStylesheet('/asset/css/main.css');
      $this->headLink()->prependStylesheet('/asset/css/base.css');
      if( defined('TIMERSTART') && ! defined('TIMERTOTAL')) timer();
      return $this->render('layout.phtml');
   }
   public function setMetaName($type, $value) {
      $this->metaName[$type]=$value;
   }
   public function setMetaHttpEquiv($type, $value){
      $this->metaHttpEquiv[$type]=$value;
   }
   private function genMeta() {
      $this->metaName['generator'] = 'RoboTamer PHP FrameWork';
      $this->metaHttpEquiv['Content-Language'] = defined('LANGUAGE') ? LANGUAGE : 'en_US';
      foreach($this->metaName as $k=>$v) {
         $this->headMeta()->appendName($k, $v);
      }
      foreach($this->metaHttpEquiv as $k=>$v) {
         $this->headMeta()->appendHttpEquiv($k, $v);
      }
   }

   /**
   * Includes the view script in a scope with only public $this variables.
   *
   * @param string The view script to execute.
   */
   protected function _run() {
     include func_get_arg(0);
   }
}
?>