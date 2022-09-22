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
        <div class="register">
            <a href="index.php?page=login" class="menu">Log In</a>
            <a href="index.php?page=register" class="menu">Sign up</a>
        </div>
        <h1 class="header">'. ucfirst($page) .'</h1>
        
        <ul class="list">
        <div class="divh"><li class="menu"><a href="index.php?page=home" class="menu">HOME</a></li></div>
        <div class="divh"><li class="menu"><a href="index.php?page=about"class="menu">ABOUT</a></li></div>
        <div class="divh"><li class="menu"><a href="index.php?page=contact"class="menu">CONTACT</a></li></div>
    </ul>
    </header>
   ');
}

//LOGIN
function ShowLoginContent(){
    showFormStart();
    showFormItem("email", "email", "E-mail:", "", "");
    showFormItem("pw", "password", "Password:", "", "");
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
    
    showFormEnd($data["dest"], "Sign Up");
    
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
    $data = getdata();

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



?>