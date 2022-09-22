<?php 
require_once("./3 Data/textfile.php");
//LOGIN


//REGISTER
function saveInDb($filename, $message){
    writeToDb($filename, $message);
}

    // returns true if email in file
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

//HOME


//ABOUT


//CONTACT


?>