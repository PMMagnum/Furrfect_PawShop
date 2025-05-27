<?php
session_start();

// Ensure payment mode was selected as COD
if ($_SESSION['payment_mode'] !== 'COD') {
    header("Location: checkout.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save personal info
    $_SESSION['personal_info'] = $_POST;
    // Save order details in session
    $_SESSION['order_details'] = [
        'cart' => $_SESSION['cart'],
        'total' => $_SESSION['cart_total'],
        'payment_mode' => 'COD',
        'personal_info' => $_POST,
    ];

    // You can process order here (save to database, etc.)

    // Clear cart after order placement
    unset($_SESSION['cart']);
    // Redirect to receipt page
    header("Location: receipt.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Personal Information</title>
</head>
<body>
<h2>Personal Information</h2>
<form method="post">
<label for="name">Full Name:</label><br>
<input type="text" id="name" name="name" required><br><br>

<label for="phone">Phone Number:</label><br>
<input type="tel" id="phone" name="phone" required><br><br>

<label for="email">Email Address:</label><br>
<input type="email" id="email" name="email" required><br><br>

<label for="address">Delivery Address:</label><br>
<textarea id="address" name="address" rows="4" required></textarea><br><br>

<!-- Add more fields if needed -->

<button type="submit">Confirm Personal Info</button>
</form>
</body>
</html>