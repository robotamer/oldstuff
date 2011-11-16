<?php // model

/**
 * @name      model
 * @package   phpcli
 * @copyright (C) 2009-2010 Dennis T Kaplan
 * @license   GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @author    Dennis T Kaplan
 * @todo      
 */

/*
 * Use : php zend/model.php -path=/var/www/TaMeR/data/tmp.db3 -table=users -code=finder
 */


error_reporting(); @ini_set('display_errors', '0');
defined('ROOT') ||  define('ROOT', realpath(dirname(__FILE__).'/../'));
include_once ROOT.'/class/argv.php';

$obj = new argv();
$obj->set_text('Set DATABASE path');
$obj->set_arg('path');
$obj->set_required(true);

$obj->set_text('Set table name');
$obj->set_arg('table');
$obj->set_required(TRUE);

$obj->set_text('What code to generate');
$obj->set_arg('code');
$obj->set_required(false);
$obj->set_value('model');
$obj->set_value('saver');
$obj->set_value('finder');
$obj->set_value('columns');

$result = $obj->run($argv);

$path  = $result['path'];
$table = $result['table'];
$code = $result['code'];

$db = NULL;
try {
    $db = new PDO('sqlite:'.$path);
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    trigger_error('PDO Sqlite3 Connection with '.$path.' failed: ' . $e->getMessage());
}

$sql = "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
foreach ($db->query($sql) as $row) $tables[] = current($row);
if( ! in_array($result['table'], $tables)) {
    echo "\n\n\tTable '". $table . "' not availabe in database ". $path." \n\n";
}

foreach ($db->query("pragma table_info($table)") as $row) {
    $columns[] = $row;
}

if($code == 'saver'){
    $file = saver($table, $columns);
}elseif($code == 'finder'){
    $file = finder($table, $columns);
}elseif($code == 'columns'){
    $file = columns($table, $columns);
}else{
    $file = model($table, $columns);
}

echo $file;


function model($table, $columns){
    $file = "\n\n ============== Veriable Definition Start ============== \n\n";
    $file .= 'class _Model_'.$table."\n{\n";
    foreach($columns as $value) {
        $file .= "\t".'protected $_'.$value['name'].";\n";
    }
    $file .= "\n\n ============== Veriable Definition End ============== \n\n";
    $file .= "\n\n ============== Set & Get Functions Start ============== \n\n";
    foreach($columns as $value) {
        $name = $value['name'];
        $type = $value['type'];
        if($type == 'INTEGER'){
            $type = '(int)';
        }elseif($type == 'TEXT') {
            $type = '(string)';
        }elseif($type == 'REAL') {
            $type = '(float)';
        }else{
            $type = '(string)';
        }
        $file .= "\t".'public function set'.ucfirst($name).'($'.$name."){\n";
        $file .= "\t\t".'$this->_'.$name." = $type ".'$'.$name.";\n";
        $file .= "\t\t".'return $this;'."\n";
        $file .= "\t}\n\n";

        $file .= "\t".'public function get'.ucfirst($name)."(){\n";
        $file .= "\t\t".'return $this->_'.$name.";\n";
        $file .= "\t}\n\n";
    }
    $file .= "\n\n ============== Set & Get Functions End ============== \n\n";

    return $file;
}


function saver($table, $columns) {
    $file = "\n\n ============  Mepper save Start =============\n\n";
    $file .= '$data = array('."\n";
    foreach($columns as $value) {
        $name = $value['name'];
        $file .= "\t'$name' => ".'$'.$table.'->get'.ucfirst($name)."(),\n";
    }
    $file .= ");\n";
    $file .= "\n\n ============  Mepper save End =============\n\n";
    return $file;
}

function finder($table, $columns){
    $file = "\n\n ============  Mepper find Start =============\n\n";
    $file .= '$'.$table;
    foreach($columns as $value) {
        $name = $value['name'];
        $file .= "->set".ucfirst($name).'($row->'.$name.")\n";
    }
     $file .= ";\n";
    $file .= "\n\n ============  Mepper find End =============\n\n";
    return $file;
}


function columns($table, $columns){
    $file = "\n\n ============  columns Start =============\n\n";
    $file .= 'return array(';
    foreach($columns as $value) {
        $name = $value['name'];
        $file .= "'$name' => '$name',\n\t";
    }
     $file .= ");\n";
    $file .= "\n\n ============  columns End =============\n\n";
    return $file;
}