<?php
$host = "localhost";
$db = "furrfect pawshop";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($furrfect_pawshop->connect_error) {
    die("Connection failed: " . $furrfect_pawshop->connect_error);
}
?>