<?php
/**
 * Logging class
 *
 *
 * @package    RoboTaMeR
 * @author     Dennis T Kaplan
 * @copyright  2008 - 2011, Dennis T Kaplan
 * @license    http://robotamer.com/license
 * @link       http://robotamer.com
 */

defined('DROOT') || define('DROOT','/tmp/');

/**
 * Logger
 *
 * Logger::set('testle');
 * echo Logger::get('15');
 *
 * @author  Dennis T Kaplan
 */
class Logger {

    private static $instance = NULL;
    protected $db = NULL;

    const developer_email = NULL;

    /**
     * String to be used as subject line
     * of email notification
     *
     * @var string
     */
    const EMAIL_SUBJECT = 'Error on your website';


    private function __construct(){}

    public function __destruct()
    {
        $log = self::getInstance();
        if (isset($log->db))
        {
            unset($log->db);
        }
    }

    public function __clone()
    {
        trigger_error('Cloning is not allowed. ', E_USER_ERROR);
    }

    // Logging Functions
    public static function set($msg, $level = 'Notice')
    {
        if($level != 'Notice') self::emailError($msg);
        loadFunc('randString');
        $db = self::db();
        $msg = $db->quote($msg);
        defined('LOGRAND') || define('LOGRAND', randString('4'));
        $sql = "INSERT INTO log (session, level, message) VALUES ('".LOGRAND."', '$level', $msg);";
        //echo $sql;die;
        $db->exec($sql);
    }

    protected static function gethtml($items = 20)
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

    protected static function getInstance() {
        if(self::$instance === NULL) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    protected static function db(){

        $log = self::getInstance();

        if(isset($log->db) && $log->db !== NULL) return $log->db;

        $dbname = DROOT.DIRECTORY_SEPARATOR.'tmp.db3';
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
    /**
     * Sends email message to developer
     * if message contains error pattern
     */
    protected static function emailError($msg){
        $devEmail = self::getDevEmail();

        if (empty($devEmail)) {
            return;
        }

        if(isset($_SERVER) && is_array($_SERVER)){
            $msg .= "\n".'-----------------------------------------------------';
            $msg .= "\n".'HTTP_HOST: '.self::getServerVar('HTTP_HOST');
            $msg .= "\n".'SCRIPT_NAME: '.self::getServerVar('SCRIPT_NAME');
            $msg .= "\n".'REQUEST_METHOD: '.self::getServerVar('REQUEST_METHOD');
            $msg .= "\n".'REQUEST_URI: '.self::getServerVar('REQUEST_URI');
            $msg .= "\n".'SCRIPT_FILENAME: '.self::getServerVar('SCRIPT_FILENAME');
            $msg .= "\n".'-----------------------------------------------------';
            $msg .= "\n".'HTTP_USER_AGENT: '.self::getServerVar('HTTP_USER_AGENT');
            $msg .= "\n".'HTTP_REFERER: '.self::getServerVar('HTTP_REFERER');
            $msg .= "\n".'-----------------------------------------------------';
            $msg .= "\n".'REMOTE_ADDR/IP: '.self::getServerVar('REMOTE_ADDR');
            $msg .= "\n".'-----------------------------------------------------';
        }

        /**
         * Add hight priority to email headers
         * for error messages of certain types (real errors, no notices)
         */
        $headers = '';
        $headers .= 'X-Mailer: RoboTamer'.PHP_EOL;
        $headers .= 'X-Priority: 1'.PHP_EOL;
        $headers .= 'Importance: High'.PHP_EOL;
        $headers .= 'X-MSMail-Priority: High';

        mail( $devEmail, self::EMAIL_SUBJECT, $msg, $headers);

        return;
    }

    /**
     * Get value from global $_SERVER array
     * if it exists, otherwise return 'Not Available'
     *
     * @param string $var
     * @return string value of $var or empty string
     */
    protected static function getServerVar($var){
        return (array_key_exists($var, $_SERVER)) ? $_SERVER[$var] : 'Not Available';
    }

    public static function setDevEmail($email){
        $logger = self::getInstance();
        $logger->developer_email = $email;
    }

    /**
     * Get email address of developer
     * if global constant DEVELOPER_EMAIL exists
     * then return it, otherwise return this class's
     * self::DEVELOPER_EMAIL
     *
     *
     * @return string email address of developer
     */
    protected static function getDevEmail(){
        return defined('DEVELOPER_EMAIL') ? DEVELOPER_EMAIL : self::developer_email;
    }
}
?>
