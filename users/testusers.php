<?php 
$file = fopen("users.txt", "r");
echo fread($file, filesize("users.txt"));
fclose($file);

$file = fopen("users.txt", "a");
fwrite($file, "\nTEST TST TEST");
fclose($file);

$file = fopen("users.txt", "r");
echo fread($file, filesize("users.txt"));
fclose($file);
?>