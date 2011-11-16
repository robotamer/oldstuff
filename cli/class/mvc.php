#!/usr/bin/env php
<?php
/**
 * An open source application development framework for PHP 5.2 or newer
 *
 * @package      TaMeR
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 */

/**
 *
 * Description: Creates the PHP TaMeR Framework folder and file structure
 *
 * @author     Dennis T Kaplan
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
//print_r($result);

echo "\n\n\tParsing $cfg \n\n";
$c = new mvc($cfg);
echo "\n\n\tParsing DONE\n\n";

class mvc {

    private $cfg;

    public function __construct($cfg) {
        $root = getcwd();
        include $root . '/'.$cfg;
        $this->cfg = $config;
        $www = $this->cfg['www_path'] ;
        self::mkBoot($www);
        self::mkDataFolder($root);
        self::mkClassFiles($root, 'lobby');
        foreach($this->cfg['apps'] as $app) {
            $wwwfolder = $this->cfg['www_path'] .'/'. $app;
            $mvcfolder = $root .'/'. $app;
            self::mkBoot($wwwfolder);
            //self::mkIndexFile($wwwfolder, $app);
            self::mkClassFiles($mvcfolder, $app);
        }
    }
    public static function mkBoot($folder, $root = false) {
        if($root === false) {
            $root = getcwd();
        }
        self::mkFolder($folder);
        $boot = $folder .'/index.php';
        $fp = fopen($boot, 'w');
        fwrite($fp, '<?php' . PHP_EOL);
        fwrite($fp, "include '$root/boot.php';". PHP_EOL);
        fwrite($fp, "YRoute::dispatch();");
        fclose($fp);
        echo "creating $boot".PHP_EOL;
    }
    private function mkFolder($folder) {
        if(!is_dir($folder)) {
            mkdir($folder, 0755, true);
            echo "creating $folder".PHP_EOL;
        }
    }
    private function mkIndexFile($folder,$app){
        $file = $folder .'/index.php';
        if(!is_file($file)) {;
            $fp = fopen($file, 'w');
            fwrite($fp, '<?php' . PHP_EOL. PHP_EOL);
            fwrite($fp, "include 'boot.php';". PHP_EOL. PHP_EOL);
            fclose($fp);
            echo "creating $file".PHP_EOL;
        }
    }
    private function mkDataFolder($folder) {
        $folder = $folder .'/'.'data';
        if(!is_dir($folder)) {
            mkdir($folder, 0777, true);
            chmod("$folder", 0777);
            echo "creating $folder".PHP_EOL;
        }
    }
    private function mkClassFiles($folder, $app){
        $file = $folder .'/indexC.php';
        if(!is_dir($folder)){
            self::mkFolder($folder);
        }
        if(!is_file($file)) {;
            $fp = fopen($file, 'w');

            self::mkClassFile($fp, $app);
            fclose($fp);
            echo "creating $file".PHP_EOL;
        }
    }
    private function mkClassFile($fp, $app) {
        fwrite($fp, '<?php' . PHP_EOL. PHP_EOL);
        fwrite($fp, 'class indexController extends YController {'.PHP_EOL. PHP_EOL);
        fwrite($fp, "\tpublic function __construct() {". PHP_EOL);
        fwrite($fp, "\t\tparent::__construct();".PHP_EOL);
        fwrite($fp, "\t}".PHP_EOL);
        fwrite($fp, "\tpublic function indexAction() {".PHP_EOL);
        fwrite($fp, "\t\t".'$this->view->headLink()->appendStylesheet("/css/right.css");'. PHP_EOL);
        fwrite($fp, "\t\t".'$this->view->title = "'.ucfirst($app).'";'.PHP_EOL);
        fwrite($fp, "\t\t".'$this->view->content = printa($_SERVER,true);'.PHP_EOL);
        fwrite($fp, "\t}".PHP_EOL."}");
    }
    private function mkLayoutFile($root) {
        $file = $root .'/layout.phtml';
        if(!is_dir($root)){
            self::mkFolder($root);
        }
        if(!is_file($file)) {;
            $fp = fopen($file, 'w');
            fwrite($fp, '<?=$this->doctype()?>' . PHP_EOL. PHP_EOL);
            fwrite($fp, '<html xmlns="http://www.w3.org/1999/xhtml">'.PHP_EOL. PHP_EOL);
            fwrite($fp, "\t<head>". PHP_EOL);
            fwrite($fp, '<?=$this->headTitle(). PHP_EOL ?>'. PHP_EOL);
            fwrite($fp, '<?=$this->headMeta(). PHP_EOL ?>'. PHP_EOL);
            fwrite($fp, '<?=$this->headLink(). PHP_EOL?>'. PHP_EOL);
            fwrite($fp, '<?=$this->headScript(). PHP_EOL?>'. PHP_EOL);
            fwrite($fp, '</head>'. PHP_EOL);
            fwrite($fp, '<body>'. PHP_EOL);
            fwrite($fp, '<?=$this->content?>'. PHP_EOL);
            fwrite($fp, '</body>'. PHP_EOL);
            fwrite($fp, '</html>'. PHP_EOL);
            fclose($fp);
            echo "creating $file".PHP_EOL;
        }
    }
}
