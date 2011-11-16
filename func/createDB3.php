<?php
/**
 * Creates a the table, and the database if doesn't exist
 *
 * @param string $name 'Database name'
 * @param string $table 'Table Name'
 * @param array $rows = array('word'=>'TEXT UNIQUE');
 */
function createDB3($name,$table,$rows)
{
    $db = new dbSqlite();
    $db->setdbName($name);
    $db->setdbTable($table);
    $db->createDB($rows);
    unset($db);
}
?>
