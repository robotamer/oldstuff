<?php
function timer() {
	$mtime = microtime(TRUE);
	if(defined('TIMERTOTAL')) {
		echo 'Time Back Trace<pre>';
		print_r(debug_backtrace());
		exit;
	}
	if( ! defined('TIMERSTART')) {
		define('TIMERSTART', $mtime);
	}else{
		$mtime = round(($mtime - TIMERSTART), 4);
		define('TIMERTOTAL', $mtime);
	}
	return $mtime;
}
?>