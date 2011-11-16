#!/ramdisk/bin/php5-cli -q
<?php

// Database
$DB_USER="geronim2";
$DB_PASS="coreteam";
$DB_NAME="drp01";
$NAME      = 'geronimo';
$BACKUPDIR = '/public_html/members/';

$DB_NAME  = $DB_USER.'_'.$DB_NAME;
$now      = time();
$HOME     = $_SERVER["HOME"];
$DEST     = $HOME . '/backup/' . $NAME; //Destination Folder
$BIN      = $HOME . '/backup/bin/';
$SOURCE   = $HOME . $BACKUPDIR ;
$EXCLUDES = $BIN  . $NAME.'.cfg';
$DATA     = $BIN  . $NAME.'.data';


if (!is_dir($DEST)) {
   mkdir($DEST,0700,true);
   touch($EXCLUDES);
   chmod($EXCLUDES, 0600);
   echo PHP_EOL."Files & directory created! Please edit your $EXCLUDES file and run again.".PHP_EOL;
   exit;
}

if(!is_file($DATA)){
   touch($DATA);
   chmod($DATA, 0600);
   $data['nextweek'] = $now + (7 * 24 * 60 * 60);
   $data['nextday']  = $now + (24 * 60 * 60);
   $data['nexthour'] = $now + 3000;
   echo "Database setup done! \n Please wait, backing up! \n\n\n";
}
$data = unserialize(file_get_contents($DATA));

if ($now > $data['nextweek']) {
   $data['nextweek'] = $now + (7 * 24 * 60 * 60);

   if (is_file("$DEST/week.3.sql.bz2")) shell_exec("mv -f $DEST/week.3.sql.bz2 $DEST/week.4.sql.bz2");
   if (is_file("$DEST/week.2.sql.bz2")) shell_exec("mv -f $DEST/week.2.sql.bz2 $DEST/week.3.sql.bz2");
   if (is_file("$DEST/week.1.sql.bz2")) shell_exec("mv -f $DEST/week.1.sql.bz2 $DEST/week.2.sql.bz2");
   if (is_file("$DEST/day.2.sql.bz2")) shell_exec("mv -f $DEST/day.2.sql.bz2 $DEST/week.1.sql.bz2");

   if (is_file("$DEST/week.3.tar.bz2")) shell_exec("mv -f $DEST/week.3.tar.bz2 $DEST/week.4.tar.bz2");
   if (is_file("$DEST/week.2.tar.bz2")) shell_exec("mv -f $DEST/week.2.tar.bz2 $DEST/week.3.tar.bz2");
   if (is_file("$DEST/week.1.tar.bz2")) shell_exec("mv -f $DEST/week.1.tar.bz2 $DEST/week.2.tar.bz2");
   if (is_file("$DEST/day.4.tar.bz2"))  shell_exec("mv -f $DEST/day.4.tar.bz2  $DEST/week.1.tar.bz2");
}

if ($now > $data['nextday']) {
   $data['nextday'] = $now + (24 * 60 * 60);

   if (is_file("$DEST/day.3.sql.bz2"))    shell_exec("mv -f $DEST/day.3.sql.bz2 $DEST/day.4.sql.bz2");
   if (is_file("$DEST/day.2.sql.bz2"))    shell_exec("mv -f $DEST/day.2.sql.bz2 $DEST/day.3.sql.bz2");
   if (is_file("$DEST/day.1.sql.bz2"))    shell_exec("mv -f $DEST/day.1.sql.bz2 $DEST/day.2.sql.bz2");
   if (is_file("$DEST/hour.4.sql.bz2")) shell_exec("mv -f $DEST/hour.4.sql.bz2 $DEST/day.1.sql.bz2");

   if (is_file("$DEST/day.3.tar.bz2"))    shell_exec("mv -f $DEST/day.3.tar.bz2 $DEST/day.4.tar.bz2");
   if (is_file("$DEST/day.2.tar.bz2"))    shell_exec("mv -f $DEST/day.2.tar.bz2 $DEST/day.3.tar.bz2");
   if (is_file("$DEST/day.1.tar.bz2"))    shell_exec("mv -f $DEST/day.1.tar.bz2 $DEST/day.2.tar.bz2");
   if (is_file("$DEST/hour.4.tar.bz2")) shell_exec("mv -f $DEST/hour.4.tar.bz2 $DEST/day.1.tar.bz2");
}

if ($now > $data['nexthour']) {

   $data['nexthour'] = $now + (60 * 60);
   $data['count']    = $data['count'] + 1;
   $data['last']     = $now;

   if (is_file("$DEST/hour.3.sql.bz2")) shell_exec("mv $DEST/hour.3.sql.bz2 $DEST/hour.4.sql.bz2");
   if (is_file("$DEST/hour.2.sql.bz2")) shell_exec("mv $DEST/hour.2.sql.bz2 $DEST/hour.3.sql.bz2");
   if (is_file("$DEST/hour.1.sql.bz2")) shell_exec("mv $DEST/hour.1.sql.bz2 $DEST/hour.2.sql.bz2");
   if (is_file("$DEST/hour.0.sql.bz2")) shell_exec("mv $DEST/hour.0.sql.bz2 $DEST/hour.1.sql.bz2");

   if (is_file($DEST.'/hour.3.tar.bz2')) shell_exec("mv $DEST/hour.3.tar.bz2 $DEST/hour.4.tar.bz2");
   if (is_file($DEST.'/hour.2.tar.bz2')) shell_exec("mv $DEST/hour.2.tar.bz2 $DEST/hour.3.tar.bz2");
   if (is_file($DEST.'/hour.1.tar.bz2')) shell_exec("mv $DEST/hour.1.tar.bz2 $DEST/hour.2.tar.bz2");
   if (is_dir($DEST.'/hour.0')) shell_exec("tar cjf hour.1.tar.bz2 $DEST/hour.0/");
   if (is_dir($DEST.'/hour.0')) shell_exec("rm -r $DEST/hour.0/");
   
   shell_exec("mysqldump --user=$DB_USER --password=$DB_PASS --opt $DB_NAME | bzip2 > $DEST/hour.0.sql.bz2");
   shell_exec("cp -a $SOURCE $DEST/hour.0/");
   
   //shell_exec("rsync -va --delete --delete-excluded --exclude-from=$EXCLUDES $SOURCE $DEST/hour.0");
   //shell_exec("chmod -R u+w $DEST/hour.0");
   if (is_dir($DEST.'/hour.0')) shell_exec("touch $DEST/hour.0");
}

file_put_contents($DATA, serialize($data));
?>

