<?php
session_start();

if (!isset($_SESSION['order_details']) || !isset($_SESSION['delivery_info'])) {
    header("Location: main.php");
    exit;
}

$order = $_SESSION['order_details'];
$delivery = $_SESSION['delivery_info'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Confirmation - Furrfect Pawshop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body class="bg-yellow-50 text-yellow-900 font-sans">

  <div class="max-w-3xl mx-auto p-8 mt-10 bg-white shadow-lg rounded-lg border border-yellow-200">
    <h1 class="text-3xl font-bold text-center text-yellow-800 mb-6">Order Confirmation</h1>

    <div class="mb-6 text-center">
      <p class="text-lg">Thank you for your order, <strong><?php echo htmlspecialchars($delivery['name']); ?></strong>!</p>
      <p class="text-sm text-gray-600">Your order has been placed successfully.</p>
    </div>

    <div class="mb-6 border-t pt-4">
      <h2 class="text-xl font-semibold mb-2">Order Summary</h2>
      <p><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></p>
      <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_mode']); ?></p>
    </div>

    <div class="mb-6 border-t pt-4">
      <h2 class="text-xl font-semibold mb-2">Delivery Information</h2>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($delivery['name']); ?></p>
      <p><strong>Address:</strong> <?php echo htmlspecialchars($delivery['address']); ?></p>
      <p><strong>Contact:</strong> <?php echo htmlspecialchars($delivery['contact']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($delivery['email']); ?></p>
      <?php if (!empty($delivery['notes'])): ?>
        <p><strong>Notes:</strong> <?php echo htmlspecialchars($delivery['notes']); ?></p>
      <?php endif; ?>
    </div>

    <div class="mb-6 border-t pt-4">
      <h2 class="text-xl font-semibold mb-2">Items Ordered</h2>
      <ul class="space-y-2">
        <?php foreach ($order['cart'] as $item): ?>
          <li class="flex justify-between items-center">
            <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo (int)$item['quantity']; ?>)</span>
            <span>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="text-lg font-bold flex justify-between border-t pt-4 mb-6">
      <span>Total Amount:</span>
      <span>₱<?php echo number_format($order['total'], 2); ?></span>
    </div>

    <div class="flex justify-center space-x-4 no-print">
      <button onclick="window.print()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded shadow">
        Print Receipt
      </button>
      <button onclick="window.location.href='main.php'" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded shadow">
        Back to Home
      </button>
    </div>
  </div>

</body>
</html>

<?php
// ✅ Clear session data after rendering the page
unset($_SESSION['order_details']);
unset($_SESSION['delivery_info']);
unset($_SESSION['cart']); // Optional: also clear cart if stored separately
?>