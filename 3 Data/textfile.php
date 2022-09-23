<?php 
function writetoDb($filename, $message) {
    $file = fopen($filename, "a");
    fwrite($file, $message . "\n");
    fclose($file);
}

// returns true if email in file
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
?>