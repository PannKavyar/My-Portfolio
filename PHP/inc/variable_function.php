<?php

function answer() {
	return 42;
}

function add_up($a, $b) {
	return $a + $b;
}

$func = 'add_up';

$num = $func(10, 25);

echo $num;

?>