#!/usr/bin/env php
<?php

/**
 * This file has been created to test the class argv();
 */
include_once 'argv.php';

$obj = new argv();
$obj->set_text('You must be in the MVC root folder when executing!');
$obj->set_arg('root');
$obj->set_required(true);
$obj->set_value('y');
$obj->set_text('Set config file name');
$obj->set_arg('cfg');
$obj->set_required(true);
$obj->set_value('config.php');

$result = $obj->run($argv);
$cfg = $result['cfg'];

echo "\n\n\tParsing your $cfg\n\n";
print_r($result);
echo "\n\n\tParsing END\n\n";