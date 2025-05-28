<?php
// $host = 'localhost';
// $dbname = 'furfect_db';
// $username = 'root';  // or your DB username
// $password = '';      // or your DB password


$host = 'localhost';    
$username = 'u866427573_furfect';
$password = '@Qetu1357'; // or your MySQL password
$dbname = 'u866427573_furfect'; // replace with your actual DB name

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
