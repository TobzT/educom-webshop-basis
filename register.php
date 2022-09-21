<?php 
require_once("contact.php");
function showRegisterContent() {
    $data = array("valid" => true, "name" => "", "nameErr" => "", "pw" => "", "cpw" => "", "pwErr" => "", "email" => "", "emailErr" => "");
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        $data = validateRegistration(".\users\users.txt");
    }
    
    showFormStart();

    showFormItem("name", "text", "Naam:", $data["name"], $data["nameErr"]);
    showFormItem("email", "email", "E-mail:", $data["email"], $data["emailErr"]);
    showFormItem("pw", "password", "Password:", $data["pw"], $data["pwErr"]);
    showFormItem("cpw", "password", "Confirm Password:", $data["cpw"], "");
    showFormEnd("register", "Sign Up");
}

function validateRegistration($filename) {
    $nameErr = $pwErr = $emailErr = "";
    $valid = true;
    $name = test_inputs(getVarFromArray($_POST, "name"));
    $email = strtolower(test_inputs(getVarFromArray($_POST, "email")));
    $pw = test_inputs(getVarFromArray($_POST, "pw"));
    $cpw = test_inputs(getVarFromArray($_POST, "cpw"));

    if(empty($name)){
        $valid = false;
        $nameErr = "Please enter your name.";
    }
    
    if (empty($pw)) {
        $valid = false;
        $pwErr = "Please enter your password.";
    }

    if (empty($email)) {
        $valid = false;
        $emailErr = "Please enter your e-mail.";
    }

    if ($pw !== $cpw){
        $valid = false;
        $pwErr = "Passwords do not match";
    }

    if (findEmailInFile($filename, $email)) {
        $valid = false;
        $emailErr = "E-mail found in database";
    }

    
    if ($valid) {
        $message = $email . "|" . $name . "|" . $pw;
        myWriteToFile($filename, $message);
    }
    
    return array("valid" => $valid, "name" => $name, "nameErr" => $nameErr, "pw" => $pw, "cpw" => $cpw, "pwErr" => $pwErr, "email" => $email, "emailErr" => $emailErr);

}

function myWritetoFile($filename, $message) {
    $file = fopen($filename, "a");
    fwrite($file, $message . "\n");
    fclose($file);
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
?>