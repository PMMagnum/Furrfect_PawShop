<?php
date_default_timezone_set('America/Los_Angeles'); // Set timezone to PST/PDT
session_start();
include 'db.php';

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'cashier') {
    header('Location: index.php');
    exit();
}

$user = $_SESSION['user'];

// Fetch categories for filter dropdown
$categoryStmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category");
$categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch products with optional category filter (limited to 5 for fixed layout)
$selectedCategory = $_GET['category'] ?? '';
$query = "SELECT * FROM products";
$params = [];
if ($selectedCategory && $selectedCategory !== 'all') {
    $query .= " WHERE category = ?";
    $params[] = $selectedCategory;
}
$query .= " ORDER BY name LIMIT 5";
$productStmt = $pdo->prepare($query);
$productStmt->execute($params);
$products = $productStmt->fetchAll();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart (barcode from scan or clickable)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    error_log('add_to_cart POST received: ' . print_r($_POST, true)); // Debug
    $barcode = $_POST['barcode'] ?? '';
    $quantityToAdd = isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0 ? (int)$_POST['quantity'] : 1;
    if (!empty($barcode)) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE barcode = ?");
        $stmt->execute([$barcode]);
        $product = $stmt->fetch();
        error_log('Product query result: ' . print_r($product, true)); // Debug
        if ($product) {
            $existingQty = $_SESSION['cart'][$product['id']]['quantity'] ?? 0;
            $newQuantity = $existingQty + $quantityToAdd;
            if ($newQuantity > $product['stock']) {
                echo "<script>alert('Insufficient stock for {$product['name']}. Available: {$product['stock']}');</script>";
            } else {
                $_SESSION['cart'][$product['id']] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $newQuantity,
                    'barcode' => $product['barcode']
                ];
                error_log('Product added to cart: ' . $product['id']); // Debug
            }
        } else {
            echo "<script>alert('Product not found.');</script>";
        }
    } else {
        echo "<script>alert('Invalid barcode.');</script>";
    }
}

// Handle update quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (empty($_SESSION['cart']) || !isset($_POST['quantities']) || !is_array($_POST['quantities'])) {
        echo "<script>alert('No items in the cart.');</script>";
    } else {
        foreach ($_POST['quantities'] as $product_id => $qty) {
            $qty = (int)$qty;
            if ($qty < 1) {
                unset($_SESSION['cart'][$product_id]);
                continue;
            }
            $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            if ($product) {
                if ($qty > $product['stock']) {
                    echo "<script>alert('Insufficient stock for product ID {$product_id}. Available: {$product['stock']}');</script>";
                } else {
                    $_SESSION['cart'][$product_id]['quantity'] = $qty;
                }
            }
        }
    }
}

// Handle delete item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $product_id_to_delete = $_POST['delete_item'];
    if (isset($_SESSION['cart'][$product_id_to_delete])) {
        unset($_SESSION['cart'][$product_id_to_delete]);
    }
}

// Handle processing of e-commerce orders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_order'])) {
    $order_id = (int)$_POST['order_id'];
    
    // Fetch order details
    $stmt = $pdo->prepare("SELECT cart_json, payment_mode FROM orders WHERE id = ? AND status = 'pending'");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order) {
        // Mark order as processed
        $stmt = $pdo->prepare("UPDATE orders SET status = 'processed' WHERE id = ?");
        $stmt->execute([$order_id]);
        
        // If payment mode is Over-the-counter, add items to cart
        if ($order['payment_mode'] === 'Counter') {
            $cart_items = json_decode($order['cart_json'], true);
            foreach ($cart_items as $product_id => $item) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    $existingQty = $_SESSION['cart'][$product['id']]['quantity'] ?? 0;
                    $newQuantity = $existingQty + $item['quantity'];
                    
                    if ($newQuantity > $product['stock']) {
                        echo "<script>alert('Insufficient stock for {$product['name']}. Available: {$product['stock']}');</script>";
                    } else {
                        $_SESSION['cart'][$product['id']] = [
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'quantity' => $newQuantity,
                            'barcode' => $product['barcode']
                        ];
                    }
                }
            }
        }
    }
}

// Handle clearing all pending e-commerce orders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_pending_orders'])) {
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE status = 'pending'");
        $stmt->execute();
        echo "<script>alert('All pending e-commerce orders have been cleared.');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to clear pending orders: {$e->getMessage()}');</script>";
    }
}

// Handle payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    $total = 0;
    $payment_amount = (float)$_POST['payment_amount'];
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        if ($product['stock'] < $item['quantity']) {
            echo "<script>alert('Insufficient stock for {$item['name']}. Available: {$product['stock']}');</script>";
            exit();
        }
        $total += $item['price'] * $item['quantity'];
    }
    if ($payment_amount < $total) {
        echo "<script>alert('Payment amount is insufficient. Total: ₱$total');</script>";
    } else {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO sales (user_id, total, payment_amount) VALUES (?, ?, ?)");
            $stmt->execute([$user['id'], $total, $payment_amount]);
            $sale_id = $pdo->lastInsertId();
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $stmt = $pdo->prepare("INSERT INTO sales_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$sale_id, $product_id, $item['quantity'], $item['price']]);
                $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $product_id]);
            }
            $receipt_number = uniqid('REC_');
            $items = json_encode($_SESSION['cart']);
            $change_due = $payment_amount - $total;
            $stmt = $pdo->prepare("INSERT INTO receipts (receipt_number, items, total, cash, change_due) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$receipt_number, $items, $total, $payment_amount, $change_due]);
            
            // Store receipt data with formatted date
            $_SESSION['receipt'] = [
                'receipt_number' => $receipt_number,
                'items' => $_SESSION['cart'],
                'total' => $total,
                'cash' => $payment_amount,
                'change_due' => $change_due,
                'date' => date('M d, Y h:i A')
            ];
            
            $_SESSION['cart'] = [];
            $pdo->commit();
            header('Location: receipt.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Payment failed: {$e->getMessage()}');</script>";
        }
    }
}

// Clear cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    unset($_SESSION['receipt']);
    header("Location: pos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Furrfect Pawshop POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        html, body {
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        body {
            font-family: 'Fredoka', sans-serif;
            background: url('https://www.transparenttextures.com/patterns/paw-print.png') repeat, #fff8f0;
            background-size: 60px 60px;
            display: flex;
            flex-direction: column;
        }
        header {
            background-color: #cc7722;
            color: white;
            padding: 10px 0;
            flex-shrink: 0;
        }
        .navbar {
            width: 100%;
            margin: 0;
            padding: 0 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }
        h1 { 
            font-size: 28px; 
            margin: 0;
        }
        .user-info { 
            display: flex; 
            align-items: center; 
        }
        .user-info span { 
            margin-right: 10px; 
        }
        .logout-btn, .dashboard-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-left: 10px;
        }
        .dashboard-btn { 
            background-color: #f4a261; 
        }
        .logout-btn:hover { 
            background-color: #c0392b; 
        }
        .dashboard-btn:hover { 
            background-color: #e76f51; 
        }
        .pos-container {
            display: flex;
            gap: 0;
            width: 100%;
            max-width: none;
            height: calc(100vh - 48px);
            margin: 0;
            flex: 1;
            overflow: hidden;
        }
        .pos-card {
            background-color: #fffbea;
            border: 2px solid #f7d9a4;
            border-radius: 0;
            box-shadow: none;
            width: 50%;
            max-width: none;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .pos-card.products-card {
            height: 100%;
        }
        .pos-card.cart-card {
            height: 100%;
        }
        .cart-table-wrapper {
            flex: 1;
            overflow-y: auto;
        }
        h2 { 
            color: #86592d; 
            margin-bottom: 5px;
            font-size: 1.5rem;
        }
        h3 {
            color: #86592d;
            margin: 10px 0 5px;
            font-size: 1.2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #cc7722;
            color: white;
        }
        tr:nth-child(even) { 
            background-color: #f2f2f2; 
        }
        .products-table {
            table-layout: fixed;
        }
        .products-table th:nth-child(1), .products-table td:nth-child(1) { width: 30%; }
        .products-table th:nth-child(2), .products-table td:nth-child(2) { width: 20%; }
        .products-table th:nth-child(3), .products-table td:nth-child(3) { width: 20%; }
        .products-table th:nth-child(4), .products-table td:nth-child(4) { width: 15%; }
        .products-table th:nth-child(5), .products-table td:nth-child(5) { width: 15%; }
        .cart-table {
            table-layout: fixed;
        }
        .cart-table th:nth-child(1), .cart-table td:nth-child(1) { width: 35%; }
        .cart-table th:nth-child(2), .cart-table td:nth-child(2) { width: 15%; }
        .cart-table th:nth-child(3), .cart-table td:nth-child(3) { width: 20%; }
        .cart-table th:nth-child(4), .cart-table td:nth-child(4) { width: 15%; }
        .cart-table th:nth-child(5), .cart-table td:nth-child(5) { width: 15%; }
        .notification-table th, .notification-table td {
            padding: 6px 10px;
            text-align: left;
        }
        .notification-table th {
            background-color: #cc7722;
            color: white;
        }
        .notification-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-sm {
            padding: 4px 8px;
            font-size: 0.85rem;
        }
        .name-cell, .barcode-cell, .category-cell {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .name-cell:hover, .barcode-cell:hover, .category-cell:hover {
            overflow: visible;
            white-space: normal;
            position: relative;
        }
        .name-cell:hover::after, .barcode-cell:hover::after, .category-cell:hover::after {
            content: attr(data-fulltext);
            position: absolute;
            background: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            z-index: 10;
            white-space: nowrap;
            left: 0;
            top: 100%;
        }
        input, button, select {
            padding: 8px;
            margin: 3px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background-color: #f4a261;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover { 
            background-color: #e76f51; 
        }
        .btn-clear { 
            background-color: #e74c3c; 
        }
        .btn-clear:hover { 
            background-color: #c0392b; 
        }
        .btn-delete {
            background-color: #e74c3c;
            padding: 5px 8px;
            font-size: 0.9rem;
            border-radius: 4px;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .product-row:hover {
            background-color: #ffe5b4;
            cursor: pointer;
        }
        .scan-barcode-input {
            font-size: 1rem;
            padding: 6px 10px;
            width: 100%;
            margin-bottom: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .update-qty-input {
            width: 50px;
            border-radius: 4px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .category-filter {
            width: 100%;
            font-size: 0.9rem;
        }
        .datetime-display {
            font-size: 14px;
            margin-top: 5px;
            color: #fff;
        }
        @media (max-width: 768px) {
            .pos-container {
                flex-direction: column;
            }
            .pos-card {
                width: 100%;
                height: 50%;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="navbar">
        <h1>Furrfect Pawshop POS</h1>
        <div class="user-info">
            <span>Logged in as: <?= htmlspecialchars($user['username']) ?> (<?= $user['role'] ?>)</span>
            <a href="logout.php" class="logout-btn">Logout</a>
            <?php if ($user['role'] === 'admin'): ?>
                <a href="dashboard.php" class="dashboard-btn">Admin Dashboard</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="datetime-display" id="datetime"></div>
</header>

<main>
    <div class="pos-container">
        <!-- Product List -->
        <div class="pos-card products-card">
            <h2>Available Products</h2>
            <!-- Category Filter -->
            <select name="category" class="category-filter" onchange="filterByCategory(this.value)">
                <option value="all" <?= $selectedCategory === '' || $selectedCategory === 'all' ? 'selected' : '' ?>>All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>" <?= $selectedCategory === $category ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <table class="products-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Barcode</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <?php if (empty($product['barcode'])) { error_log("Missing barcode for product ID: " . $product['id']); } ?>
                    <tr class="product-row" data-barcode="<?= htmlspecialchars($product['barcode']) ?>">
                        <td class="name-cell" data-fulltext="<?= htmlspecialchars($product['name']) ?>">
                            <?= htmlspecialchars($product['name']) ?>
                        </td>
                        <td class="barcode-cell" data-fulltext="<?= htmlspecialchars($product['barcode']) ?>">
                            <?= htmlspecialchars($product['barcode']) ?>
                        </td>
                        <td>₱<?= number_format($product['price'], 2) ?></td>
                        <td><?= $product['stock'] ?></td>
                        <td class="category-cell" data-fulltext="<?= htmlspecialchars($product['category'] ?: 'General') ?>">
                            <?= htmlspecialchars($product['category'] ?: 'General') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Scan Barcode Form -->
            <form id="barcodeForm" method="POST" style="margin-top: 5px;">
                <input type="text" name="barcode" placeholder="Scan or enter barcode" autofocus class="scan-barcode-input" autocomplete="off" />
                <input type="hidden" name="add_to_cart" value="1" />
                <button type="submit" class="btn btn-warning">Add by Barcode</button>
            </form>
        </div>

        <!-- Cart -->
        <div class="pos-card cart-card">
            <h2>Cart</h2>
            <!-- Notification Section for Pending Orders -->
            <h3>Pending E-commerce Orders</h3>
            <div class="notification-table-wrapper" style="max-height: 150px; overflow-y: auto;">
                <table class="notification-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Payment Mode</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch pending orders
                        $stmt = $pdo->prepare("SELECT id, payment_mode, total_price FROM orders WHERE status = 'pending'");
                        $stmt->execute();
                        $pendingOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($pendingOrders as $order):
                        ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_mode']); ?></td>
                                <td>₱<?php echo number_format($order['total_price'], 2); ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" name="process_order" class="btn btn-warning btn-sm">Process</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <form method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to clear all pending e-commerce orders?');">
                <button type="submit" name="clear_pending_orders" class="btn btn-clear">Clear All Pending Orders</button>
            </form>
            <form method="POST">
                <div class="cart-table-wrapper">
                    <table class="cart-table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $product_id => $item):
                            $item_total = $item['price'] * $item['quantity'];
                            $total += $item_total;
                        ?>
                            <tr>
                                <td class="name-cell" data-fulltext="<?= htmlspecialchars($item['name']) ?>">
                                    <?= htmlspecialchars($item['name']) ?>
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        name="quantities[<?= $product_id ?>]"
                                        value="<?= $item['quantity'] ?>"
                                        min="1"
                                        class="update-qty-input"
                                        required
                                    >
                                </td>
                                <td>₱<?= number_format($item['price'], 2) ?></td>
                                <td>₱<?= number_format($item_total, 2) ?></td>
                                <td>
                                    <button type="submit" name="delete_item" value="<?= $product_id ?>" class="btn-delete" title="Delete item">×</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" name="update_cart" class="btn btn-warning mt-2">Update Quantities</button>
            </form>

            <h3>Total: ₱<?= number_format($total, 2) ?></h3>
            <form action="" method="POST" class="mt-2">
                <input type="number" step="0.01" name="payment_amount" placeholder="Enter payment amount" required>
                <button type="submit" name="pay" class="btn btn-warning">Pay</button>
                <button type="submit" name="clear_cart" class="btn-clear">Clear</button>
            </form>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    $('.product-row').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const barcode = $(this).data('barcode');
        console.log('Product row clicked:', barcode); // Debug
        if (barcode) {
            $.ajax({
                url: 'pos.php',
                method: 'POST',
                data: {
                    barcode: barcode,
                    add_to_cart: 1,
                    quantity: 1
                },
                success: function(response) {
                    console.log('Product added to cart');
                    location.reload(); // Refresh to update cart
                },
                error: function(xhr, status, error) {
                    console.error('Error adding to cart:', error);
                    alert('Failed to add product to cart.');
                }
            });
        } else {
            alert('Error: Product barcode not found.');
        }
    });

    function filterByCategory(category) {
        const url = new URL(window.location.href);
        if (category === 'all') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category);
        }
        window.location.href = url.toString();
    }

    // Live Date and Time Display
    function updateDateTime() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        };
        const formattedDateTime = now.toLocaleString('en-US', options);
        document.getElementById('datetime').textContent = `Current Time: ${formattedDateTime}`;
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Function to fetch pending orders
    function fetchPendingOrders() {
        $.ajax({
            url: 'fetch_pending_orders.php',
            method: 'GET',
            success: function(data) {
                $('.notification-table tbody').html(data);
            },
            error: function() {
                console.log('Error fetching pending orders');
            }
        });
    }

    setInterval(fetchPendingOrders, 10000);
    fetchPendingOrders();
});
</script>
</body>
</html>