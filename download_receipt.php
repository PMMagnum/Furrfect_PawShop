<?php
session_start();

if (!isset($_SESSION['order_details'])) {
    header("Location: main.php");
    exit;
}

$order = $_SESSION['order_details'];

// Generate receipt content
$receipt = "Order Receipt\n\n";
$receipt .= "Products:\n";
foreach ($order['cart'] as $item) {
    $itemTotal = $item['price'] * $item['quantity'];
    $receipt .= "{$item['name']} - Qty: {$item['quantity']} - Price: ₱" . number_format($item['price'], 2) . " - Total: ₱" . number_format($itemTotal, 2) . "\n";
}
$receipt .= "Total Price: ₱" . number_format($order['total'], 2) . "\n";
$receipt .= "Payment Method: " . $order['payment_mode'] . "\n";

if ($order['payment_mode'] === 'COD') {
    $delivery = $order['delivery_info'];
    $receipt .= "\nDelivery Details:\n";
    $receipt .= "Recipient: " . htmlspecialchars($delivery['recipient_name']) . "\n";
    $receipt .= "Delivery Time: " . htmlspecialchars($delivery['delivery_time']) . "\n";
    $receipt .= "Address: " . htmlspecialchars($delivery['delivery_address']) . "\n";
    $receipt .= "Item: " . htmlspecialchars($delivery['delivery_item']) . "\n";
    $receipt .= "Reason: " . htmlspecialchars($delivery['delivery_reason']) . "\n";
    $receipt .= "Method: " . htmlspecialchars($delivery['delivery_method']) . "\n";
}

// Send as downloadable text file
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="order_receipt.txt"');
echo $receipt;
exit;
?>