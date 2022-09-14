<?php

##############################################################
#MAIN APP                                                    #
##############################################################

$page = getRequestedPage();
showResponsePage($page);

##############################################################
#FUNCTIONS                                                   #
##############################################################

function getRequestedPage() {
    $request_type = $_SERVER["REQUEST_METHOD"];
    if ($request_type == "GET") {
        return getPageFromGet();
    } else {
        return getPageFromPost();
    }
}

function getPageFromGet() {
    return $_GET['page'];
}

function getPageFromPost() {
    return $_POST['page'];
}

function showResponsePage($page) {
    beginDocument();
    showHead();
    showBody($page);
    endDocument();
}

function beginDocument() {
    echo('
        <!DOCTYPE html>
        <html>
    ');
}

function showHead() {
    echo('<head>');
    linkExternalCss();
    echo('</head>');
}

function showBody($page) {
    echo('<body> <div class="container">');
    showHeader($page);
    showContent($page);
    showFooter();
    echo('</div> </body>');

}

function showHeader($page) {
   echo('
    <header>
        <h1 class="header">'. ucfirst($page) .'</h1>
        <ul class="list">
        <div class="divh"><li class="menu"><a href="index.php?page=home" class="menu">HOME</a></li></div>
        <div class="divh"><li class="menu"><a href="index.php?page=about"class="menu">ABOUT</a></li></div>
        <div class="divh"><li class="menu"><a href="index.php?page=contact"class="menu">CONTACT</a></li></div>
    </ul>
    </header>
   ');
}


function showContent($page) {
    
    switch($page) {
        case "home":
            showHomeContent();
            break;
        case "about":
            showAboutContent();
            break;
        case "contact":
            showContactContent();
            break;
        default:
            showPageError();
    }
}

function showHomeContent() {
    echo('
        <p class="body">
            Welcome to the website, dear traveler. <br>
            Here we will have some fun while also learning a thing or two. <br>
            See you around traveler!
        </p>'
        );
}

function showAboutContent() {
    echo('
        <div class="body">
            <p>My name is Tobias, and I am building this website right now. <br>
                I am planning on making this a bigger project, but everyone has to start somewhere.
            </p>


            <p>
                My hobbies consist of:<br>
                <ul>
                    <li>Music production</li>
                    <li>Video games (mainly first person shooters)</li>
                    <li>Sports (tennis, table tennis, basket ball)</li>
                </ul>
            </p>

            <p>If you have any questions, go over to the contact page. You can find information there.</p>
        </div>
    ');
}

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

    function test_inputs($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
        }
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


function showPageError() {
    echo('
        <h1 class="error">PAGE ERROR</h1>
    ');
}

function showFooter() {
    echo('
    <footer>
        &#169;
        <p>' . date("Y") . '</p>
        <p>Tobias The</p>
    </footer>
    ');
}

function endDocument() {
    echo('</html>');
}


function linkExternalCss() {
    echo('<link rel="stylesheet" href="css.css">');
}
