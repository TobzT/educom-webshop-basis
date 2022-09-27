<?php 
function ShowFormStart() {
    echo('<form class="body" method="post" action="index.php"s>');
}

function ShowFormEnd($page, $submitText) {
    echo('<input type="hidden" name="page" value="'.$page.'">');
    echo('<button>'.$submitText.'</button></form>');
}

function showMetaForm($page, $meta) {

    showFormStart();

    foreach($meta as $array){
        showMetaFormItem($array);
    }
    showFormEnd($page, "Submit");
}

function showMetaFormItem($array) {
    echo('<div>
        <label for="'.$array['key'].'">'.$array['labeltext'].'</label>'
    );
    
    switch ($array['type']) {
        case "dropdown":
            echo('
                    <select name="'.$array['key'].'" id="'.$array['key'].'" >');

            echo(repeatingForm($array['options'], $array['value']));

            echo('</select>');
            break;
        
        case "radio":
            echo('
                <p><h3 class="error"> '. $array['error'] .'</h3></p>
            ');

            echo(repeatingRadio($array['key'], $array['options']));

            break;
        
        case "textarea":
            echo('
                
                <textarea class=input name="'.$array['key'].'" cols="40" rows="10"></textarea>

                
            ');
            break;
        
        default:
            echo('
                    <input class="input" type="'.$array['type'].'" id="'.$array['key'].'" name="'.$array['key'].'" value="'. $array['type'] .'">
                    
                    <h3 class="error">'.$array['error'].'</h3>
                
            ');
            break;
    }
    echo('</div><br>');
}


$test = array(
    array('name' => 'lmao', 'type' => 'text', 'key' => 'lmao', 'labeltext' => 'help', 'error' => ''),
    array('name' => 'hoppa', 'type' => 'textarea', 'key' => 'hoppa', 'labeltext' => 'bingus', 'error' => '')
);

// showMetaForm('nvm', $test);

function myReadFile($filename) {
    $file = fopen($filename, "r");
    echo fread($file, filesize("users.txt"));
    fclose($file);
}

function myWriteFile($filename, $message) {
    $file = fopen($filename, "a");
    fwrite($file, $message . "\n");
    fclose($file);
}

function findEmailInFileB($filename, $string) {
    $file = fopen($filename, "r");
    $result = false;
    fgets($file);
    while(($line = fgets($file)) !== false) {
        // echo($line);
        $line = explode("|", $line);
        // echo($line[0]."\n");
        if($line[0] == $string) {
            $result = true;
            break;
        }
    }

    fclose($file);
    return $result;
}

function findEmailInFile($filename, $string) {
    $file = fopen($filename, "r");
    $result = NULL;
    fgets($file);
    while(($line = fgets($file)) !== false) {
        $line = explode("|", $line);
        if($line[0] == $string) {
            $result = array('email' => $line[0], 'name' => $line[1], 'pw' => $line[2]);
            break;
        }
    }
    fclose($file);
    return $result;
}
// $data = findEmailInFile("users.txt", "balls@mogus.nl");
// var_dump($data);

function session_test(){
    session_start();

    $_SESSION['dest'] = "test";
    print_r($_SESSION);
}


// myWriteFile("users.txt", "TEST|TEST|TEST");
// myReadFile("users.txt");
// findEmailInFileB("users.txt", "Tobias@conceptuals.nl");
// findEmailInFileB("users.txt", "TEST");
// session_test();
// findEmailInFile("users.txt", "niks@niemand.nl");

phpinfo();
?>