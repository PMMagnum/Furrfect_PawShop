<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furfect_db";



// $servername = 'localhost';    
// $username = 'u866427573_furfect';
// $password = '@Qetu1357'; // or your MySQL password
// $dbname = 'u866427573_furfect'; // replace with your actual DB name

// $conn = new mysqli('localhost', 'root', '', 'furfect_db');


// Create connection
//$conn = new mysqli('localhost', 'u866427573_furfect', '@Qetu1357', 'u866427573_furfect');

$conn = new mysqli('localhost', 'root', '', 'furfect_db');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
