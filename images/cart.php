<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        $id = $_POST['remove'];
        unset($_SESSION['cart'][$id]);
    }
    if (isset($_POST['update'])) {
        $id = $_POST['update'];
        $qty = max(1, intval($_POST['quantity'][$id] ?? 1));
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    if (isset($_POST['checkout'])) {
        header("Location: checkout.php");
        exit;
    }
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Cart - Furrfect Pawshop</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f5f0e6;
            color: #5a4d3d; /* Match header text color */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 32px 28px;
            background: #fdf6ee; /* Slightly lighter beige to match header */
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(166, 123, 91, 0.13);
        }

        h1 {
            font-weight: 700;
            color: #8c7b6b; /* Header brown */
            margin-bottom: 20px;
            text-align: left;
        }

        a {
            text-decoration: none;
            color: #c9a06c; /* Header accent color */
            font-weight: 600;
            transition: color 0.2s;
        }

        a:hover {
            color: #8c7b6b;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 14px 16px;
            text-align: left;
            font-size: 1rem;
        }

        thead th {
            background-color: #c9a06c; /* Header background */
            color: #fff;
            font-weight: 600;
            border-radius: 8px 8px 0 0;
            letter-spacing: 1px;
        }

        tbody tr {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(166, 123, 91, 0.05);
            transition: background 0.2s;
        }

        tbody tr:hover {
            background-color: #f5e9d7;
        }

        input[type="number"] {
            width: 70px;
            padding: 7px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            background: #f5f0e6;
            color: #5a4d3d;
        }

        button {
            cursor: pointer;
            background: linear-gradient(90deg, #c9a06c 0%, #8c7b6b 100%);
            border: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            transition: background 0.2s;
            font-family: 'Poppins', Arial, sans-serif;
        }

        button:hover {
            background: linear-gradient(90deg, #8c7b6b 0%, #c9a06c 100%);
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .remove-btn {
            background: #e74c3c;
            color: #fff;
        }

        .remove-btn:hover {
            background: #c0392b;
        }

        .checkout-btn {
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
            background: linear-gradient(90deg, #c9a06c 0%, #8c7b6b 100%);
            margin-top: 20px;
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .checkout-btn:hover {
            background: linear-gradient(90deg, #8c7b6b 0%, #c9a06c 100%);
        }

        p {
            font-size: 1.1rem;
            color: #8c7b6b;
            text-align: center;
        }

        strong {
            font-weight: 700;
            color: #8c7b6b;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1>Your Cart</h1>
            <a href="index.php">Continue Shopping</a>
        </div>
    </header>

    <main class="container">
        <?php if (empty($_SESSION['cart'])) : ?>
            <p>Your cart is empty.</p>
        <?php else : ?>
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalPrice = 0;
                        foreach ($_SESSION['cart'] as $id => $item) :
                            $itemTotal = $item['price'] * $item['quantity'];
                            $totalPrice += $itemTotal;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>₱<?= number_format($item['price'], 2) ?></td>
                                <td>
                                    <input type="number" name="quantity[<?= $id ?>]" value="<?= $item['quantity'] ?>" min="1" />
                                </td>
                                <td>₱<?= number_format($itemTotal, 2) ?></td>
                                <td>
                                    <div class="actions">
                                        <button type="submit" name="update" value="<?= $id ?>">Update</button>
                                        <button type="submit" name="remove" value="<?= $id ?>" class="remove-btn">Remove</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3"><strong>Total Price</strong></td>
                            <td colspan="2"><strong>₱<?= number_format($totalPrice, 2) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" name="checkout" class="checkout-btn">Checkout</button>
            </form>
        <?php endif; ?>
    </main>
</body>

</html>
