#!/usr/bin/php
<?php
/**
 * An open source PHP library collection
 *
 * @category     RoboTamer
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license      http://www.RoboTamer.com/license.html
 * @link         http://www.RoboTamer.com
 **/

if(PHP_SAPI == 'cli')
{
    $options = getopt('i:');
    $inifile = $options['i'];
    $bak = new phpBackup($inifile);
    $bak->run();
}

/**
 * Simply spesify some vars in an ini file and let RTBackup() do the job.
 * uses rsync!
 *
 * @category     RoboTamer
 * @package      RTBackup
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license      http://www.RoboTamer.com/license.html
 * @link         http://www.RoboTamer.com
 * @todo         keep backup intervals
 **/
class RTBackup {

    const logFile      = 'bak_log';
    const dbFile       = 'bak_array.php';
    const excludeFile  = 'bak_excludes.cfg';

    public function __construct($inifile = NULL) {
        if($inifile !== NULL) $this->setINI($inifile);
    }

    public function run(){
        $this->time = time();
        if(!isset($this->destination)) {
			echo PHP_EOL."\t You have to provide an ini file or set the vars manualy!".PHP_EOL.PHP_EOL;
			die;
        }
        $this->setdb();
        $this->setExcludes();
        if(!isset($this->data['count'])) $this->data['count'] = 0;
        $this->data['next']  = $this->time + (60 * 60 * 24);
        $this->data['last']  = $this->time;
        $this->data['nextdate'] = date('c',$this->data['next']);
        $this->data['lastdate'] = date('c',$this->time);
        $this->data['count'] = $this->data['count'] + 1;
        $this->logger('Backup Start');
        $destination = $this->destination.DIRECTORY_SEPARATOR.'bak0'.DIRECTORY_SEPARATOR;
        $source = $this->source.DIRECTORY_SEPARATOR;
        echo PHP_EOL."\t Backing up now, please hold".PHP_EOL.PHP_EOL;
		var_export($this);
        echo exec("nice rsync -va --delete --delete-excluded --exclude-from=$this->excludes $source $destination",$output);
        foreach($output as $v) $this->logger('rsync: '.$v);
        unset($output);
        exec("chmod -R u+w $destination",$output);
        foreach($output as $v) $this->logger('chmod: '.$v);
        $this->putData($this->data);
        echo PHP_EOL.PHP_EOL."\t Backup succesful".PHP_EOL.PHP_EOL;
    }

    public function setINI($file){
        if(!is_file($file)) {
            echo PHP_EOL."\t Could not find your ini file at: $file ".PHP_EOL.PHP_EOL;
            die;
        }
        $cfg = parse_ini_file($file);
        $requiered = array('NAME','SOURCE','DESTINATION');
        foreach($requiered as $k=>$v){
            if(!isset($cfg[$v])) {
                trigger_error($v.' in ini file not defined');
                die;
            }
        }
        $this->setName($cfg['NAME']);
        $this->setSource($cfg['SOURCE']);
        $this->setDestination($cfg['DESTINATION']);
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setSource($dir){
        if(!is_dir($dir)) {
            trigger_error(PHP_EOL."\t".$dir.' does not exit!'.PHP_EOL.PHP_EOL);
            die;
        }
        $this->source = $dir;
    }

    public function setDestination($dir){
        if(!is_dir($dir.DIRECTORY_SEPARATOR.$this->name)) {
           mkdir($dir.DIRECTORY_SEPARATOR.$this->name,0775,true);
        }
        $this->destination = $dir.DIRECTORY_SEPARATOR.$this->name;
    }

    protected function setExcludes(){
        $this->excludes = $this->destination.DIRECTORY_SEPARATOR.self::excludeFile;
		if(!is_file($this->excludes)) {
            touch($this->excludes);
            chmod($this->excludes, 0664);
            echo PHP_EOL."\t Please edit your $this->excludes file".PHP_EOL.PHP_EOL;
            die;
        }
    }

    protected function setdb(){
        $this->db = $this->destination.DIRECTORY_SEPARATOR.self::dbFile;
        if(is_file($this->db)) $this->data = include $this->db;
    }

    public function __destruct() {
        self::logger('Backup End');
    }

    protected function logger($msg){
        $TAB = "\t";
        if(!isset($this->destination)) {
            die;
        }
        $file = $this->destination . DIRECTORY_SEPARATOR. self::logFile;
        $count = isset($this->data['count']) ? $this->data['count'] :  NULL;
        $line = date('c').$TAB.$count.$TAB.$msg.PHP_EOL;
        file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
    }

    protected function putData($data){
        $string = var_export($data,true);
        file_put_contents($this->db,'<?php return '.$string.'; ?>', LOCK_EX);
    }
}
?>
