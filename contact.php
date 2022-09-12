<!DOCTYPE html>
<head>
    <style>
        body {
            background-color: white;
        }
        
        .container {
            display: block;
            width: 800px;
            height: 100%;
            margin-right: auto;
            margin-left: auto;
            background-color: white;
        }

        header {
            text-align: center;
            background-color: rgb(86, 7, 86);
            min-height: 48px;
            padding: 1px;
        }

        footer {
            margin-bottom: 0%;
            text-align: right;
            background-color: black;
            color: white;
            min-height: 48px;
            font-size: 10px;
        }
        
        .header {
            color:white;
            
        }

        .divh {
            display: inline-block;
            background-color:rgb(35, 85, 160);
            max-width: 30%;
            border: 1px solid white;
            padding: 5px;
            color: white;
        }

        .body {
            font-size: 12px;
            color: rgb(100, 96, 96);
        }

        li {
            list-style-type: none;
            
        }
        .list ul {
            display: inline;
        }
        .menu {
            color: white;
            font-size: large;
            list-style-type: none;
        }

        .menu:link {
            text-decoration: none;
        }

        .menu:visited {
            text-decoration: none;
        }

        .input {
            width: 400px;
        }
        
        h1 {
            font-size: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1 class="header">Title</h1>
            <ul class="list">
                <div class="divh"><li class="menu"><a href="home.html" class="menu">HOME</a></li></div>
                <div class="divh"><li class="menu"><a href="about.html"class="menu">ABOUT</a></li></div>
                <div class="divh"><li class="menu"><a href="contact.html"class="menu">CONTACT</a></li></div>
            </ul>
        </header>
        <?php 
            if($_SERVER["REQUEST_METHOD"] == "GET") {
                echo(
                    '<form class="body">
                        <label for="gender">Aanhef:</label>

                        <select name="gender" id="gender">
                            <option value="Sir">Dhr</option>
                            <option value="Madam">Mvr</option>
                            <option value="Other">Anders</option>
                        </select><br><br>

                        <label for="name">Naam:</label>
                        <input class="input" type="text" id="name" name="name"><br><br>

                        <label for="email">E-mail adres:</label>
                        <input class="input" type="text" id="email" name="email"><br><br>

                        <label for="tlf">Telefoonnummer:</label>
                        <input class="input" type="number" id="tlf" name="tlf"><br><br>

                        <p>Communicatievoorkeur:</p>
                        
                        
                        <input type="radio" id="vtlf" name="v">
                        <label for="vtlf">Telefoon</label><br>
                        
                        <input type="radio" id="vemail" name="v">
                        <label for="vemail">E-mail</label><br><br>

                        <textarea class="input" name="Text1" cols="40" rows="10"></textarea>

                        <br><br>
                        <button>Submit</button>
                    </form>'
                );
            };
        ?>
        
        <footer>
            &#169;
            <p>2022</p>
            <p>Tobias The</p>
        </footer>
    </div>
</body>