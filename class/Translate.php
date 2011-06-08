<?php defined('DROOT') || include $_SERVER['DOCUMENT_ROOT'].'/boot.php';
/**
 * Translation class
 *
 * Tanslate uses a database to store multiligual text
 *
 * As with all TaMeR libraries you will have to have a few thinks defined
 * TaMeR standard is that those are defined in the boot.php file
 * located at your servers document root.
 *
 * Prerequisites:
 * define('DROOT',SROOT.'data/');  #Database data etc
 *
 * Optional requisites:
 * You may define the debug constant to get errors and notices
 * define('DEBUG', 1);
 */


/**
 * @category   TaMeR
 * @package    Translate
 * @author     Dennis T Kaplan
 * @copyright  Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license    http://tamer.pzzazz.net/license.html
 * @link       http://tamer.pzzazz.net
 **/

class Translate {

    private $language;
    private $db;

    public function __construct()
    {
      is_object($this->db) || $this->dbConnect();
      $this->language = defined('LANGUAGE')
                  ? LANGUAGE
                  : 'en';
    }

   /**
    * Translate a string
    *
    * @param string  $code 'Code for the text to be translated'
    * @param int     $caps = 1 'Will capitalize the first letter'
    *                $caps = 2 'Will capitalize the first letter of all words'
    *
    * @return string
    */
    public function trans($code = '', $caps = false, $type = FALSE) {
        $lang = $this->language;
        $sql = "SELECT $lang FROM lang WHERE code='$code'";
        $query = $this->db->query($sql);
        $obj = $query->fetch(PDO::FETCH_OBJ);
        if( ! $obj) $obj->$lang = $code;
        if($caps == 1)
            return ucfirst($obj->$lang);
        elseif($caps == 2)
            return ucwords($obj->$lang);
        else
            return $obj->$lang;
    }

   /**
    * Get the translation for all languages
    *
    * @param  string    $code 'Code for the text to be translated'
    * @return array  'Translated text'
    */
    public function get($code) {
        $sql = "SELECT * FROM lang WHERE code='$code'";
        $query = $this->db->query($sql);
        if (numRows($query) > 0) {
            $row = $query->row();
            return (array) $row ;
        }else {
            return FALSE;
        }
    }

    public function addcode($type = '', $code = '', $english = '', $turkish = NULL) {
        $sql = "INSERT INTO lang(type,code,en,tr) VALUES ('$type', '$code', '$english', '$turkish')";
        return $this->db->prepare( $sql )->execute();
    }

    public function updatecode($code = '', $en = '', $tr = '', $de = '') {
        $sql = "UPDATE lang SET en=$en, tr=$tr, de=$de WHERE code = '$code'";
        $this->db->prepare( $sql )->execute();
    }

    public function addtrans($code, $lang, $trans) {
        $sql = "UPDATE lang SET $lang='$trans' WHERE code='$code'";
        $this->db->prepare( $sql )->execute();
    }

    public function addtable() {
        $sql = 'CREATE TABLE lang (type TEXT, code TEXT UNIQUE, en TEXT, de TEXT, tr TEXT)';
        $this->db->exec($sql);
    }

    private function dbConnect() {
        $tables = array();
        try {
            $this->db = new PDO('sqlite:'.DROOT.'language.db3');
            if(defined('DEBUG') && DEBUG !== FALSE) $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            trigger_error('PDO Sqlite3 Connection with language.db3 failed: ' . $e->getMessage());
        }
        $sql = "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
        foreach ($this->db->query($sql) as $row) $tables[] = current($row);
        if( ! in_array('lang', $tables)) {
            $this->db->exec("CREATE TABLE lang (type TEXT, code TEXT UNIQUE, en TEXT, de TEXT, tr TEXT);");
        }
    }

    private function __clone() {}
} // END Translate Class
?>
