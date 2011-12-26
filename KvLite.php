<?php
/**
 * An open source application development framework for PHP
 *
 * @category    RoboTaMeR
 * @author      Dennis T Kaplan
 * @copyright   Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license     http://RoboTamer.com/license.html
 * @link        http://RoboTamer.com
 */

 /**
 * Sqlite key value database
 *
 * @category   RoboTaMeR
 * @package    KvLite
 * @author     Dennis T Kaplan
 * @copyright  Copyright (c) 20010 - 2011, Dennis T Kaplan
 * @license    http://RoboTamer.com/license.html
 * @link       http://RoboTamer.com
 */
class KvLite {

	private static $instance = NULL;
	protected $db;
	protected $file;
	public $msg = array();

	public static function get($cat, $key = FALSE) {
		$rows = $dbdata = FALSE;
		$dbdata = self::dbGet($cat, $key);
		if(is_array($dbdata)){
			foreach($dbdata as $k=>$v){
				if(is_array($v['value'])){
					$rows[$k]['Name']=$v['key'];
					foreach($v['value'] as $kk=>$kv)
						$rows[$k][$kk]=$kv;
				}elseif($key === FALSE){
					$rows[$v['key']] = $v['value'];
				}else{
					$rows = $v['value'];
				}
			}
			$dbdata = $rows;
		}
		return $dbdata;
	}

	public static function dbGet($cat, $key = FALSE)
	{
		$kv = self::getInstance();
		if(!is_object($kv->db)) $kv->connect();

		$result = $row = NULL;

		$data = array('cat'=>$cat);

		if($key !== FALSE){
			$data['key'] = $key;
			$key = ' AND key = :key';
		}else{
			$key = '';
		}

		$sql = "SELECT * FROM kv WHERE cat = :cat $key";
		$stmt = $kv->db->prepare($sql);

		foreach($data as $k => $v){
			$stmt->bindValue(':'.$k, $v);
		}unset($k,$v);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC) ;
		unset($stmt);

		foreach($result as $k=>$v){
			if(strpos($v['value'], '~StrSafe~>') > 0){
				$strsafe = new Strsafe();
				$result[$k]['value'] = $strsafe->run($v['value']);
			}
		}
		return $result;
	}

	public function delete($cat,$key){
		$sql="DELETE FROM [kv] WHERE cat=? AND key= ?";
		$sth = $this->db->prepare($sql);
		return $sth->execute(array($cat, $key));
	}

	public function deleteSet($cat){
		$sql="DELETE FROM [kv] WHERE cat=?";
		$sth = $this->db->prepare($sql);
		return $sth->execute(array($cat));
	}

	public static function save($cat,$key,$value){

		$kv = self::getInstance();
		if(!is_object($kv->db)) $kv->connect();

		$cleansedType = self::getType($value);
		$type  = $cleansedType['type'];
		$value = $cleansedType['value'];
		if($type == 'array' || $type == 'object'){
			$strsafe = new StrSafe();
			$value = $strsafe->run($value);
		}

		$sql="SELECT count(*) FROM [kv] WHERE cat == ? AND key == ? ";
		$sth = $kv->db->prepare($sql);
		$sth->execute(array($cat, $key));;
		$rows = $sth->fetch(PDO::FETCH_NUM);
		if ($rows[0]>0){
			return $kv->update($cat,$key,$value);
		}else{
			return $kv->insert($cat,$key,$value);
		}
	}
	protected function insert($cat,$key,$value){
		$sql = "INSERT INTO [kv] (cat,key,value) VALUES (?,?,?)";
		$sth = $this->db->prepare($sql);
		return $sth->execute(array($cat, $key, $value));
	}
	protected function update($cat,$key,$value){
		$sql = "UPDATE [kv] SET value = :value WHERE cat = :cat AND key = :key";
		$sth = $this->db->prepare($sql);
		return $sth->execute(array(':cat'=>$cat,':key'=>$key,':value'=>$value));
	}

	/**
	 * Insert a one dimentional array as key value set in to the database
	 *
	 * @param string $cat
	 * @param array  $array
	 * @return bool TRUE FALSE
	 */
	public static function insertArray($cat,$array){
		if(!self::isMultiArray($array)){

			$kv = self::getInstance();
			if(!is_object($kv->db)) $kv->connect();

			try {
				$kv->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$kv->db->beginTransaction();
				foreach ($array as $key=>$value)
					$kv->save($cat,$key,$value);
				$kv->db->commit();
			} catch (Exception $e){
				$kv->db->rollBack();
				$kv->msg[] = "Failed: " . $e->getMessage();
				return FALSE;
			}
			return TRUE;
		}else{
			$kv->msg[] = 'You can only insert a one dimentional array with insertArray!';
			return FALSE;
		}
	}

	protected function numRows($cat){
		$sql="SELECT count(*) FROM [kv] WHERE cat == ? ";
		$sth = $this->db->prepare($sql);
		$sth->execute(array($cat));
		$rows = $sth->fetch(PDO::FETCH_NUM);
		return $rows[0];
	} // PDO SQLITE3

	public static function getType($value, $max_length = 50){

		$type = gettype($value);

		if($type == 'NULL'
				|| $type == 'boolean'
				|| $type == 'integer'
				|| $type == 'double'
				|| $type == 'object'
				|| $type == 'resource'
				|| $type == 'array'
			)
			return array('type'=>$type,'value'=>$value);

		if($type == 'string' && empty($value))
			return array('type'=>'NULL','value'=>$value);

		if($type == 'string' && strlen($value) > $max_length)
			return array('type'=>'blob','value'=>$value);

		if($type == 'string' && substr($value, 0,1) === '0')
			return array('type'=>'string','value'=>$value);

		if($type == 'string' && is_numeric($value)){
			$int   = (int) $value;
			$float = (float) $value;

			if($int == $value){
				$value = $int;
				$type = 'integer';
			}elseif($float == $value){
				$value = $float;
				$type = 'double';
			}
		}elseif($type == 'string'){
			$type = 'string';
		}else{
			$type = 'blob';
		}
		return array('type'=>$type,'value'=>$value);
	}

	public static function isMultiArray($a){
		foreach($a as $v) if(is_array($v)) return TRUE;
		return FALSE;
	}
	private function __construct(){}
	protected static function getInstance(){
		if(self::$instance === NULL){
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}
	public function __clone(){
		trigger_error('Cloning is not allowed. ', E_USER_ERROR);
	}
	public function __destruct(){
		$kvlite = self::getInstance();
		if(isset($kvlite->db) && is_object($kvlite->db))
			$kvlite->db = NULL;
	}
	protected function connect(){
		if(is_object($this->db)) return $this->db;
		if($this->file === NULL) $this->file = self::setFile();
		$tables = array();
		try {
			$this->db = new PDO('sqlite:'.$this->file);
			$sql = "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
			$result = $this->db->query($sql);
			foreach ($result as $row) $tables[] = current($row);
			if( ! in_array('kv', $tables)) {
				if($this->db->exec('CREATE TABLE [kv] (cat, key, value)') === FALSE) die(print_r($this->db->errorInfo(), true));;
				chmod($this->file,00666);
			}
		} catch (Exception $e) {
			trigger_error("Database Connection failed: $this->file could not be created. Write Error!".PHP_EOL.$e->getMessage());
		}
	}
	public static function setFile($file = 'kv.db3'){
		$file = ROOT.'data/'.$file;
		return $file;
	}
}
?>