<?php
function printdb() {
	$preO = $preC = '';
	if(PHP_SAPI!='cli'){
		$preO = '<pre>';
		$preC = '</pre>';
	}
	echo PHP_EOL.$preO.var_dump(func_get_args()).$preC.PHP_EOL;
}
?>
