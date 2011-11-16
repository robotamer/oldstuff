<?php

//echo '<pre>'; var_dump(exchangeRate('TRY'));

function exchangeRate($to='TRY',$from='USD'){
	$result = FALSE;
	$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1d1t1&s='.$from.$to.'=X';
	$handle = fopen($url, 'r');
	if ($handle) {
		$result = fgetcsv($handle);
		fclose($handle);
		$result[0]=(FLOAT)$result[0];
		$d = explode('/',$result[1]);
		$result[1] = $d[2].'-'.$d[0].'-'.$d[1];
	}
	return $result;
}
?>