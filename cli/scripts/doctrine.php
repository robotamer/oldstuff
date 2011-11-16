#!/usr/bin/env php
<?php
/*
 *    $ ./doctrine generate-models-yaml
 *    generate-models-yaml - Generated models successfully from YAML schema
 *
 *    $ ./doctrine generate-sql
 *    generate-sql - Generated SQL successfully for models
 *
 *    $ ./doctrine create-tables
 *    create-tables - Created tables successfully
 */

defined('ROOT') || define('ROOT', '/var/www/y');
if(is_readable(ROOT.'/boot.php')){
    include ROOT.'/boot.php';
}else{
    echo "\n\n\t\tPlease define ROOT corectly in file!\n\n";
    die ("Error" . " File: " . __FILE__ . " on line: " . __LINE__);
}

$cli = new Doctrine_Cli($doctrine_config);
$cli->run($_SERVER['argv']);



/**
./doctrine build-all
./doctrine-cli build-all-load
./doctrine-cli build-all-reload
./doctrine-cli compile
./doctrine-cli create-db
./doctrine-cli create-tables
./doctrine-cli load-data
./doctrine-cli dql
./doctrine-cli drop-db
./doctrine-cli dump-data   Dumped db data to: fixtures
./doctrine-cli generate-migration
./doctrine-cli generate-migrations-db
./doctrine-cli generate-migrations-diff
./doctrine-cli generate-migrations-models
./doctrine-cli generate-models-db
./doctrine-cli generate-models-yaml   Generated models from YAML schema
./doctrine-cli generate-sql
./doctrine-cli generate-yaml-db       Generate YAML  schema from database
./doctrine-cli generate-yaml-models   Generated YAML schema from models
./doctrine-cli migrate
./doctrine-cli rebuild-db
*/
