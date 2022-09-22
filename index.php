<?php

##############################################################
#MAIN APP                                                    #
##############################################################
require_once("./1 Presentation/show.php");

$page = getRequestedPage();
showResponsePage($page);
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
function getVarFromArray($array, $key, $default = 'home') {
    return isset($array[$key]) ? $array[$key] : $default;
}


function showResponsePage($page) {
    beginDocument();
    showHead();
    showBody($page);
    endDocument();
    
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
        case "register":
            showRegisterContent();
            break;
        case "login":
            showLoginContent();
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
