<?php

// function add_up($a, $b) {
// 	return $a + $b;
// }

// $value = add_up(2, 4);

// echo $value;

function add_up($a, $b) {
	$arr = array (
		$a,
		$b,
		$a + $b
	);
	return $arr;
}

$value = add_up(2, 4);

//print_r($value);
echo $value[2];

?>