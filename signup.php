<?php
session_start();
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($username === '' || $password === '' || $confirm_password === '') {
        $message = 'Please fill all fields.';
    } elseif ($password !== $confirm_password) {
        $message = 'Passwords do not match.';
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = 'Username already taken.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                $_SESSION['user'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $message = 'Error creating account.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Sign Up - Furrfect Pawshop</title>
    <link rel="stylesheet" href="signup.css" />
</head>

<body>
    <div class="container">
        <h2>Sign Up</h2>
        <?php if ($message) : ?>
            <p style="color:red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="post">
            <label>Username:</label><br />
            <input type="text" name="username" required /><br />
            <label>Password:</label><br />
            <input type="password" name="password" required /><br />
            <label>Confirm Password:</label><br />
            <input type="password" name="confirm_password" required /><br /><br />
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>
