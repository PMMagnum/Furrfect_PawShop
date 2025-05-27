<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furfect_db";

// Create connection
$conn = new mysqli('localhost', 'root', '', 'furfect_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
