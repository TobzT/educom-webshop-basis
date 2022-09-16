<?php 
function showContactContent() {
    
    $nameErr = $emailErr = $tlfErr = $prefErr = "";
    $name = $email = $gender = $text = $pref = $tlf = "";
    $valid = false;
    $pronoun = "";

    validateContactForm();
    

    if($valid == true) {
        switch($gender) {
            case "male":
                $pronoun = "dhr";
                break;
            case "female":
                $pronoun = "mvr";
        }
        
        switch($pref) {
            case "email":
                $pref = "e-mail";
                break;
            case "tlf":
                $pref = "telefoon";
        }
        
        showContactThanks($pronoun, $name, $email, $tlf, $pref, $text);
        
    } else {
        // showContactForm($gender, $name, $email, $tlf, $pref, $text, $nameErr, $emailErr, $tlfErr, $prefErr);
        showGenericForm("contact", $nameErr, $emailErr, $tlfErr, $prefErr);
        
    }
    
}

function test_inputs($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function showGenericForm($page, $nameErr, $emailErr, $tlfErr, $prefErr) {
    
    $GENDERS = getGenders();
    $OPTIONS = getOptions();
    showFormStart();
    
    showFormItem("gender", "dropdown", "Gender:", "", "", $GENDERS);
    showFormItem("name", "text", "Name:", "", $nameErr);
    showFormItem("email", "email", "E-mail adres:", "", $emailErr);
    showFormItem("tlf", "number", "Telefoonnummer: ", "", $tlfErr);
    showFormItem("radio", "radio", "Communicatievoorkeur: ", "", $prefErr, $OPTIONS);
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

        echo(repeatingForm($options));

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
                <input class="input" type="'.$type.'" id="'.$key.'" name="'.$key.'" value="'.$value.' ">
                <span class="error">'.$error.'</span>
            </div><br>
        ');
    }
}

function repeatingForm($options) {
    
    $count = count($options);
    $keys = array_keys($options);
    for ($i = 0; $i < $count; $i++) {
        echo('<option value="'.$keys[$i].'">'.$options[$keys[$i]].'</option><br>');
    }
}

function repeatingRadio($key, $error, $options) {
    $count = count($options);
    $keys = array_keys($options);
    for($i = 0; $i < $count; $i++) {
        echo('
            
            <input type="radio" id="" name="'.$key.'" value="'.$options[$keys[$i]].'">
            <label for="'.$key.'">'.$options[$keys[$i]].'</label><br>
        ');
    }
    
}

function showContactForm($gender, $name, $email, $tlf, $pref, $text, $nameErr, $emailErr, $tlfErr, $prefErr) {
    echo(
        '<form class="body" method="post" action="contact.php">
            <label for="gender">Aanhef:</label>

            <select name="gender" id="gender">
                <option value="male" ' . (($gender == "male") ? "selected" : "") . ' >Dhr</option>
                <option value="female" ' . (($gender == "female") ? "selected" : "") . ' >Mvr</option>
                <option value="other" ' . (($gender == "other") ? "selected" : "") . ' >Anders</option>
            </select><br><br>

            <label for="name">Naam:</label>
            <input class="input" type="text" id="name" name="name" value=' . $name .'> <h3 class="error"> '. $nameErr .'</h3><br><br>

            <label for="email">E-mail adres:</label>
            <input class="input" type="text" id="email" name="email" value='. $email . '><h3 class="error"> '. $emailErr .'</h3><br><br>

            <label for="tlf">Telefoonnummer:</label>
            <input class="input" type="number" id="tlf" name="tlf"value='. $tlf .'><h3 class="error"> '. $tlfErr .'</h3><br><br>
            
            <p>Communicatievoorkeur: <h3 class="error"> '. $prefErr .'</h3></p>
            
            
            <input type="radio" id="vtlf" name="pref" value="tlf" ' . (($pref == "tlf") ? "checked" : "") . '>
            <label for="vtlf">Telefoon</label><br>
            
            <input type="radio" id="vemail" name="pref" value="email" ' . (($pref == "email") ? "checked" : "") . '>
            <label for="vemail">E-mail</label><br><br>

            <textarea class="input" name="Text1" cols="40" rows="10">' . $text . '</textarea>

            <br><br>
            <button>Submit</button>
        </form>'
    );
}

function validateContactForm() {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $valid = true;
        $gender = test_inputs($_POST["gender"]);
        
        if(empty($_POST["name"])) {
            $nameErr = "Je moet je naam invullen.";
            $valid = false;
        } else {
            $name = test_inputs($_POST["name"]);
        }

        if(empty($_POST["email"])) {
            $emailErr = "Je moet je e-mail adres invullen.";
            $valid = false;
        } else {
            $email = test_inputs($_POST["email"]);
        }

        if(empty($_POST["tlf"])) {
            $tlfErr = "Je moet je telefoonnummer invullen.";
            $valid = false;
        } else {
            $tlf = test_inputs($_POST["tlf"]);
        }
        
        if(empty($_POST["pref"])) {
            $prefErr = "Je moet een voorkeur kiezen";
            $valid = false;
        } else {
            $pref = test_inputs($_POST["pref"]);
        }

        $text = test_inputs($_POST["Text1"]);
    }
}

function showContactThanks($pronoun, $name, $email, $tlf, $pref, $text) {
    echo(
        '<p class="body">
            Dankjewel ' . $pronoun . " " . ucfirst($name) . '! <br> <br>

            Jouw e-mail adres is ' . $email . '. <br>
            Jouw telefoonnummer is ' . $tlf . '. <br>
            Jouw voorkeur is ' . $pref . '. <br> <br>
            
            ' . $text . '
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