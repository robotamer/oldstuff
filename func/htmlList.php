<?php

function htmlList($rows){
	$keys = array_keys($rows[0]);
	array_unshift($rows, array_combine($keys,$keys));
	$li = '<div class="ltsiderbox">'.PHP_EOL;
	$t = "\t";
	foreach($rows as $row){
		$li .= $t.'<ul>'.PHP_EOL;
		$c=0;
		foreach($row as $k=>$i){
			$c++;
			$color = 'class="color1"';
			$w='>';
			if($k == $i || $k=='Name'){
				$c++;
				$w=' style="font-weight: bold;">';
			}
			if(($c & 1)){
				$color = 'class="color2"';
			}
			$li .= $t.$t.'<li '.$color.$w.$i.'</li>'.PHP_EOL;

		}
		$li .= $t.'</ul>'.PHP_EOL;
	}
	$li .= '</div>';
	return $li;
}
?>