<?php
session_start();
require 'config.php'; // your database connection

// Check if cart exists
if (empty($_SESSION['cart'])) {
    header("Location: thankyou.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $cart = $_SESSION['cart'];

    // Calculate total
    $totalPrice = 0;
    foreach ($cart as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }

    // Save order to database (example)
    $orderDate = date('Y-m-d H:i:s');

    // Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (order_date, total_amount) VALUES (?, ?)");
    $stmt->bind_param("sd", $orderDate, $totalPrice);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    // Insert order details
    foreach ($cart as $item) {
        $stmtDetails = $conn->prepare("INSERT INTO order_details (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");
        $stmtDetails->bind_param("isii", $orderId, $item['name'], $item['quantity'], $item['price']);
        $stmtDetails->execute();
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to thank you page
    header("Location: thankyou.php");
    exit;
} else {
    // If not POST, redirect back to checkout
    header("Location: checkout.php");
    exit;
}
?>