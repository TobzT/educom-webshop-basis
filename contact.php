<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="css.css">
</head>
<body>
    <div class="container">
        <header>
            <h1 class="header">Title</h1>
            <ul class="list">
                <div class="divh"><li class="menu"><a href="index.php?page=home" class="menu">HOME</a></li></div>
                <div class="divh"><li class="menu"><a href="index.php?page=about"class="menu">ABOUT</a></li></div>
                <div class="divh"><li class="menu"><a href="index.php?page=contact"class="menu">CONTACT</a></li></div>
            </ul>
        </header>
        <?php 
            $nameErr = $emailErr = $tlfErr = $prefErr = "";
            $name = $email = $gender = $text = $pref = $tlf = "";
            $valid = false;
            $pronoun = "";
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
                echo(
                    '<p class="body">
                        Dankjewel ' . $pronoun . " " . $name . '! <br> <br>

                        Jouw e-mail adres is ' . $email . '. <br>
                        Jouw telefoonnummer is ' . $tlf . '. <br>
                        Jouw voorkeur is ' . $pref . '. <br> <br>
                        
                        ' . $text . '
                    </p>
                    '
                );
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
        ?>
        
        <footer>
            &#169;
            <p><?php echo date("Y") ?></p>
            <p>Tobias The</p>
        </footer>
    </div>
</body>