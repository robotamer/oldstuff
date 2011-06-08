<?php

/**
 *
 * Monitor memory usage
 *
 * Including and registering the function at the top of your script
 * Then placing the following at the very end of the script allows
 * you to monitor its memory usage
 *
 * <code>echo profiler(true);</code>
 *
 * @return int (bytes)
 */

function profiler($return=false)
{
   static $mem=0;
   if($return) return $mem.' max. bytes used!'.PHP_EOL;
   if( ( $m=memory_get_usage() ) > $mem ) $mem = $m;
}

register_tick_function('profiler');
declare(ticks=1);

?>