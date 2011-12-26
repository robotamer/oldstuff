<?php // PHP RoboTamer -> Session
/**
 * An open source application development framework for PHP 5.2 or newer
 *
 * @package      RoboTamer
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license      http://wiki.robotamer.com/linux/php/robotamer/license
 * @link         http://www.robotamer.com
 */

/**
 * @todo     Move lifetime, session name and salt to config
 * @author   Dennis T Kaplan
 */
class Session {

    private $lifetime = 10; // Day

    public function  __construct() {}

    public static function start(){
        if (session_id() == "") {
            $lifetime = 1 * (60*60*24);
            session_name("RoboTamerSession");
            $host = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
            $host = $host[1].'.'.$host[0];
            $domain = '.'.$host;
            session_set_cookie_params($lifetime, '/', $domain);
            session_set_save_handler(
                            array('DB_Session', 'open'),
                            array('DB_Session', 'close'),
                            array('DB_Session', 'read'),
                            array('DB_Session', 'write'),
                            array('DB_Session', 'destroy'),
                            array('DB_Session', 'gc')
                    );
            return session_start();
        }
    }
    /**
     * getId() - get the current session id
     *
     * @return string
     */
    public static function getId(){
        return session_id();
    }
    public static function newId(){
        DB_Session::newId();
    }
    public static function delSession(){
        return DB_Session::destroy();
    }
}

class DB_Session {

    private $db_session;
    private static $lifetime = 1; // Day
    private static $salt = 'Rebel';

    private function  __construct(){}
    public function __destruct()
    {
        session_write_close();
    }
    /**
     * Open the session
     * @return bool
     */
    public static function open()
    {
        $parm = (defined('DROOT')) ? 'sqlite:'.DROOT.DIRECTORY_SEPARATOR.'tmp.db3' : 'sqlite:/tmp/session.db3';
        $tables = array();
        global $db_session;
        $lifetime = self::$lifetime;

        try {
            $db_session = new PDO($parm);
        } catch (PDOException $e) {
            if(defined('DEBUG') && DEBUG !== FALSE){
                trigger_error('Sqlite Tmp not connected'. $e->getMessage());
                exit;
            }
        }

        if(defined('DEBUG') && DEBUG !== FALSE) $db_session->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $sql = "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
        $result = $db_session->query($sql);
        foreach ($result as $row) $tables[] = current($row);
        if( ! in_array('sessions', $tables)) {
            $sql  = "BEGIN TRANSACTION;";
            $sql .= "CREATE TABLE sessions (id TEXT UNIQUE, name TEXT, first REAL, last REAL, lifetime  RAEL, data BLOB, fingerprint TEXT, PRIMARY KEY (id));";
            $sql .= "CREATE TRIGGER session_delete AFTER UPDATE ON sessions BEGIN DELETE FROM sessions WHERE last + lifetime < julianday('now','utc'); END;";
            $sql .= "CREATE TRIGGER session_insert_time AFTER INSERT ON sessions BEGIN UPDATE sessions SET first = julianday('NOW','UTC'), lifetime = '$lifetime' WHERE rowid = last_insert_rowid();END;";
            $sql .= "CREATE TRIGGER session_update_time AFTER UPDATE ON sessions BEGIN UPDATE sessions SET last = julianday('NOW','UTC') WHERE rowid = last_insert_rowid(); END;";
            $sql .= "COMMIT;";
            $db_session->exec($sql);
        }
        return ((is_object($db_session)) ? TRUE : FALSE);
    }
    /**
     * Close the session
     * @return bool
     */
    public static function close()
    {
        global $db_session;
        unset($db_session);
        return TRUE;
    }
    /**
     * Read the session data
     * @param int id
     * @return string data
     */
    public static function read($id)
    {
        global $db_session;
        if( ! is_object($db_session) ) self::open();

        $query = $db_session->query("SELECT data, fingerprint FROM sessions WHERE id = '$id' LIMIT 1;");
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result['fingerprint'] != md5($_SERVER['HTTP_USER_AGENT'].self::$salt))
        {
            return $result['data'] = NULL;
        }
        return $result['data'];
    }
    /**
     * Write the session, time is being updated by sqlite trigger
     * @param int id
     * @param string data
     */
    public static function write($id, $data)
    {
        global $db_session;
        //$data = serialize($_SESSION);
        if( ! is_object($db_session) ) self::open();

        $sth = $db_session->prepare("INSERT OR REPLACE INTO sessions (id, name, data, fingerprint) VALUES (:id, :name, :data, :fingerprint);");

        //Returns TRUE on success or FALSE on failure.
        return $sth->execute(array(':id'=>$id, ':name'=> session_name(), ':data'=>$data, ':fingerprint'=>md5($_SERVER['HTTP_USER_AGENT'].self::$salt)));
    }

    public static function newId()
    {
        global $db_session;
        if( ! is_object($db_session) ) self::open();
        $id = session_id();
        session_regenerate_id(false);
        $db_session->exec("UPDATE sessions SET lifetime = '-10' WHERE id = '$id';");
        self::destroy($id);
    }

    /**
     * Destoroy the session
     * @param int session id
     * @return bool
     */
    public static function destroy()
    {
        global $db_session;
        if( ! is_object($db_session) ) self::open();
        $id = session_id();
        return $db_session->exec("DELETE FROM sessions WHERE id = '$id'");
    }
    /**
     * Garbage Collector handled via sqlite trigger
     * @return bool
     */
    public static function gc() { return true; }
}
?>
