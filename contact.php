<?php 
function showContactContent() {
    $data = getdata();

    // var_dump($data);
    if($data["valid"] == true) {
        showContactThanks($data);
        
    } else {
        showGenericForm("contact", $data);
        
    }
    
}

function test_inputs($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
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
    showFormItem("Text1", "textarea", "", "", "");

    showFormEnd("contact", "Submit");
}

function ShowFormStart() {
    echo('<form class="body" method="post" action="index.php"s>');
}

function ShowFormEnd($page, $submitText) {
    echo('<input type="hidden" name="page" value="'.$page.'">');
    echo('<button>'.$submitText.'</button></form>');
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
    $data = getdata();
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

function validateContactForm($valid, $gender, $name, $nameErr, $email, $emailErr, $tlf, $tlfErr, $pref, $prefErr, $text) {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $valid = true;
        $gender = test_inputs(getVarFromArray($_POST, "gender"));
        
        if(empty(test_inputs(getVarFromArray($_POST, "name")))) {
            $nameErr = "Je moet je naam invullen.";
            $valid = false;
        } else {
            $name = test_inputs(getVarFromArray($_POST, "name"));
        }


        if(empty(test_inputs(getVarFromArray($_POST, "email")))) {
            $emailErr = "Je moet je e-mail adres invullen.";
            $valid = false;
        } else {
            $email = test_inputs(getVarFromArray($_POST, "email"));
        }

        if(empty(test_inputs(getVarFromArray($_POST, "tlf")))) {
            $tlfErr = "Je moet je telefoonnummer invullen.";
            $valid = false;
        } else {
            $tlf = test_inputs(getVarFromArray($_POST, "tlf"));
        }

        if(empty(getVarFromArray($_POST, "pref"))) {
            $prefErr = "Je moet een voorkeur kiezen.";
            $valid = false;
        } else {
            $pref = test_inputs(getVarFromArray($_POST, "pref"));
        }

        $text = test_inputs(getVarFromArray($_POST, "Text1"));
    }
    return array("valid" => $valid, "gender" => $gender, "name" => $name, "nameErr" => $nameErr,
                     "email" => $email, "emailErr" => $emailErr, "tlf" => $tlf, "tlfErr" => $tlfErr,
                     "pref" => $pref, "prefErr" => $prefErr, "text" => $text
                    );
}

function showContactThanks($data) {
    $GENDERS = getGenders();
    echo(
        '<p class="body">
            Dankjewel ' . $GENDERS[$data['gender']] . " " . ucfirst($data["name"]) . '! <br> <br>

            Jouw e-mail adres is ' . $data["email"] . '. <br>
            Jouw telefoonnummer is ' . $data["tlf"] . '. <br>
            Jouw voorkeur is ' . $data["pref"] . '. <br> <br>
            
            ' . $data["text"] . '
        </p>
        '
    );
}

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

function getdata() {
    $nameErr = $emailErr = $tlfErr = $prefErr = "";
    $name = $email = $gender = $text = $pref = $tlf = "";
    $valid = false;
    
    return validateContactForm($valid, $gender, $name, $nameErr, $email, $emailErr, $tlf, $tlfErr, $pref, $prefErr, $text);
}
?>