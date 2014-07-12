<?php


$arrayURL = 'https://opentranslators.transifex.com/projects/p/fpa/resource/test-array-file/l/en/download/for_use/';
$constantURL = 'https://opentranslators.transifex.com/projects/p/fpa/resource/test-constant-file/l/en/download/for_use/';


$test = file_get_contents($arrayURL);

echo '<pre>' .print_r ( $test, 1 ). '</pre>';