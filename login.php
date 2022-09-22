<?php 
require_once("contact.php");
function ShowLoginContent(){
    showFormStart();
    showFormItem("email", "email", "E-mail:", "", "");
    showFormItem("pw", "password", "Password:", "", "");
    showFormEnd("login", "Log In");
}
?>