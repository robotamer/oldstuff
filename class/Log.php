<?php

/**
 * Sqlite logging class
 *
 *
 * @package    TaMeR
 * @author     Dennis T Kaplan
 * @copyright  2008 - 2011, Dennis T Kaplan
 * @license    http://tamer.pzzazz.net/license.html
 * @link       http://tamer.pzzazz.net
 */

defined('DROOT') || trigger_error("Please define data locaction (DROOT)");

/**
 * Logger
 *
 * Log::set('testle');
 * echo Log::get('15');
 *
 * @author  Dennis T Kaplan
 */
class Log {

    private static $instance = NULL;
    private static $db = NULL;

    // Logging Functions
    public static function set($msg, $level = 'Notice')
    {
        Load::f('randString');
        $db = self::db();
        defined('LOGRAND') || define('LOGRAND', randString('4'));
        $sql = "INSERT INTO log (session, level, message) VALUES ('".LOGRAND."', '$level', '$msg');";
        $db->exec($sql);
    }

    public static function gethtml($items = 20)
    {
        $db = self::db();
        $sql = "SELECT * FROM log ORDER BY added DESC LIMIT $items";
        $html = '<table border="1" cellpadding="5" cellspacing="0" class="center">';
        $html .= '<tr><th>Date Time</th><th>Session</th><th>Level</th><th>Message</th></tr>';

        $i = 0;
        foreach ($db->query($sql, PDO::FETCH_OBJ) as $row) {
            ($i&1) ? $color = '#ffffff' : $color = '#d5d5d5';
            $html .= '<tr style="background-color: '.$color.'">'."\n";
            $html .= "\t<td>$row->added</td>\n";
            $html .= "\t<td>$row->session</td>\n";
            $html .= "\t<td>$row->level</td>\n";
            $html .= "\t<td>$row->message</td>\n";
            $html .= "</tr>\n";
            $i++;
        }

        $html .= '</table>';
        return $html;
    }

    private function __construct(){}

    private static function getInstance()
    {
        if(self::$instance === NULL) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function __clone()
    {
        trigger_error('Cloning is not allowed. ', E_USER_ERROR);
    }

    public function __destruct()
    {
        if (isset($this->db))
        {
            unset($this->db);
        }
        unset($this->instance);
    }

    private static function db(){

        $log = self::getInstance();

        if(isset($log->db) && $log->db !== NULL) return $log->db;

        $dbname = DROOT.'tmp.db3';
        try {
            $log->db = new PDO('sqlite:'.$dbname);
            if (DEBUG === TRUE) $log->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
                    trigger_error("PDO Sqlite3 Connection with config failed:<br />$dbname<br />" . $e->getMessage());
        }
        if(defined('DEBUG') && DEBUG !== FALSE) {
            $sql = "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
            $result = $log->db->query($sql);
            foreach ($result as $row) $tables[] = current($row);
            if( ! in_array('log', $tables)) {
                $log->db->exec("CREATE TABLE log (added DATETIME, session TEXT, level TEXT, message TEXT)");
                $log->db->exec("CREATE TRIGGER log_insert_time AFTER INSERT ON log BEGIN UPDATE log SET added = datetime('NOW','UTC') WHERE rowid = last_insert_rowid(); END;");
            }
        }
        return $log->db;
    }
}
?>