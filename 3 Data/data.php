<?php 
require_once("./3 Data/textfile.php");
//LOGIN
function findByEmail($filename, $email) {
    return findEmailInFile($filename, $email);
}

//REGISTER
function saveInDb($filename, $message){
    return writeToDb($filename, $message);
}

function findByEmailB($filename, $string) {
    return findEmailInFileB($filename, $string);
}



//HOME


//ABOUT


//CONTACT


?>