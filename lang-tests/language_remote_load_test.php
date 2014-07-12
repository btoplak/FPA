<?php


//$arrayURL = 'https://raw.githubusercontent.com/btoplak/FPA/FPA_2.0_Development/lang-tests/en_GB-array.php';
$arrayURL = 'en_GB-array.php';
$constantURL = 'https://raw.githubusercontent.com/btoplak/FPA/FPA_2.0_Development/lang-tests/en_GB.php';

file_put_contents('lang.inc', file_get_contents($arrayURL));
require 'lang.inc';
echo '<pre>' .print_r ( $language, 1 ). '</pre>';

file_put_contents('lang2.inc', file_get_contents($constantURL));
require 'lang2.inc';
echo _PHP_DISERR;