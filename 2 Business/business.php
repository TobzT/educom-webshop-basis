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
    if($_SERVER['REQUEST_METHOD'] == "POST") {
                $data = validateForm($data, ".\users\users.txt");
            }
            return $data;
}

function getMetaData($page) {
    switch($page) {
        case 'login':
            return array(
                'email' => array('label' => 'E-mail: ', 'type' => 'email', 'validations' => array('validEmail', 'notEmpty')),
                'pw' => array('label' => 'Password: ', 'type' => 'password', 'validations' => array('correctPassword', 'notEmpty'))
            );
    
        case 'register':
            return array(
                'name' => array('label' => 'Name: ', 'type' => 'text', 'validations' => array('onlyLetters', 'notEmpty')),
                'email' => array('label' => 'E-mail: ', 'type' => 'email', 'validations' => array('validEmail', 'notDuplicateMail', 'notEmpty')),
                'pw' => array('label' => 'Password', 'type' => 'password', 'validations' => array('validPassword', 'notEmpty')),
                'cpw' => array('label' => 'Confim Password', 'type' => 'password', 'validations' => array('equalField:pw', 'notEmpty'))
            );

        case 'contact':
            return array(
                'gender' => array('label' => 'Aanspreeksvorm: ', 'type' => 'dropdown', 'options' => getGenders(), 'validations' => array('notEmpty')),
                'name' => array('label' => 'Name: ', 'type' => 'text', 'validations' => array('onlyLetters', 'notEmpty')),
                'email' => array('label' => 'E-mail', 'type' => 'email', 'validations' => array('validEmail', 'notEmpty')),
                'tlf' => array('label' => 'Telefoon: ', 'type' => 'number', 'validations' => array('onlyNumbers', 'notEmpty')),
                'radio' => array('label' => 'Communicatievoorkeur: ', 'type' => 'radio', 'options' => getOptions(), 'validations' => array('notEmpty')),
                'text' => array('label' => '', 'type' => 'textarea', 'validations' => array())
            );
    }
}

function validateForm($data, $filename) {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $data['valid'] = true;
        $data['errors'] = NULL;
        foreach($data['meta'] as $key => $metaArray) {
            
            $data['values'][$key] = test_inputs(getVarFromArray($_POST, $key));
            $data = validateField($data, $key, $filename);
        }
    }

    return $data;
}

function validateField($data, $key, $filename) {
    if(!empty($data['meta'][$key]['validations'])){
        $value = $data['values'][$key];
        foreach($data['meta'][$key]['validations'] as $validation) {
            switch($validation) { 
                case 'notEmpty':
                    if(empty($value)) {
                        $data['valid'] = false;
                        $fieldName = explode(':', $data['meta'][$key]['label'])[0];
                        $data['errors'][$key] = $fieldName .' mag niet leeg zijn.';
                    }
                    break;
                    
                case 'validEmail':
                    
                    if(!str_contains($value, '@') Or !str_contains($value, '.')) {
                        $data['valid'] = false;
                        $data['errors'][$key] = 'Dit is geen E-mail adres.';
                    }
                    break;
                    
                case 'onlyNumbers':
                    if(!is_numeric($value)) {
                        $data['valid'] = false;
                        $data['errors'][$key] = 'Voer alleen cijfers in.';
                    }
                    break;
                    
                case 'onlyLetters':
                    if(!ctype_alpha($value)) {
                        $data['valid'] = false;
                        $data['errors'][$key] = 'Voer alleen letters in.';
                    }
                    break;
                case 'notDuplicateMail':
                    if(findByEmailB($filename, strtolower($data['values'][$key]))) {
                        $data['valid'] = false;
                        $data['errors'][$key] = 'Dit e-mail adres is al bekend.';
                    }
                    break;
                case 'validPassword':
                    $len = strlen($data['values'][$key]);
                    switch($len){
                        case ($len < 8):
                            $data['valid'] = false;
                            $data['errors'][$key] = 'Wachtwoord mag niet minder dan acht tekens zijn.';
                            break;
                        case ($len > 40):
                            $data['valid'] = false;
                            $data['errors'][$key] = 'Wachtwoord mag niet meer dan veertig tekens zijn.';
                            break;
                    }
                    break;
                case 'correctPassword':
                    $pwInDb = test_inputs(findByEmail($filename, strtolower(getVarFromArray($_POST, 'email')))['pw']);
                    $pwInPost = test_inputs($data['values'][$key]);
                    if($pwInDb !== $pwInPost ) {
                        $data['valid'] = false;
                        $data['errors'][$key] = 'Deze combinatie van e-mail en wachtwoord is niet bekend.';
                    }
                    break;
                case str_starts_with($validation, 'equalField'):
                    $fields = explode(':', $validation);
                    if($data['values'][$key] !== $data['values'][$fields[1]]){
                        $data['valid'] = false;
                        $data['errors'][$key] = 'Twee velden komen niet overeen.';
                    }
                    break;
                    
            }
        }
        return $data;
    }
    
    return $data;
}

//LOGIN

function doLogIn($data) {
    
    $_SESSION['username'] = findByEmail("./users/users.txt", $data['values']['email'])['name'];
    $_SESSION['loggedin'] = true;
    $_SESSION['lastUsed'] = date('Y:m:t-H:m:s');
    
}

function doLogOut() {
    $_SESSION['username'] = NULL;
    $_SESSION['loggedin'] = false;
    $_SESSION['lastUsed'] = NULL;
}

function session_check() {
    if ($_SESSION['lastUsed'] !== NULL){
        $currentDate = explode("-", date('Y:m:t-H:m:s'));
        $currentTime = $currentDate[1];
        $currentDay = $currentDate[0];
        $lastDate = explode("-", $_SESSION['lastUsed']);
        $lastTime = $lastDate[1];
        $lastDay = $lastDate[0];
        if ($currentDay !== $lastDay) {
            doLogout();
            return;
        } elseif(checkTimeout($currentTime, $lastTime)) {
            doLogout();
            return;
        }
    } else {
        doLogout();
        return;
    }
}

function checkTimeout($currentTime, $lastTime) {
    $currentTime = explode(":", $currentTime);
    $lastTime = explode(":", $lastTime);
    if(2 > (int)$currentTime[0] - (int)$lastTime[0]){
        return false;
    } elseif(30 > (int)$currentTime[1] - (int)$lastTime[1]) {
        return false;
    }

    return true;
}
//REGISTER

function registerUser($data, $filename) {
    $name = $data['values']['name'];
    $email = $data['values']['email'];
    $pw = $data['values']['pw'];
    $message = $email . "|" . $name . "|" . $pw;
    saveInDb($filename, $message);
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