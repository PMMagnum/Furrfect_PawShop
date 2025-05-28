<?php
session_start();
include 'db.php';

// Automatically insert default admin account if not exists
$defaultAdminUsername = 'admin';
$defaultAdminPassword = 'admin123'; // change this if you want
$defaultAdminRole = 'admin';

$checkAdmin = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
$checkAdmin->execute([$defaultAdminUsername, $defaultAdminRole]);

if ($checkAdmin->rowCount() === 0) {
    $hashed = password_hash($defaultAdminPassword, PASSWORD_BCRYPT);
    $insertAdmin = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $insertAdmin->execute([$defaultAdminUsername, $hashed, $defaultAdminRole]);
}

// Get input values from the login form
$username = $_POST['username'];
$password = $_POST['password'];

// Fetch the user record based on username (no role needed from input)
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Successful login: Save user data in session
    $_SESSION['user'] = $user;
    
    // Redirect based on detected role
    if ($user['role'] === 'admin') {
        header('Location: dashboard.php');
    } else {
        header('Location: pos.php');
    }
    exit();
} else {
    header('Location: index.php?error=Invalid+username+or+password');
    exit();
}
?>
