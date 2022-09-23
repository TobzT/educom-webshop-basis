<?php 
require_once('./3 Data/data.php');

function getGenders() {
    define("GENDERS", array("male" => "Dhr",
                        "female" => "Mvr",
                        "other" => "Anders"));
    return GENDERS;
}

function getOptions() {
    define("OPTIONS", array("tlf" => "Telefoon",
                        "email" => "E-mail"
                        ));
    return OPTIONS;
}



function getVarFromArray($array, $key, $default = NULL) {
    return isset($array[$key]) ? $array[$key] : $default;
}



//LOGIN
function getLoginData() {
    $data = array("valid" => false, "pw" => "", "pwErr" => "", "email" => "", "emailErr" => "", 'name' => "");
    $request_type = $_SERVER["REQUEST_METHOD"];

    if($request_type == "POST") {

        $data = validateLogin(".\users\users.txt");
        
    }
    return $data;
}

function validateLogin($filename) {
    $emailErr = $pwErr = $name = "";
    $valid = true;
    $email = test_inputs(strtolower(getVarFromArray($_POST, 'email')));
    $pw = test_inputs(getVarFromArray($_POST, 'pw'));
    $data = findByEmail($filename, $email);
    if(!empty($data)) {
        if(!empty($data['name'])){
            $name = $data['name'];
        }
    
        if(empty($pw)) {
            $valid = false;
            $pwErr = 'Please enter a password.';
        } elseif (test_inputs($data['pw']) !== $pw) {
            $valid = false;
            $pwErr = 'Password does not match';
        }
    } else {
        $valid = false;
        $emailErr = 'E-mail is not registered.';
    }
    


    return array('valid' => $valid, 'email' => $email, 'emailErr' => $emailErr ,'pw' => $pw, 'pwErr' => $pwErr, 'name' => $name);

}

function doLogIn($data) {
    
    $_SESSION['username'] = $data['name'];
    $_SESSION['loggedin'] = true;
}

function doLogOut() {
    $_SESSION['username'] = NULL;
    $_SESSION['loggedin'] = false;
}

//REGISTER
function getRegisterData() {
    $data = array("valid" => NULL, "name" => NULL, "nameErr" => NULL, "pw" => NULL, "cpw" => NULL, "pwErr" => "", "email" => NULL, "emailErr" => "");
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        $data = validateRegistration(".\users\users.txt");
    }
    return $data;
}



function validateRegistration($filename) {
    $nameErr = $pwErr = $emailErr = NULL;
    $valid = true;
    $name = test_inputs(getVarFromArray($_POST, "name"));
    $email = strtolower(test_inputs(getVarFromArray($_POST, "email")));
    $pw = test_inputs(getVarFromArray($_POST, "pw"));
    $cpw = test_inputs(getVarFromArray($_POST, "cpw"));

    if(empty($name)) {
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

    if (findByEmailB($filename, $email)) {
        $valid = false;
        $emailErr = "E-mail found in database";
    }

    
    if ($valid) {
        $message = $email . "|" . $name . "|" . $pw;
        saveInDb($filename, $message);
        
    }
    
    return array("valid" => $valid, "name" => $name, "nameErr" => $nameErr, "pw" => $pw, "cpw" => $cpw, "pwErr" => $pwErr, "email" => $email, "emailErr" => $emailErr);

}



//HOME


//ABOUT


//CONTACT
function test_inputs($data) {
    if(!empty($data)){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
    }
    
    return $data;
}

function showGenericForm($page, $data) {
    
    $GENDERS = getGenders();
    $OPTIONS = getOptions();
    showFormStart();
    
    showFormItem("gender", "dropdown", "Gender:", $data["gender"], "", $GENDERS);
    showFormItem("name", "text", "Name:", $data["name"], $data["nameErr"]);
    showFormItem("email", "email", "E-mail adres:", $data["email"], $data["emailErr"]);
    showFormItem("tlf", "text", "Telefoonnummer: ", $data["tlf"], $data["tlfErr"]);
    showFormItem("pref", "radio", "Communicatievoorkeur: ", $data["pref"], $data["prefErr"], $OPTIONS);
    showFormItem("text", "textarea", "", $data["text"], "");

    showFormEnd($page, "Submit");
}

function showFormItem($key, $type, $labeltext, $value, $error, $options=NULL) {
    
    echo('<div>
        <label for="'.$key.'">'.$labeltext.'</label>'
    );
    
    switch ($type) {
        case "dropdown":
            echo('
                    <select name="'.$key.'" id="'.$key.'" >');

            echo(repeatingForm($options, $value));

            echo('</select>');
            break;
        
        case "radio":
            echo('
                <p><h3 class="error"> '. $error .'</h3></p>
            ');

            echo(repeatingRadio($key, $options));

            break;
        
        case "textarea":
            echo('
                
                <textarea class=input name="'.$key.'" cols="40" rows="10"></textarea>

                
            ');
            break;
        
        default:
            echo('
                    <input class="input" type="'.$type.'" id="'.$key.'" name="'.$key.'" value="'. $value .'">
                    
                    <h3 class="error">'.$error.'</h3>
                
            ');
            break;
    }
    echo('</div><br>');
}

function repeatingForm($options, $value) {
    
    $count = count($options);
    $keys = array_keys($options);
    for ($i = 0; $i < $count; $i++) {
        echo('<option value="'.$keys[$i].'"'.(($value == $keys[$i]) ? "selected" : "").'>'.$options[$keys[$i]].'</option><br>');
    }
}

function repeatingRadio($key, $options) {
    $data = getContactData();
    $count = count($options);
    $keys = array_keys($options);
    
    for($i = 0; $i < $count; $i++) {
        $checked = radioCheck($options, $keys, $i, $data);
        echo('
            <input type="radio" id="" name="'.$key.'" value="'.$options[$keys[$i]].'"'.$checked.'>
            <label for="'.$key.'">'.$options[$keys[$i]].'</label><br>
        ');
    }
    
}

function radioCheck($options, $keys, $i, $data) {
    return ($data['pref'] == $options[$keys[$i]]) ? "checked" : "";
}



function validateContactForm($data) {
    $request_type = $_SERVER["REQUEST_METHOD"];
    if($request_type == "POST") {
        
        $data['valid'] = true;
        $data['gender'] = test_inputs(getVarFromArray($_POST, "gender"));
        
        if(empty(test_inputs(getVarFromArray($_POST, "name")))) {
            $data['nameErr'] = "Je moet je naam invullen.";
            $data['valid'] = false;
        } else {
            $data['name'] = test_inputs(getVarFromArray($_POST, "name"));
        }


        if(empty(test_inputs(getVarFromArray($_POST, "email")))) {
            $data['emailErr'] = "Je moet je e-mail adres invullen.";
            $data['valid'] = false;
        } else {
            $data['email'] = test_inputs(getVarFromArray($_POST, "email"));
        }

        if(empty(test_inputs(getVarFromArray($_POST, "tlf")))) {
            $data['tlfErr'] = "Je moet je telefoonnummer invullen.";
            $data['valid'] = false;
        } else {
            $data['tlf'] = test_inputs(getVarFromArray($_POST, "tlf"));
        }

        if(empty(getVarFromArray($_POST, "pref"))) {
            $data['prefErr'] = "Je moet een voorkeur kiezen.";
            $data['valid'] = false;
        } else {
            $data['pref'] = test_inputs(getVarFromArray($_POST, "pref"));
        }

        $data['text'] = test_inputs(getVarFromArray($_POST, "Text1"));
    }
    return $data;
}

function getContactData() {
    $data = array('valid' => NULL, 'gender' => NULL, 'name' => NULL, 'nameErr' => NULL, 'email' => NULL, 'emailErr' => NULL, 'tlf' => NULL, 'tlfErr' => NULL, 'pref' => NULL, 'prefErr' => NULL, 'text' => NULL);
    
    return validateContactForm($data);
}
?>