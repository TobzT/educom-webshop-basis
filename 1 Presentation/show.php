<?php 
require_once('./2 Business/business.php');

//INDEX
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

function showBody($data) {
    echo('<body> <div class="container">');
    showHeader($data);
    showContent($data);
    showFooter();
    echo('</div> </body>');
    

}

function showHeader($data) {
    if($_SESSION['loggedin'] == true) {
        $register = '
        <div class="register">
            <a href="index.php?page=logout" class="menu">Log Out ' . ucfirst($_SESSION['username']) . '</a>
        </div>
        ';
    } else {
        $register = '
        <div class="register">
            <a href="index.php?page=login" class="menu">Log In</a>
            <a href="index.php?page=register" class="menu">Sign up</a>
        </div>
        ';
    }
   echo('
    <header>
        '.$register.'
        <h1 class="header">'. ucfirst($data['page']) .'</h1>
        
        <ul class="list">
        <div class="divh"><li class="menu"><a href="index.php?page=home" class="menu">HOME</a></li></div>
        <div class="divh"><li class="menu"><a href="index.php?page=about"class="menu">ABOUT</a></li></div>
        <div class="divh"><li class="menu"><a href="index.php?page=contact"class="menu">CONTACT</a></li></div>
    </ul>
    </header>
   ');
}



function showContent($data) {
    
    switch($data['page']) {
        case "home":
            showHomeContent();
            break;
        case "about":
            showAboutContent();
            break;
        case "contact":
            showContactContent();
            break;
        case "register":
            showRegisterContent();
            break;
        case "login":
            showLoginContent($data);
            break;
        case 'thanks':
            showContactThanks($data);
            break;
        case 'logout':
            showHomeContent();
        default:
            showPageError();
    }
}

function endDocument() {
    echo('</html>');
}


function linkExternalCss() {
    echo('<link rel="stylesheet" href="css.css">');
}



//LOGIN
function ShowLoginContent($data){
    $data = getLoginData();
    
    showFormStart();
    showFormItem("email", "email", "E-mail:", $data['email'], $data['emailErr']);
    showFormItem("pw", "password", "Password:", $data['pw'], $data['pwErr']);
    showFormEnd("login", "Log In");
}

//REGISTER
function showRegisterContent() {
    $data = getRegisterData();
    
    showFormStart();

    showFormItem("name", "text", "Naam:", $data["name"], $data["nameErr"]);
    showFormItem("email", "email", "E-mail:", $data["email"], $data["emailErr"]);
    showFormItem("pw", "password", "Password:", $data["pw"], $data["pwErr"]);
    showFormItem("cpw", "password", "Confirm Password:", $data["cpw"], "");
    
    showFormEnd('register', "Sign Up");
}

//HOME
function showHomeContent() {
    echo('
        <p class="body">
            Welcome to the website, dear traveler. <br>
            Here we will have some fun while also learning a thing or two. <br>
            See you around traveler!
        </p>'
        );
}

//ABOUT
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

//CONTACT
function showContactContent() {
    $data = getContactData();

    // var_dump($data);
    if($data["valid"] == true) {
        showContactThanks($data);
        
    } else {
        showGenericForm("contact", $data);
    }
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

function ShowFormStart() {
    echo('<form class="body" method="post" action="index.php"s>');
}

function ShowFormEnd($page, $submitText) {
    echo('<input type="hidden" name="page" value="'.$page.'">');
    echo('<button>'.$submitText.'</button></form>');
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


?>