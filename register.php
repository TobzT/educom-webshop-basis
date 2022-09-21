<?php 
require_once("contact.php");
function showRegisterContent() {
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        validateRegistration();
    }
    
    showFormStart();

    showFormItem("name", "text", "Naam:", "", "");
    showFormItem("email", "email", "E-mail:", "", "");
    showFormItem("pw", "password", "Password:", "", "");
    showFormItem("cpw", "password", "Confirm Password:", "", "");
    showFormEnd("register", "Sign Up");
}

function validateRegistration($filename) {
    $pwErr = $emailErr = "";
    $valid = true;
    $name = test_inputs(getVarFromArray($_POST, "name"));
    $email = strtolower(test_inputs(getVarFromArray($_POST, "email")));
    $pw = test_inputs(getVarFromArray($_POST, "pw"));
    $cpw = test_inputs(getVarFromArray($_POST, "cpw"));

    if ($pw !== $cpw){
        $valid = false;
        $pwErr = "Passwords do not match";
    }

    if (findEmailInFile("users.txt", $email)) {
        $valid = false;
        $emailErr = "E-mail found in database";
    }

    
    if ($valid) {
        myWriteToFile($filename, $message);
    }
    
    return array("valid" => $valid, "pwErr" => $pwErr, "emailErr" => $emailErr);

}

function myWriteFile($filename, $message) {
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