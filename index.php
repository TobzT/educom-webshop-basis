<?php

##############################################################
#MAIN APP                                                    #
##############################################################
require_once("home.php");
require_once("about.php");
require_once("contact.php");
$page = getRequestedPage();
showResponsePage($page);
##############################################################
#CONSTANTS                                                   #
##############################################################





##############################################################
#FUNCTIONS                                                   #
##############################################################

function getRequestedPage() {
    $request_type = $_SERVER["REQUEST_METHOD"];
    if ($request_type == "GET") {
        return getVarFromArray($_GET, 'page');
    } else {
        return getVarFromArray($_POST, 'page');
    }
}
function getVarFromArray($array, $key, $default = '') {
    return isset($array[$key]) ? $array[$key] : $default;
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
