<?php 


function myReadFile($filename) {
    $file = fopen($filename, "r");
    echo fread($file, filesize("users.txt"));
    fclose($file);
}

function myWriteFile($filename, $message) {
    $file = fopen($filename, "a");
    fwrite($file, $message);
    fclose($file);
}

function findStringInFile($filename, $string) {
    
}


// myWriteFile("users.txt", "\nTEST|TEST|TEST");
myReadFile("users.txt");
?>