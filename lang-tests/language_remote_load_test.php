<?php


$arrayURL = 'https://raw.githubusercontent.com/btoplak/FPA/FPA_2.0_Development/lang-tests/en_GB-array.php';
$constantURL = 'https://github.com/btoplak/FPA/blob/FPA_2.0_Development/lang-tests/en_GB.php';


$test = file_get_contents($arrayURL);

echo '<pre>' .print_r ( $test, 1 ). '</pre>';