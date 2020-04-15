<?php

$db_host = "localhost";
$db_user = "wikatona_root";
$db_pass = "widyakelana19";
$db_name = "wikatona_authentication";

try {    
    //create PDO connection 
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
} catch(PDOException $e) {
    //show error
    die("Terjadi masalah: " . $e->getMessage());
}