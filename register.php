<?php 
require_once("contact.php");
function showRegisterContent() {
    // if()
    
    showFormStart();

    showFormItem("name", "text", "Naam:", "", "");
    showFormItem("email", "email", "E-mail:", "", "");
    showFormItem("pw", "password", "Password:", "", "");
    showFormItem("cpw", "password", "Confirm Password:", "", "");
    showFormEnd("register", "Sign Up");
}
?>