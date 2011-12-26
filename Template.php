<?php
require_once 'Zend/View/Abstract.php';
/**
 * An open source application development framework for PHP
 *
 * @category	RoboTaMeR
 * @author		 Dennis T Kaplan
 * @copyright	 Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license		http://RoboTamer.com/license.html
 * @link			http://RoboTamer.com
 */

 /**
 * Sqlite key value database
 *
 * @category	RoboTaMeR
 * @package		Template
 * @author		 Dennis T Kaplan
 * @copyright	 Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license		http://RoboTamer.com/license.html
 * @link			http://RoboTamer.com
 */
class Template extends Zend_View_Abstract {

	private $metaName = array('keywords'=>'domain,hosting,vps,server');
	private $metaHttpEquiv = array();
	 /**
	* Constructor
	*
	* RemovedZend_View_Stream since I have that in my php.ini file turned on
	*
	* @paramarray $config
	* @return void
	*/
	 public function __construct($config = array()) {
		parent::__construct($config);
		$this->setBasePath(ROOT.'/view');
		$this->addScriptPath(ROOT.'/view/layouts');
	 }
	public function create() {
		//$core = Singleton::factory('Core');
		$title = KvLite::get('site','title');
		$this->headTitle($title)->setSeparator(' :: ');
		$this->genMeta();
		//$this->headLink()->prependStylesheet('/asset/css/layout.css');
		//$this->headLink()->prependStylesheet('/asset/css/main.css');
		//$this->headLink()->prependStylesheet('/asset/css/base.css');
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
		$this->metaHttpEquiv['Content-Language'] =
				defined('LANGUAGE') ? LANGUAGE : 'en_US';
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
	protected function _run(){
		include func_get_arg(0);
	}
}
?>