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

function findEmailInFileB($filename, $string) {
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

function findEmailInFile($filename, $string) {
    $file = fopen($filename, "r");
    $result = NULL;
    fgets($file);
    while(($line = fgets($file)) !== false) {
        $line = explode("|", $line);
        if($line[0] == $string) {
            $result = array('email' => $line[0], 'name' => $line[1], 'pw' => $line[2]);
            break;
        }
    }
    fclose($file);
    return $result;
}
$data = findEmailInFile("users.txt", "balls@mogus.nl");
var_dump($data);

function session_test(){
    session_start();

    $_SESSION['dest'] = "test";
    print_r($_SESSION);
}


// myWriteFile("users.txt", "TEST|TEST|TEST");
// myReadFile("users.txt");
// findEmailInFileB("users.txt", "Tobias@conceptuals.nl");
// findEmailInFileB("users.txt", "TEST");
// session_test();
findEmailInFile("users.txt", "niks@niemand.nl");

?>