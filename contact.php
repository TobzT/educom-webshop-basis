<?php 
function showContactContent() {
    
    $nameErr = $emailErr = $tlfErr = $prefErr = "";
    $name = $email = $gender = $text = $pref = $tlf = "";
    $valid = false;
    $pronoun = "";

    $RESULTS = validateContactForm($valid, $gender, $name, $nameErr, $email, $emailErr, $tlf, $tlfErr, $pref, $prefErr, $text);
    
    
    var_dump($RESULTS);
    if($RESULTS["valid"] == true) {
        switch($RESULTS["gender"]) {
            case "male":
                $pronoun = "dhr";
                break;
            case "female":
                $pronoun = "mvr";
        }
        
        switch($RESULTS["pref"]) {
            case "email":
                $pref = "e-mail";
                break;
            case "tlf":
                $pref = "telefoon";
        }
        
        showContactThanks($RESULTS, $pronoun);
        
    } else {
        showGenericForm("contact", $RESULTS);
        
    }
    
}

function test_inputs($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function showGenericForm($page, $RESULTS) {
    
    $GENDERS = getGenders();
    $OPTIONS = getOptions();
    showFormStart();
    
    showFormItem("gender", "dropdown", "Gender:", $RESULTS["gender"], "", $GENDERS);
    showFormItem("name", "text", "Name:", $RESULTS["name"], $RESULTS["nameErr"]);
    showFormItem("email", "email", "E-mail adres:", $RESULTS["email"], $RESULTS["emailErr"]);
    showFormItem("tlf", "text", "Telefoonnummer: ", $RESULTS["tlf"], $RESULTS["tlfErr"]);
    showFormItem("pref", "radio", "Communicatievoorkeur: ", $RESULTS["pref"], $RESULTS["prefErr"], $OPTIONS);
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
    
    if ($type == "dropdown") {
        echo('
            <div>
                <label for="'.$key.'">'.$labeltext.'</label>
                <select name="'.$key.'" id="'.$key.'" >');

        echo(repeatingForm($options, $value));

        echo('</select></div><br>');
    } elseif ($type == "radio") {
        echo('
            <div>
            <p>'.$labeltext.'<h3 class="error"> '. $error .'</h3></p>

            ');

        echo(repeatingRadio($key, $error, $options));

        echo('</div><br>');
    } elseif ($type == "textarea") {
        echo('
            <div>
            <label for="'.$key.'"></label>
            <textarea class=input name="'.$key.'" cols="40" rows="10"></textarea>

            </div>
        ');
    
    } else {
        echo('
            <div>
                <label for="'.$key.'">'.$labeltext.'</label>
                <input class="input" type="'.$type.'" id="'.$key.'" name="'.$key.'" value="'. $value .' ">
                
                <h3 class="error">'.$error.'</h3>
            </div><br>
        ');
    }
}

function repeatingForm($options, $value) {
    
    $count = count($options);
    $keys = array_keys($options);
    for ($i = 0; $i < $count; $i++) {
        echo('<option value="'.$keys[$i].'"'.(($value == $keys[$i]) ? "selected" : "").'>'.$options[$keys[$i]].'</option><br>');
    }
}

function repeatingRadio($key, $error, $options) {
    $count = count($options);
    $keys = array_keys($options);
    for($i = 0; $i < $count; $i++) {// TODO checked
        echo('
            
            <input type="radio" id="" name="'.$key.'" value="'.$options[$keys[$i]].'">
            <label for="'.$key.'">'.$options[$keys[$i]].'</label><br>
        ');
    }
    
}


function validateContactForm($valid, $gender, $name, $nameErr, $email, $emailErr, $tlf, $tlfErr, $pref, $prefErr, $text) {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $valid = true;
        $gender = test_inputs($_POST["gender"]);
        
        if(empty(test_inputs($_POST["name"]))) {
            $nameErr = "Je moet je naam invullen.";
            $valid = false;
        } else {
            $name = test_inputs($_POST["name"]);
        }


        if(empty(test_inputs($_POST["email"]))) {
            $emailErr = "Je moet je e-mail adres invullen.";
            $valid = false;
        } else {
            $email = test_inputs($_POST["email"]);
        }

        if(empty(test_inputs($_POST["tlf"]))) {
            $tlfErr = "Je moet je telefoonnummer invullen.";
            $valid = false;
        } else {
            $tlf = test_inputs($_POST["tlf"]);
        }

        if(empty($_POST["pref"])) {
            $prefErr = "Je moet een voorkeur kiezen.";
            $valid = false;
        } else {
            $pref = test_inputs($_POST["pref"]);
        }

        $text = test_inputs($_POST["Text1"]);
    }
    return array("valid" => $valid, "gender" => $gender, "name" => $name, "nameErr" => $nameErr,
                     "email" => $email, "emailErr" => $emailErr, "tlf" => $tlf, "tlfErr" => $tlfErr,
                     "pref" => $pref, "prefErr" => $prefErr, "text" => $text
                    );
}

function showContactThanks($RESULTS, $pronoun) {
    echo(
        '<p class="body">
            Dankjewel ' . $pronoun . " " . ucfirst($RESULTS["name"]) . '! <br> <br>

            Jouw e-mail adres is ' . $RESULTS["email"] . '. <br>
            Jouw telefoonnummer is ' . $RESULTS["tlf"] . '. <br>
            Jouw voorkeur is ' . $RESULTS["pref"] . '. <br> <br>
            
            ' . $RESULTS["text"] . '
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
?>