<?php 
require_once('./3 Data/data.php');

function getGenders() {
    return array("male" => "Dhr",
                "female" => "Mvr",
                "other" => "Anders");
}

function getOptions() {
    return array("tlf" => "Telefoon",
                "email" => "E-mail");
}



function getVarFromArray($array, $key, $default = NULL) {
    return isset($array[$key]) ? $array[$key] : $default;
}
//DATA
function getData($page) {
    $data = array('page' => $page, "valid" => NULL, 'errors' => array(), 'values' => array());
    $data['meta'] = getMetaData($page);
    switch($page) {
        case 'register':
            
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                $data = validateRegistration(".\users\users.txt");
            }
            return $data;

        case 'contact':
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                $data = validateForm($data);
            }

            return $data;
            
        case 'login':
            // print_r($data['meta']);

            // if($_SERVER['REQUEST_METHOD'] == "POST") {

            //     $data = validateLogin(".\users\users.txt");
                
            // }
            return $data;
    }
}

function getMetaData($page) {
    switch($page) {
        case 'login':
            return array(
                'email' => array('label' => 'E-mail: ', 'type' => 'email', 'validations' => array('validEmail')),
                'pw' => array('label' => 'Password: ', 'type' => 'password', 'validations' => array('correctPassword'))
            );
    
        case 'register':
            return array(
                'name' => array('label' => 'Name: ', 'type' => 'text', 'validations' => array('onlyLetters')),
                'email' => array('label' => 'E-mail: ', 'type' => 'email', 'validations' => array('validEmail', 'notDuplicateMail')),
                'pw' => array('label' => 'Password', 'type' => 'password', 'validations' => array('notEmpty', 'validPassword')),
                'cpw' => array('label' => 'Confim Password', 'type' => 'password', 'validations' => array('equalField:pw'))
            );

        case 'contact':
            return array(
                'gender' => array('label' => 'Aanspreeksvorm: ', 'type' => 'dropdown', 'options' => getGenders(), 'validations' => array('notEmpty')),
                'name' => array('label' => 'Name: ', 'type' => 'text', 'validations' => array('onlyLetters')),
                'email' => array('label' => 'E-mail', 'type' => 'email', 'validations' => array('validEmail')),
                'tlf' => array('label' => 'Telefoon: ', 'type' => 'number', 'validations' => array('justNumbers')),
                'radio' => array('label' => 'Communicatievoorkeur: ', 'type' => 'radio', 'options' => getOptions(), 'validations' => array('notEmpty')),
                'Text1' => array('label' => '', 'type' => 'textarea', 'validations' => array())
            );
    }
}

function validateForm($data) {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $data['valid'] = true;
        // var_dump($data['meta']);
        // print_r($data['meta']);
        var_dump($_POST);
        foreach($data['meta'] as $key => $metaArray) {
            // var_dump($metaArray);
            
            $data['values'][$key] = test_inputs(getVarFromArray($_POST, $key));
            $data = validateField($data, $key, $metaArray);
        }
    }
    // var_dump($data['values']);

    return $data;
}

function validateField($data, $key, $metaArray) {
    if(!empty($data['meta'][$key]['validations'])){
        foreach($data['meta'][$key]['validations'] as $validation) {
            switch($validation) {
                case 'notEmpty':
                    if(empty($data['values'][$key])) {
                        $data['valid'] = false;
                        $data['errors'][$key] = 'TODO cannot be empty.';
                    }
            }
        }
    }
    
    return $data;
}

function validateContactForm($data) {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
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
//LOGIN
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




function showMetaForm($data, $text) {

    showFormStart();
    // var_dump($data);
    foreach(array_keys($data['meta']) as $key){
        $meta = $data['meta'][$key];
        showMetaFormItem($key, $data, $meta);
    }
    showFormEnd($data['page'], $text);
}

function showMetaFormItem($key, $data, $meta) {
    echo('<div>
        <label for="'.$key.'">'.$meta['label'].'</label>'
    );

    if(empty($data['values'][$key])) {
        $data['values'][$key] = '';
    }

    if(empty($data['errors'][$key])) {
        $data['errors'][$key] = '';
    }

    switch ($meta['type']) {
        case "dropdown":
            echo('
                    <select name="'.$key.'" id="'.$key.'" >');

            echo(repeatingForm($meta['options'], $data['values'][$key]));

            echo('</select>');
            break;
        
        case "radio":
            echo('
                <p><h3 class="error"> '. $data['errors'][$key] .'</h3></p>
            ');

            echo(repeatingRadio($meta['options'], $data['values'][$key], $key));

            break;
        
        case "textarea":
            echo('
                
                <textarea class=input name="'.$key.'" cols="40" rows="10"></textarea>

                
            ');
            break;
        
        default:
            echo('
                    <input class="input" type="'.$meta['type'].'" id="'.$key.'" name="'.$key.'" value="'. $data['values'][$key] .'">
                    
                    <h3 class="error">'.$data['errors'][$key] .'</h3>
                
            ');
            break;
    }
    echo('</div><br>');
}

function showFormItem($key, $type, $labeltext, $value, $error, $options=NULL) {
    
    echo('<div>
        <label for="'.$key.'">'.$labeltext.'</label>'
    );
    
    switch ($type) {
        case "dropdown":
            echo('
                    <select name="'.$key.'" id="'.$key.'" >');

            echo(repeatingForm($data, $key));

            echo('</select>');
            break;
        
        case "radio":
            echo('
                <p><h3 class="error"> '. $error .'</h3></p>
            ');

            echo(repeatingRadio($data, $key));

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

function repeatingRadio($options, $value, $key) {
    $count = count($options);
    $keys = array_keys($options);
    for ($i = 0; $i < $count; $i++) {
        echo('
            <input type="radio" name="'.$key.'" id="'.$keys[$i].'"value="'.$keys[$i].'"'.(($value == $keys[$i]) ? "checked" : "").'></option>
            <label for="'.$keys[$i].'">'.$options[$keys[$i]].'</label><br>
        ');
    }
    
}

function radioCheck($data, $key, $option) {
    return ($data['values'][$key] == $option) ? "checked" : "";
}






?>