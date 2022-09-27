<?php

##############################################################
#MAIN APP                                                    #
##############################################################
require_once("./1 Presentation/show.php");
require_once("./2 Business/business.php");
require_once("./3 Data/data.php");

session_start();
// var_dump($_SESSION);
$page = getRequestedPage();
$data = processRequest($page);
showResponsePage($data);

function getRequestedPage() {
    $request_type = $_SERVER["REQUEST_METHOD"];
    if ($request_type == "GET") {
        return getVarFromArray($_GET, 'page', 'home');
    } else {
        return getVarFromArray($_POST, 'page', 'home');
    }
}

function processRequest($page){
    switch($page){
        case 'home':
            //TODO
        case 'about':
            //TODO
        case 'contact':
            $data = getData('contact');
            $data = validateForm($data);
            if($data['valid'] == true){
                $page = 'thanks';
            }
            break;
        case 'login':
            $data = getData('login');
            // var_dump($data);
            
            if($data['valid'] == true) {
                doLogin($data);
                $page = 'home';
            }
            break;

        case 'logout':
            doLogout();
            $page = 'home';
            break;
        case 'register':
            $data = getData('register');
            if($data['valid']) {
                $page = 'login';
            }
            break;
        
        
    }
    $data['page'] = $page;
    return $data;
}

function showResponsePage($data) {
    beginDocument();
    showHead();
    showBody($data);
    endDocument();
}