<?php
// $host = "localhost";
// $db = "furrfect pawshop";
// $user = "root";
// $pass = "";


$host = 'localhost';    
$user = 'u866427573_furfect';
$pass = '@Qetu1357'; // or your MySQL password
$db = 'u866427573_furfect'; // replace with your actual DB name


$conn = new mysqli($host, $user, $pass, $db);
if ($furrfect_pawshop->connect_error) {
    die("Connection failed: " . $furrfect_pawshop->connect_error);
}
?>