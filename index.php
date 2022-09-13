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
    showBody();
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

function showBody() {
    echo('<body>');
    showHeader();
    showContent();
    showFooter();
    echo('</body>');

}

function showHeader() {
   echo('
    <header>
        <h1 class="header">Title</h1>
        <ul class="list">
        <div class="divh"><li class="menu"><a href="index.php?page=home" class="menu">HOME</a></li></div>
        <div class="divh"><li class="menu"><a href="index.php?page=about"class="menu">ABOUT</a></li></div>
        <div class="divh"><li class="menu"><a href="index.php?page=contact"class="menu">CONTACT</a></li></div>
    </ul>
        
        
    </header>
   ');
}


function showContent() {
    //TODO
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
