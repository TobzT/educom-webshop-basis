<?php 


function myReadFile($filename) {
    $file = fopen($filename, "r");
    echo fread($file, filesize("users.txt"));
    fclose($file);
}

function myWriteFile($filename, $message) {
    $file = fopen($filename, "a");
    fwrite($file, $message . "\n");
    fclose($file);
}

function findEmailInFile($filename, $string) {
    $file = fopen($filename, "r");
    $result = false;
    fgets($file);
    while(($line = fgets($file)) !== false) {
        // echo($line);
        $line = explode("|", $line);
        // echo($line[0]."\n");
        if($line[0] == $string) {
            $result = true;
            break;
        }
    }

    fclose($file);
    return $result;
}

function session_test(){
    session_start();

    $_SESSION['dest'] = "test";
    print_r($_SESSION);
}


// myWriteFile("users.txt", "TEST|TEST|TEST");
// myReadFile("users.txt");
// findEmailInFile("users.txt", "Tobias@conceptuals.nl");
// findEmailInFile("users.txt", "TEST");
session_test();

?>