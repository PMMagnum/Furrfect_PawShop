<?php
$host = 'localhost';
$username = 'root';
$password = ''; // or your MySQL password
$database = 'furfect_db'; // replace with your actual DB name


//$host = 'localhost';    
//$username = 'u866427573_furfect';
//$password = '@Qetu1357'; // or your MySQL password
//$database = 'u866427573_furfect'; // replace with your actual DB name

$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>