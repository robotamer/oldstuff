<?php

function renameArrayKey(&$array, $old, $new){
	// Rename array key but retain order of elements
	foreach($array as $key => $value) {
		if($key == $old){
			$new_array[$new] = $value;
		}else{
			$new_array[$key] = $value; }
	}
	$array = $new_array;
}