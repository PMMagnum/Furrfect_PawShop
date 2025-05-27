<?php
$host = 'localhost';
$username = 'root';
$password = ''; // or your MySQL password
$database = 'furfect_db'; // replace with your actual DB name

$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>