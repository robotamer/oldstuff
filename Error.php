<?php // Error

/**
 * An open source application development framework for PHP 5.2 or newer
 *
 * @package      TaMeR
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 */


/**
 * @author     Dennis T Kaplan
 */
class Error extends ErrorException {

    protected $vars; 

	protected $codes = array
	(
		E_ERROR              => 'Error',
		E_USER_ERROR         => 'User Error',
		E_PARSE              => 'Parse Error',
		E_WARNING            => 'Warning',
		E_USER_WARNING       => 'User Warning',
		E_STRICT             => 'Strict',
		E_NOTICE             => 'Notice',
		E_RECOVERABLE_ERROR  => 'Recoverable Error',
	);


    function __construct($message, $code = null, $severity = E_ERROR, $filename = null, $line= null, array $vars = array())
    {
        parent::__construct($message, $code, $severity, $filename, $line);   

		$this->message  = $message;
		$this->code     = isset($this->codes[$code]) ? $this->codes[$code] : $code;
		$this->severity = $severity;
		$this->file     = $filename;
		$this->line     = $line;
        $this->vars     = $vars;
		$this->logErrors() ;
    }


	protected function logErrors()
	{
		$msg = $this->message .' | '. $this->file . ' #'. $this->line ;
		Logger::set($msg,$this->code);
	}
    
    /**
     * Return array that points to the active symbol table at the point the error
     * occurred. In other words, it will contain an array of every variable that
     * existed in the scope the error was triggered in.
     * 
     * @return array
     */ 
    public function getVars()   
    {
        return $this->vars;
    }

    /**
     * Set Zend_Exception::errorHandler() as error handling function.
     *
     * @param integer $error_types (optional) used to mask the triggering of the Zend_Exception::errorHandler() function.
     * @return boolean
     */ 
    public static function setErrorHandler($error_types = E_ALL) 
    { 
        set_error_handler('Error::errorHandler', (int) $error_types);
		set_exception_handler('Error::exceptionHandler');
        return true;   
    }

    /**
     * Error handling function. Used by Error::setErrorHandler()
     *
     * @param integer $errno Level of the error raised
     * @param string $errstr Error message
     * @param string $errfile Filename that the error was raised in
     * @param integer $errline Line number the error was raised at
     * @param array $context Array that points to the active symbol table at the
     * point the error occurred
     * @throws Zend_Exception
     * @return void
     */ 
    public static function errorHandler($errno = E_ERROR, $errstr, $errfile, $errline, $context)
    { 
        throw new Error($errstr, 0, $errno, $errfile, $errline, $context);
    } 

	public static function exceptionHandler($exception) {

		$msg = $exception->getMessage() .' | '. $exception->getFile() . ' #'. $exception->getLine() ;
		Logger::set($msg,get_class($exception));
	}


} // End Error
Error::setErrorHandler();

?>
