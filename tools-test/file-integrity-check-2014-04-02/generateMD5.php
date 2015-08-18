<?php
include 'libraries/integrity.md5.class.php';

$integrity = new integrity('path/to/check/directory');

if($integrity->getMd5Hashes('hash.md5') > 0){
    echo "MD5 File Hashes Generated!";
} else {
    echo "Error!";
}
?>
