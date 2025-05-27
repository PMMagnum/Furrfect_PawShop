<?php
session_start();

if (!isset($_SESSION['order_details'])) {
    header("Location: main.php");
    exit;
}

$order = $_SESSION['order_details'];
?>

<!-- Existing receipt code -->

<?php if ($order['payment_mode'] === 'COD') {
    $personal = $order['personal_info'];
    echo "<h3>Personal Information</h3>";
    echo "<p><strong>Name:</strong> " . htmlspecialchars($personal['name']) . "</p>";
    echo "<p><strong>Phone:</strong> " . htmlspecialchars($personal['phone']) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($personal['email']) . "</p>";
    echo "<p><strong>Address:</strong> " . htmlspecialchars($personal['address']) . "</p>";
} ?>