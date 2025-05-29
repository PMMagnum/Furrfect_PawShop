```php
<?php
include 'db.php';

$stmt = $pdo->prepare("SELECT id, payment_mode, total_price FROM orders WHERE status = 'pending'");
$stmt->execute();
$pendingOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pendingOrders as $order) {
    echo '<tr>';
    echo '<td>#' . htmlspecialchars($order['id']) . '</td>';
    echo '<td>' . htmlspecialchars($order['payment_mode']) . '</td>';
    echo '<td>₱' . number_format($order['total_price'], 2) . '</td>';
    echo '<td><form method="POST">';
    echo '<input type="hidden" name="order_id" value="' . $order['id'] . '">';
    echo '<button type="submit" name="process_order" class="btn btn-warning btn-sm">Process</button>';
    echo '</form></td>';
    echo '</tr>';
}
?>
```