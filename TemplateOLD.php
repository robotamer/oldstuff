<?php // Template

/**
 * Name: Template
 * Description
 *
 * @package    TaMeR FrameWork Core
 * @author     Dennis T Kaplan
 * @copyright  (c) 2009-2010 TaMeR Team
 * @license    http://tamer.pzzazz.com/license.html
 */

class Template extends View {
    public $template = 'base';
    public $renderAuto = TRUE;
    protected $cssArray = '';
    protected $jsArray = '';
    protected $metaArray = '';

    public function __construct() {
    	/*
        $trace = debug_backtrace();
        if($trace[1]['class'] != 'Singleton'){
            e($trace);
            trigger_error("Class ".$trace[0]['class'].' must be called from the Singilton Core Class', E_USER_ERROR );
        }
        parent::__construct();
        */
        $this->core = Singleton::factory('Core');
        $this->configSet();
    }
    public function  __destruct() {
        global $route;
        if ($this->renderAuto == TRUE){
            $this->docType();
            if(isset($this->metaArray))   $this->metaGen();
            if(isset($this->cssArray))    $this->cssGen();
            $this->jsGen();
/*
            $menu = new View();
            $menu->setFilename($route['folder'].'/menu');
            $this->menu = $menu;
*/
            //timer();
            echo $this->render();
        }
    }
    public function viewLoad($file, $data) {
        /**
         * Includes a View within the controller scope.
         *
         * @param   string  view filename
         * @param   array   array of view variables
         * @return  string
         */
        if($file == '') return;

        // Buffering on
        ob_start();

        // Import the view variables to local namespace
        extract($data, EXTR_SKIP);

        // Views are straight HTML pages with embedded PHP, so importing them
        // this way insures that $this can be accessed as if the user was in
        // the controller, which gives the easiest access to libraries in views
        try {
            include $file;
        }
        catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $this->core->shutdown(); // Shutdown Core Class
        // Fetch the output and close the buffer
        return ob_get_clean();
    }
    private function configSet() {
        $this->setGlobal('title', $this->core->cfgKey('view','title'));
        $this->setGlobal(array('menu'=>'','error'=>'','body'=>'','content'=>''));
        $this->cssSet(array('base', 'layout'));
        //$this->metaArray = $this->core->cfgKey('view','metaArray');
        $this->metaArray['content-language'] = array('name' => 'content-language', 'content' => LANGUAGE, 'type' => 'equiv');
    }

    public function docType($type = 'xhtml1-strict') {
        //$type = $this->core->cfgKey('view','doctypes');
        $this->setGlobal('docType', $type.PHP_EOL);
    }
    public function metaSet($name = '', $content = '', $type = 'name') {
        /**
        * Set a meta variable.
        *
        * @param string $name name of the variable to set
        * @param mixed $value the value of the variable
        *
        * @return void
        */
        if ( is_string($name)) {
            $this->metaArray[$name] = array('name' => $name, 'content' => $content, 'type' => $type);
        }else{
            trigger_error("metaSet only takes strings, no array", E_USER_ERROR);
        }
    }
    private function metaGen() {
        /**
        * Generates meta tags from an array of key/values
        *
        * @access	private
        * @param	array
        * @return	string
        */
        $r = '';
        foreach ($this->metaArray as $meta)
        {
            $type 	= ( ! isset($meta['type'])      OR  $meta['type'] == 'name') ? 'name' : 'http-equiv';
            $name 	= ( ! isset($meta['name'])) 	? '' 	: $meta['name'];
            $content	= ( ! isset($meta['content']))	? '' 	: $meta['content'];

            $r .= "      ".'<meta '.$type.'="'.$name.'" content="'.$content.'" />'.PHP_EOL;
        }
        $this->setGlobal('meta', $r);
    }
    
    public function cssSet($name = '') {
       /**
        * Set css variable.
        * @return void
        */
        if ( is_string($name)) {
            $this->cssArray[$name] = $name;
        }else{
            foreach($name as $y){
                    $this->cssArray[$y] = $y;
            }
        }
    }
    
    private function cssGen() {
    /**
    * Generates css tags from an array
    *
    * @access	private
    * @param	array
    * @return	string
    */
        $css = '';
        if ( is_array($this->cssArray)) {
            foreach($this->cssArray as $v){
                $css .= '      <link rel="stylesheet" type="text/css" href="/css/'.$v.'.css" />'.PHP_EOL;
            }
            $this->setGlobal('css', $css);
        }
    }
    public function jsSet($name) {
   /**
    * Set js variable.
    * @return void
    */
        if (is_string($name)) {
            $this->jsArray[] = $name;
        }else{
            foreach($name as $y){
                $this->jsArray[] = $y;
            }
        }
    }
    private function jsGen() {
        /**
        * Generates js tags from an array
        *
        * @access	private
        * @param	array
        * @return	string
        */
        $js = '';
        if ( ! isset($this->jsArray)) $this->jsArray = '';
        if ( is_array($this->jsArray)) {
            foreach($this->jsArray as $v){
                $js .= '      <script type="text/javascript" src="/js/'.$v.'.js"></script>'. PHP_EOL;
            }
        }else{
            $js = '   <!-- No javaScript -->'. PHP_EOL;
        }
        $this->setGlobal('js', $js);
    }
}
?>
