<?php
class Mongo extends LampcmsObject
{
	/**
	 * Mongo connection resource
	 *
	 * @object of type Mongo
	 */
	protected $conn;


	/**
	 * Object MongoDB
	 * @object of type MongoDB
	 */
	protected $db;


	/**
	 * Name of database
	 *
	 * @string
	 */
	protected $dbname;

	public function __construct(){

        if(!extension_loaded('mongo')){
	        throw new Exception("Unable to load Mongo DB.");
        }
		try{
			$this->conn = new Mongo($server, $aOptions);
			d('$this->conn: '.get_class($this->conn)); // Mongo

		} catch (Exception $e){
			throw new Exception('Unable to connect to Mongo DB at '.$path.'<br />'. $e->getMessage());
		}
	}
}



?>
