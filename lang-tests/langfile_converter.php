<?php

/**
 * This will convert translation file from "constants" to "array" type
 */
$constants_file = 'en_GB.php';
$arrayIterator = new ArrayIterator ( file($constants_file) );
// example: define ( '_FPA_POSTD', 'Post Detail' );
$regexed = new RegexIterator($arrayIterator, '#define\s*\(.*,\s*\'(.*)\'\s*\)#', RegexIterator::GET_MATCH);

$out = '$language = array('.PHP_EOL;
foreach ($regexed as $line){
    $out .= '    \''.addslashes($line[1]).'\' => \''.addslashes($line[1]).'\','.PHP_EOL;
    #echo '<pre>' .print_r ( $line ,1 ). '</pre>';
}
$out .= ');';

file_put_contents('en_GB-array.php', $out);

//echo '<pre>' .print_r ( iterator_to_array($regexed) ,1 ). '</pre>';
