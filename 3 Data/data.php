<?php 
require_once("./3 Data/textfile.php");
//LOGIN
function findByEmail($filename, $string) {
    findEmailInFile($filename, $string);
}

//REGISTER
function saveInDb($filename, $message){
    writeToDb($filename, $message);
}

function findByEmailB($filename, $string) {
    findEmailInFileB($filename, $string);
}



//HOME


//ABOUT


//CONTACT


?>