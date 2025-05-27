<?php
session_start();
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $username;
            header("Location: main.php");
            exit;
        } else {
            $message = 'Invalid username or password.';
        }
    } else {
        $message = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Login - Furrfect Pawshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="login.css" />
   
</head>

<body>
    <div class="login-bg">
        <header class="login-header">
            <span class="brand"></span>
            <nav>
                <a href="signup.php">Sign Up</a>
                <a href="login.php" class="active">Log In</a>
            </nav>
        </header>
        <main class="login-main">
            <form method="post" autocomplete="off" novalidate class="login-form">
                <?php if ($message) : ?>
                    <div class="error-msg"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <input type="text" id="username" name="username" placeholder="username" required autofocus>
                <input type="password" id="password" name="password" placeholder="password" required>
                <button type="submit">Log In</button>
                <div class="signup-link">
                    Don't Have an Account? <a href="signup.php">Sign up here</a>
                </div>
            </form>
        </main>
        <footer class="login-footer">
            <a href="#">About</a>
            <a href="#">Help</a>
            <a href="#">Privacy & Terms</a>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>