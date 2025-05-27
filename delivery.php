<?php
// delivery.php - Delivery Man's Order Page

// Read delivery orders
$orders = [];
if (file_exists('delivery_orders.txt')) {
    $lines = file('delivery_orders.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Each line format: FullName | Phone | Address | TotalPrice
        $parts = explode('|', $line);
        if (count($parts) === 4) {
            $orders[] = [
                'full_name' => trim($parts[0]),
                'phone' => trim($parts[1]),
                'address' => trim($parts[2]),
                'total' => trim($parts[3])
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delivery Orders - Furrfect Pawshop</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f5f0e6;
            color: #5a4d3d;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 32px 28px;
            background: #fff8f0;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(166, 123, 91, 0.13);
        }

        h1 {
            font-weight: 700;
            color: #a67b5b;
            margin-bottom: 20px;
            text-align: left;
        }

        .order {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(166, 123, 91, 0.1);
        }

        .order h2 {
            margin-top: 0;
            color: #8c7b6b;
        }

        .order p {
            margin: 6px 0;
            font-size: 1rem;
        }

        .no-orders {
            text-align: center;
            font-size: 1.2rem;
            color: #a67b5b;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delivery Orders</h1>

        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $index => $order): ?>
                <div class="order">
                    <h2>Order #<?= $index + 1 ?></h2>
                    <p><strong>Name:</strong> <?= htmlspecialchars($order['full_name']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                    <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
                    <p><strong>Total Price:</strong> <?= htmlspecialchars($order['total']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-orders">No delivery orders at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
