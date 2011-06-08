<?php
if(!defined('DROOT')) trigger_error("Please define data locaction (DROOT)");

/*
 * Usage:
 * $db = new dbSqlite();
 * $db->setdbName('dict');
 * $db->setdbTable('us');
 * $db->dbCount();
 */

/**
 * print / echo everything
 *
 * Replacement for php echo, print, print_r(), var_export() etc
 */

/**
 * @category    Data
 * @package  TaMeR
 * @copyright  Copyright (c) 2008 - 2011 Dennis T Kaplan
 * @license  http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 * @author    Dennis T Kaplan
 * @date         May 1, 2011
 * @version  1.0
 * @access    public
 **/
class dbSqlite{

    private $db;
    private $dbfile;
    private $dbname;
    private $dbtable;

    public function __construct(){}
    private function __clone(){}
    public function __destruct(){
        if(is_object($this->db)){
            $this->db = NULL;
            //$this->db->close(); //Without PDO
        }
    }
    public function setdb($name,$table){
        $this->setdbName($name);
        $this->setdbTable($table);
    }
    public function setdbName($name='default'){

        if( $name == 'default' && defined('APPL') )
            $name = APPL;

        Load::f('replace');
        $name = replaceSpaceUnderscore($name);

        $this->dbname = $name;
        $this->dbfile = DROOT.$name.'.db3';
    }
    public function getdbName()
    {
        if( ! isset($this->dbname) || ! is_string($this->dbtable))
            $this->setdbTable();

        return $this->dbtable;
    }
    public function setdbTable($table = 'default'){
        $this->dbtable = $table;
    }
    public function getdbTable()
    {
        if( ! isset($this->dbtable) || ! is_string($this->dbtable))
            $this->setdbTable();

        return $this->dbtable;
    }
    public function connect(){
        if(is_object($this->db)) return $this->db;
        if(!is_string($this->dbname)) $this->setdb();
        $this->db = null;
        try {
            //$this->db = new SQLite3($this->dbfile);
            $this->db = new PDO('sqlite:'.$this->dbfile);
            return $this->db;
        } catch (Exception $e) {
            trigger_error("Sqlite3 Connection failed: $this->dbname could not be created. Write Error!".PHP_EOL.$e->getMessage());
        }
    }
    public function db(){
        /*
         * @todo Move create table out and only call it when in debig mode
         */
        if($this->db !== NULL) return $this->db;
        $this->connect();
        $tables = array();
        $sql = "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
        $result = $this->db->query($sql);
        foreach ($result as $row) $tables[] = current($row);
        if($this->dbname == 'tmp' && $table == 'log'){
            if( ! in_array('log', $tables)) {
                $this->db->exec("CREATE TABLE log (added DATETIME, session TEXT, level TEXT, message TEXT)");
                $this->db->exec("CREATE TRIGGER log_insert_time AFTER INSERT ON log BEGIN UPDATE log SET added = datetime('NOW','UTC') WHERE rowid = last_insert_rowid(); END;");
            }
        }
        if($this->dbname == 'tmp' && $table == 'visitor'){
            if( ! in_array('visitor', $tables)) {
                $sql  = "CREATE TABLE visitor (ip TEXT UNIQUE, iso3, timezone, pagecount, data, last DATETIME, first DATETIME);";
                $sql .= "CREATE TRIGGER visitor_ip_insert_time AFTER INSERT ON visitor BEGIN UPDATE visitor SET first = datetime('NOW','UTC') WHERE rowid = last_insert_rowid(); END;";
                $sql .= "CREATE TRIGGER visitor_ip_update_time AFTER UPDATE ON visitor BEGIN UPDATE visitor SET last  = datetime('NOW','UTC') WHERE visitor.ip = old.ip; END;";
                $sql .= "INSERT INTO visitor (ip) VALUES ('127.0.0.1');";
                $this->db->exec($sql);
            }
            return $this->db;
        }
    }// SELECT * FROM sqlite_master WHERE type = 'index' AND sql LIKE '%UNIQUE%';

    public function createDB($rows)
    {
        $dbtable = $this->getdbTable();

        $sql = "SELECT name FROM sqlite_master WHERE type='table'
                UNION ALL
                    SELECT name FROM sqlite_temp_master WHERE type='table'
                ORDER BY name";
        $result = $this->db->query($sql);
        foreach ($result as $row)
            $tables[] = current($row);

        if( ! in_array($dbtable, $tables)) {
            $rows = $row = $type = NULL;
            foreach($rows as $row=>$type){
                $rowString = $row.' '.$type.',';
            }
            rtrim($rowString,',');

            $sql  = "CREATE TABLE $dbtable ($rowString)";
            $this->db->exec($sql);
        }
        return TRUE;
    }

    public function dbInsert($row, $string){
        if(!is_object($this->db)) $this->connect();
        $string = rtrim($string,"\n");
        $string = trim($string);
        $sql = "INSERT INTO $this->dbtable ($row) VALUES ('$string');";
        $query = $this->db->query($sql);
        if(!$query){
            trigger_error("Sqlite3 insert failed:".BR.$this->db->lastErrorMsg());
        }
    }

    public function dbCount() {
        if(!is_object($this->db)) $this->connect();
        $query = $this->db->query("SELECT count(*) FROM $this->dbtable ;");
        if (!$query){
            trigger_error("Sqlite3 count failed:".BR.$this->db->lastErrorMsg());
        }
    }
    public function dbSearch(){
        $query = $this->db->query("SELECT * FROM us ORDER BY word ");
        if (!$query) {
            throw new Exception( $this->db->lastErrorMsg() );
        }else{
            while(($row = $query->fetchArray(SQLITE3_ASSOC)))
                echo $row['word'].'<br/>';
        }
        $query->finalize();
    }
    public function numRows($query) {
        $n = $a = 0;
        while($a = $query->fetchAll(PDO::FETCH_NUM)) {
            $n++;
        }
        return $n;
    } // PDO SQLITE3
}

/*
    $query = $this->db->query("SELECT * FROM us WHERE word='$line'");
    $line = SQLite3::escapeString($line);
    $db->dbInsert('word',$line);
    //}
}
*/
?>